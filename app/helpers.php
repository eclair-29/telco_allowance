<?php

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Status;
use App\Models\Position;
use App\Models\Ticket;
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
