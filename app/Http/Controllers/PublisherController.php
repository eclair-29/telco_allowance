<?php

namespace App\Http\Controllers;

use Throwable;
use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Action;
use App\Models\Excess;
use App\Models\Series;
use App\Models\Status;
use App\Models\Assignee;
use App\Models\Loan;
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

    public function generate()
    {
        DB::beginTransaction();
        try {
            $currentSeries = Carbon::now()->format('M') . ' ' . Carbon::now()->format('Y');
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

                $excesses['data'] = Excess::where(
                    'series_id',
                    Series::where('description', $currentSeries)->first()->id
                )->get();

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
