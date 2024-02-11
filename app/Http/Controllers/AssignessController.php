<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Loan;
use App\Models\Plan;
use App\Models\Action;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\Assignee;
use App\Models\Position;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreAssigneeRequest;
use App\Http\Requests\UpdateAssigneeRequest;

class AssignessController extends Controller
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
        $status = getActiveStatusByCategory('assignee');

        $assignees = Assignee::with('plan')
            ->where('status_id', $status->id)
            ->orderBy('assignee', 'asc')
            ->get();

        $positions = Position::all();

        $plans = Plan::all();

        return view('publisher.assignees', [
            'assignees' => $assignees,
            'positions' => $positions,
            'plans' => $plans
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
    public function store(StoreAssigneeRequest $request)
    {
        $validated = $request->validated();

        $validated['assignee'] = Str::upper($request->assignee);
        $validated['SIM_only'] = $request->SIM_only;
        $validated['notes'] = $request->notes;

        DB::beginTransaction();

        try {
            $ticketId = createTicketId('asg');
            $type = 'assignee';
            $userId = auth()->user()->id;
            $statusId = getRequestStatus('pending')->id;
            $requestDetails = collect($validated);

            Ticket::create([
                'ticket_id' => $ticketId,
                'type' => $type,
                'user_id' => $userId,
                'request_details' => $requestDetails,
                'status_id' => $statusId,
            ]);

            $action = Action::select('id')
                ->where('description', 'add profile')
                ->first();

            createActionLog(auth()->user(), $action, 'Assignee inclusion request with ticket ID: ' . $ticketId . ' pending for approval');

            DB::commit();

            return [
                'response' => 'success',
                'alert' => 'Successfully sent Assignee Update request with ticket ID: ' . $ticketId . ' pending for approval.'
            ];
        } catch (Throwable $th) {
            DB::rollBack();

            return [
                'response' => 'error',
                'alert' => 'Unable to update assignee. Please contact ISD for assistance.'
            ];
        }

        return $validated;
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
    public function update(UpdateAssigneeRequest $request, $id)
    {
        $validated = $request->validated();

        $validated['assignee'] = Str::upper($request->assignee);
        $validated['assignee_code'] = $request->assignee_code;
        $validated['plan'] = $request->plan;
        $validated['SIM_only'] = $request->SIM_only;
        $validated['position'] = $request->position;
        $validated['notes'] = $request->notes;

        DB::beginTransaction();

        try {
            $ticketId = createTicketId('asg');
            $type = 'assignee';
            $userId = auth()->user()->id;
            $statusId = getRequestStatus('pending')->id;
            $requestDetails = collect($validated);

            Ticket::create([
                'ticket_id' => $ticketId,
                'type' => $type,
                'user_id' => $userId,
                'request_details' => $requestDetails,
                'status_id' => $statusId,
            ]);

            $action = Action::select('id')
                ->where('description', 'update profile')
                ->first();

            createActionLog(auth()->user(), $action, 'Assignee update request for ' . $request->assignee . ' with ticket ID: ' . $ticketId . ' pending for approval');

            DB::commit();

            return [
                'response' => 'success',
                'alert' => 'Successfully sent Assignee Update request with ticket ID: ' . $ticketId . ' pending for approval.'
            ];
        } catch (Throwable $th) {
            DB::rollBack();
            return [
                'response' => 'error',
                'alert' => 'Unable to update assignee. Please contact ISD for assistance.'
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
