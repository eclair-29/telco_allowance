<?php

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Series;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\Assignee;
use App\Models\Position;
use App\Models\Tracking;
use Illuminate\Support\Str;

function getPlanByDescription($desc)
{
    $planByDesc = Plan::where('description', $desc)->first();
    return $planByDesc;
}

function getPositionByDescription($desc)
{
    $positionByDesc = Position::where('description', Str::title($desc))->first();
    return $positionByDesc;
}

function getActiveStatusByCategory($category)
{
    $status = Status::select('id')
        ->where('category', $category)
        ->where('description', 'active')
        ->first();

    return $status;
}

function getRequestStatus($desc)
{
    $status = Status::select('id', 'description')
        ->where('category', 'request')
        ->where('description', $desc)
        ->first();

    return $status;
}

function createActionLog($user, $action, $description)
{
    Tracking::create([
        'user_id' => $user->id,
        'action_id' => $action->id,
        'description' => $description
    ]);
}

function createTicketId($type)
{
    $currentMonth = Carbon::now()->format('m');
    $series = $currentMonth . '' . Carbon::now()->format('y');
    $ticketCount = Ticket::whereMonth('created_at', $currentMonth)->count();
    $zeroFilledId = str_pad($ticketCount + 1, $ticketCount > 9 ? 5 : 4, '0', STR_PAD_LEFT);

    // ticket id
    $ticketId = Str::upper($type) . '_' . $series . '_' . $zeroFilledId;
    return $ticketId;
}

function getTicketsByType($type)
{
    $tickets = Ticket::with('status', 'user', 'action')
        ->where('type', $type)
        ->where('status_id', getRequestStatus('pending')->id)
        ->get();

    return $tickets;
}

function getCurrentSeries()
{
    $currentSeries = Carbon::now()->format('M') . ' ' . Carbon::now()->format('Y');
    return $currentSeries;
}

function getExcessesBySeries($series)
{
    $query = DB::select(
        "SELECT 
            a.account_no,
            a.phone_no,
            a.assignee_code,
            a.assignee,
            pos.description as 'position',
            a.allowance,
            plan.subscription_fee as 'plan_fee',
            e.excess_balance,
            e.excess_balance_vat,
            if(
                l.status_id = (select id FROM statuses where description = 'finished'), 
                    null, 
                    concat(l.current_subscription_count, ' / ' , l.total_subscription_count)
            ) as loan_progress,
            if(l.status_id = (select id FROM statuses where description = 'finished'), null, l.subscription_fee) as 'loan_fee',
            e.excess_charges,
            e.excess_charges_vat,
            e.non_vattable,
            e.total_bill,
            e.deduction,
            e.notes,
            s.description as 'status',
            e.series_id,
            e.assignee_excess_id
        FROM excesses e 
            LEFT JOIN assignees a ON e.assignee_id = a.id
            LEFT JOIN positions pos ON a.position_id = pos.id
            LEFT JOIN plans plan ON a.plan_id = plan.id
            LEFT JOIN loans l ON a.id = l.assignee_id
            LEFT JOIN statuses s ON e.status_id = s.id
        WHERE e.series_id = ?;",
        [$series->id]
    );

    return $query;
}

function updateTicketByApprover($ticket, $props)
{
    $ticket->update([
        'status_id'     => $props['status']->id,
        'notes'         => $props['notes'],
        'checked_by'    => $props['user']->name,
        'action_id'     => $props['action']->id
    ]);
}
