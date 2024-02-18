<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use App\Models\Action;
use App\Models\Excess;
use App\Models\Series;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\Assignee;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApproverController extends Controller
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
        $pendingStatus = getRequestStatus('pending');

        $tickets = Ticket::with('status', 'user', 'action')
            ->where('status_id', $pendingStatus->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $series = null;

        foreach ($tickets as $ticket) {
            if ($ticket->type == 'excess') {
                $series = Series::where('id', $ticket->request_details[0]['series_id'])->first();
            }
        }

        return view('approver.index', [
            'tickets' => $tickets,
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
        $button = $request->action_btn;

        DB::beginTransaction();

        $ticket = Ticket::where('id', $id)->first();
        $user = User::where('id', auth()->user()->id)->first();
        $ticketProps = ['user'   => $user,];

        try {
            if ($button == 'approved') {
                $ticketProps['notes'] = $request->approved_notes;
                $status = Status::where('category', 'request')
                    ->where('description', 'approved')
                    ->first();

                if ($ticket->type == 'excess') {
                    $action = Action::select('id')
                        ->where('description', 'approve worksheet')
                        ->first();

                    $ticketProps['action'] = $action;
                    $ticketProps['status'] = $status;

                    updateTicketByApprover($ticket, $ticketProps);

                    $excesses = Excess::where('series_id', $ticket->request_details[0]['series_id']);
                    $excesses->update([
                        'status_id' => Status::where('category', 'excess')->where('description', 'published')->first()->id
                    ]);

                    createActionLog(auth()->user(), $action, 'Approved excess request ticket ID: ' . $ticket->ticket_id . ' by ' . $user->name);
                }

                if ($ticket->type == 'loan') {
                    $actionBeforeUpdate = Action::where('id', $ticket->action_id)->first();

                    $actionForUpdate = Action::select('id')
                        ->where('description', Str::contains($actionBeforeUpdate->description, 'update') ?  'approve update loan' : 'approve add loan')
                        ->first();

                    $ticketProps['action'] = $actionForUpdate;
                    $ticketProps['status'] = $status;

                    updateTicketByApprover($ticket, $ticketProps);

                    Loan::updateOrCreate(
                        ['assignee_id' => $request->loan_assignee],
                        [
                            'assignee_id'                => $request->loan_assignee,
                            'current_subscription_count' => $request->loan_current_subscription_count,
                            'total_subscription_count'   => $request->loan_total_subscription_count,
                            'status_id'                  => $request->loan_status,
                            'subscription_fee'           => $request->loan_subscription_fee,
                            'notes'                      => $request->loan_notes
                        ]
                    );

                    createActionLog(auth()->user(), $actionForUpdate, 'Approved loan request ticket ID: ' . $ticket->ticket_id . ' by ' . $user->name);
                }

                if ($ticket->type == 'assignee') {
                    $actionBeforeUpdate = Action::where('id', $ticket->action_id)->first();

                    $actionForUpdate = Action::select('id')
                        ->where('description', Str::contains($actionBeforeUpdate->description, 'update') ? 'approve update profile' : 'approve add profile')
                        ->first();

                    $ticketProps['action'] = $actionForUpdate;
                    $ticketProps['status'] = $status;

                    updateTicketByApprover($ticket, $ticketProps);

                    Assignee::updateOrCreate(
                        ['id' => Assignee::where('account_no', $request->account_no)->first()->id],
                        [
                            'assignee'      => $request->assignee,
                            'assignee_code' => $request->assignee_code,
                            'position_id'   => $request->position,
                            'account_no'    => $request->account_no,
                            'phone_no'      => $request->phone_no,
                            'allowance'     => $request->allowance,
                            'plan_id'       => $request->plan,
                            'notes'         => $request->assignee_notes,
                            'SIM_only'      => $request->SIM_only == 'Yes' ? 1 : 0
                        ]
                    );

                    createActionLog(auth()->user(), $actionForUpdate, 'Approved assignee update request ticket ID: ' . $ticket->ticket_id . ' by ' . $user->name);
                }

                DB::commit();

                return redirect('/approver')->with('status', 'Successfully approved request ticket ID: ' . $ticket->ticket_id);
            }

            if ($button == 'rejected') {

                $status = Status::where('category', 'request')
                    ->where('description', 'rejected')
                    ->first();

                $action = Action::select('id')
                    ->where('description', 'reject request')
                    ->first();

                $ticketProps['notes'] = $request->rejected_notes;
                $ticketProps['action'] = $action;
                $ticketProps['status'] = $status;

                updateTicketByApprover($ticket, $ticketProps);

                if ($ticket->type == 'excess') {
                    $excesses = Excess::where('series_id', $ticket->request_details[0]['series_id']);
                    $excesses->update([
                        'status_id' => Status::where('category', 'excess')->where('description', 'rejected')->first()->id
                    ]);
                }

                createActionLog(auth()->user(), $action, 'Rejected ticket ID: ' . $ticket->ticket_id . ' by ' . $user->name . ' due to  ' . $request->rejected_notes);

                DB::commit();

                return redirect('/approver')->with('status', 'Successfully rejected request ticket ID: ' . $ticket->ticket_id);
            }
        } catch (Throwable $th) {
            DB::rollBack();
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
