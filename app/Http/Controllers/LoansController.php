<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Loan;
use App\Models\Action;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\Assignee;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreLoanRequest;
use App\Http\Requests\UpdateLoanRequest;

class LoansController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loans = Loan::with('assignee', 'status')->get();

        $assigneeStatus = getActiveStatusByCategory('assignee');

        $assignees = Assignee::with('plan')
            ->where('status_id', $assigneeStatus->id)
            ->orderBy('assignee', 'asc')
            ->get();

        $positions = Position::all();

        $statuses = Status::where('category', 'loan')->get();

        $tickets = getTicketsByType('loan');

        return view('publisher.loans', [
            'loans' => $loans,
            'assignees' => $assignees,
            'positions' => $positions,
            'statuses' => $statuses,
            'tickets' => $tickets,
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
    public function store(StoreLoanRequest $request)
    {
        $validated = $request->validated();

        $validated['notes'] = $request->notes;
        $validated['assignee_full'] = Assignee::where('id', $request->assignee)->first()->assignee;
        $validated['status_desc'] = Status::where('id', $request->status)->first()->description;

        $action = Action::select('id')
            ->where('description', 'add loan')
            ->first();

        DB::beginTransaction();

        try {
            $ticketId = createTicketId('loan');

            Ticket::create([
                'ticket_id' => $ticketId,
                'type' => 'loan',
                'user_id' => auth()->user()->id,
                'request_details' => collect($validated),
                'status_id' => getRequestStatus('pending')->id,
                'notes' => $request->notes,
                'action_id' => $action->id
            ]);

            $action = Action::select('id')
                ->where('description', 'add loan')
                ->first();

            createActionLog(auth()->user(), $action, 'Loan inclusion request with ticket ID: ' . $ticketId . ' pending for approval');

            DB::commit();

            return [
                'response' => 'success',
                'alert' => 'Successfully sent Loan Inclusion request with ticket ID: ' . $ticketId . ' pending for approval.'
            ];
        } catch (Throwable $th) {
            DB::rollBack();

            return [
                'response' => 'error',
                'alert' => 'Unable to add loan. Please contact ISD for assistance.'
            ];
        }
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
    public function update(UpdateLoanRequest $request, $id)
    {
        $validated = $request->validated();

        $validated['notes'] = $request->notes;
        $validated['assignee'] = $request->assignee;
        $validated['status'] = $request->status;

        $validated['assignee_full'] = Assignee::where('id', $request->assignee)->first()->assignee;
        $validated['status_desc'] = Status::where('id', $request->status)->first()->description;

        $action = Action::select('id')
            ->where('description', 'update loan')
            ->first();

        DB::beginTransaction();

        try {
            $ticketId = createTicketId('loan');

            Ticket::create([
                'ticket_id' => $ticketId,
                'type' => 'loan',
                'user_id' => auth()->user()->id,
                'request_details' => collect($validated),
                'status_id' => getRequestStatus('pending')->id,
                'notes' => $request->notes,
                'action_id' => $action->id
            ]);

            $action = Action::select('id')
                ->where('description', 'update loan')
                ->first();

            createActionLog(
                auth()->user(),
                $action,
                'Loan update request for ' . Assignee::where('id', $validated['assignee'])->first()->assignee .  ' with ticket ID: ' . $ticketId . ' pending for approval'
            );

            DB::commit();

            return [
                'response' => 'success',
                'alert' => 'Successfully sent Loan Update request with ticket ID: ' . $ticketId . ' pending for approval.'
            ];
        } catch (Throwable $th) {
            DB::rollBack();

            return [
                'response' => 'error',
                'alert' => 'Unable to update loan. Please contact ISD for assistance.'
            ];
        }
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
