@props(['tickets', 'series'])

@foreach($tickets as $ticket)
<x-popup :id="'ticket_popup_' . $ticket->id" :title="$ticket->ticket_id . ' Ticket Details'"
    :size="$ticket->type == 'excess' ? 'xl' : 'lg'" :button="''" :dnone="true" :post="''">
    @include('layouts.ticket-details', ['series' => $series])
</x-popup>
@endforeach