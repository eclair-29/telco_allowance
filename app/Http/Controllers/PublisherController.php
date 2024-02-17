<?php

namespace App\Http\Controllers;

use Throwable;
use Carbon\Carbon;
use App\Models\Loan;
use App\Models\Plan;
use App\Models\Action;
use App\Models\Excess;
use App\Models\Series;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\Assignee;
use App\Models\Position;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublisherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getExcessesBySeries(Request $request)
    {
        $excesses['data'] = getExcessesBySeries(Series::where('id', $request->series_id)->first());
        return $excesses;
    }

    public function publish(Request $request)
    {
        DB::beginTransaction();

        $excesses = (array) json_decode($request->excesses, true);

        $excessForApprovalStatus = Status::where('description', 'for approval')
            ->where('category', 'excess')
            ->first();

        $excessSeries = Series::where('description', getCurrentSeries())->first();

        $excessesRef = $excesses[0];

        if (
            $excessesRef['series_id'] == $excessSeries->id
            && $excessesRef['status'] == 'for approval'
        ) {
            return [
                'response' => 'error',
                'alert' => 'Already sent a ticket approval request for this series',
            ];
        }

        if (
            $excessesRef['series_id'] == $excessSeries->id
            && $excessesRef['status'] == 'published'
        ) {
            return [
                'response' => 'error',
                'alert' => 'This series has already been published',
            ];
        }

        try {
            $ticketId = createTicketId('excs');
            $type = 'excess';
            $userId = auth()->user()->id;
            $statusId = getRequestStatus('pending')->id;
            $requestDetails = collect($excesses);

            $action = Action::select('id')
                ->where('description', 'publish worksheet')
                ->first();

            foreach ($excesses as $excess) {
                if (isset($excess['assignee_excess_id'])) {
                    Excess::where('assignee_excess_id', $excess['assignee_excess_id'])
                        ->first()
                        ->update([
                            'status_id' => $excessForApprovalStatus->id,
                        ]);
                }
            }

            Ticket::create([
                'ticket_id' => $ticketId,
                'type' => $type,
                'user_id' => $userId,
                'request_details' => $requestDetails,
                'status_id' => $statusId,
                'notes' => $request->notes,
                'action_id' => $action->id
            ]);

            $action = Action::select('id')
                ->where('description', 'publish worksheet')
                ->first();

            createActionLog(auth()->user(), $action, 'Excess publish request with ticket ID: ' . $ticketId . ' is pending for approval');

            DB::commit();

            return [
                'response' => 'success',
                'alert' => 'Successfully sent Excess Publish request with ticket ID: ' . $ticketId . ' pending for approval.'
            ];
        } catch (Throwable $th) {
            DB::rollBack();

            return [
                'response' => 'error',
                'alert' => 'Unable to publish excess. Please contact ISD for assistance.' . $th
            ];
        }
    }

    public function save(Request $request)
    {
        DB::beginTransaction();

        $currentSeries = getCurrentSeries();
        $excesses = (array) json_decode($request->excesses, true);
        try {
            foreach ($excesses as $excess) {
                if (isset($excess['assignee_excess_id'])) {
                    Excess::where('assignee_excess_id', $excess['assignee_excess_id'])
                        ->first()
                        ->update([
                            'deduction' => $excess['deduction'],
                            'excess_balance_vat' => $excess['excess_balance_vat'],
                            'excess_charges_vat' => $excess['excess_charges_vat'],
                            'total_bill' => $excess['total_bill'],
                            'excess_balance' => $excess['excess_balance'],
                            'excess_charges' => $excess['excess_charges'],
                            'non_vattable' => $excess['non_vattable'],
                            'notes' => $excess['notes'],
                        ]);
                }
            }

            $user = auth()->user();
            $action = Action::select('id')
                ->where('description', 'draft worksheet')
                ->first();

            createActionLog($user, $action, 'Saved ' . $currentSeries . ' Monthly Worksheet');

            DB::commit();

            return [
                'response' => 'success',
                'alert' => $currentSeries . ' worksheet saved successfully.',
                // 'data' => $excesses
            ];
        } catch (Throwable $th) {
            DB::rollBack();

            return [
                'response' => 'error',
                'alert' => 'Error on saving worksheet. Please contact ISD for support.' . $th
            ];
        }
    }

    public function generate()
    {
        DB::beginTransaction();
        try {
            $currentSeries = getCurrentSeries();
            $series = Series::where('description', $currentSeries)->first();

            if (!$series) {
                Series::create([
                    'description' => $currentSeries
                ]);

                $user = auth()->user();
                $action = Action::select('id')
                    ->where('description', 'generate worksheet')
                    ->first();

                createActionLog($user, $action, 'Generated ' . $currentSeries . ' Monthly Worksheet');

                $assigneeStatus = getActiveStatusByCategory('assignee');

                $assignees = Assignee::with('position', 'plan', 'loan')
                    ->where('status_id', $assigneeStatus->id)
                    ->orderBy('assignee', 'asc')
                    ->get();

                $excessStatus = Status::where('description', 'draft')
                    ->where('category', 'excess')
                    ->first();

                foreach ($assignees as $assignee) {
                    $assigneeExcessId = Str::replace(' ', '_', Str::upper($currentSeries)) . '_' . $assignee->account_no;

                    $excessBalanceVat = round($assignee->plan->subscription_fee * 0.12, 2);

                    $loanOngoingStatus = Status::where('category', 'loan')
                        ->where('description', 'ongoing')
                        ->first();

                    $loanFinishedStatus = Status::where('category', 'loan')
                        ->where('description', 'finished')
                        ->first();

                    $assigneeLoan = Loan::where('assignee_id', $assignee->id)
                        ->where('status_id', $loanOngoingStatus->id)
                        ->first();

                    $loanFee = $assigneeLoan ? $assigneeLoan->subscription_fee : 0;

                    if ($assigneeLoan) {
                        $assigneeLoan->update(['current_subscription_count' => $assigneeLoan->current_subscription_count + 1]);

                        if ($assigneeLoan->current_subscription_count > $assigneeLoan->total_subscription_count) {
                            $assigneeLoan->update([
                                'status_id' => $loanFinishedStatus->id,
                                'current_subscription_count' => $assigneeLoan->total_subscription_count
                            ]);

                            $loanFee = 0;
                        }
                    }

                    $excessChargesVat = $assigneeLoan ? round($loanFee * 0.12, 2) : 0;
                    $deduction = $assigneeLoan ? round($loanFee + $excessChargesVat, 2) : 0;
                    $totalBill = round(
                        $assignee->plan->subscription_fee
                            + $excessBalanceVat
                            + $loanFee
                            + $excessChargesVat,
                        2
                    );

                    Excess::create([
                        'assignee_excess_id' => $assigneeExcessId,
                        'excess_balance_vat' => $excessBalanceVat,
                        'excess_charges_vat' => $excessChargesVat,
                        'series_id' => Series::where('description', $currentSeries)->first()->id,
                        'assignee_id' => $assignee->id,
                        'status_id' => $excessStatus->id,
                        'deduction' => $deduction,
                        'total_bill' => $totalBill
                    ]);
                }

                $excesses = getExcessesBySeries(Series::where('description', $currentSeries)->first());

                DB::commit();

                return [
                    'response' => 'success',
                    'alert' => $currentSeries . ' worksheet generated successfully.',
                    'data' => $excesses
                ];
            } else {
                return [
                    'response' => 'error',
                    'alert' => 'Series ' . $currentSeries . ' already exists.'
                ];
            }
        } catch (Throwable $th) {
            DB::rollBack();

            return [
                'response' => 'error',
                'alert' => 'Error on generating worksheet. Please contact ISD for support.'
            ];
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $series = Series::all();

        return view('publisher.index', [
            'series' => $series
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
