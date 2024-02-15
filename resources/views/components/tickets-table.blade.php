@props(['tickets', 'series'])

<x-table :id="'tickets_table'">
    <thead>
        <tr>
            <th class="text-center">Ticket No.</th>
            <th class="text-center">Type</th>
            <th class="text-center">Requested By</th>
            <th class="text-center">Status</th>
            <th class="text-center">Notes</th>
            <th class="text-center">Created Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tickets as $ticket)
        <tr>
            <td class="ticket-cell">
                <a class="link-success fw-bold text-decoration-none" data-bs-toggle="modal"
                    data-bs-target="#ticket_popup_{{ $ticket->id }}" href="">{{ $ticket->ticket_id }}</a>
            </td>
            <td>{{ Str::title($ticket->type) }}</td>
            <td>{{ $ticket->user->name }}</td>
            <td>{{ Str::title($ticket->status->description) }}</td>
            <td class="notes-cell">{{ $ticket->notes }}</td>
            <td>{{ $ticket->created_at }}</td>
        </tr>
        @endforeach
    </tbody>
</x-table>

<script src="{{ asset('js/datatable_overrides.js') }}"></script>

@foreach($tickets as $ticket)
<x-popup :id="'ticket_popup_' . $ticket->id" :title="$ticket->ticket_id . ' Ticket Details'"
    :size="$ticket->type == 'excess' ? 'xl' : 'lg'" :button="''" :dnone="true" :post="''">
    @include('approver.partials.ticket-details')
</x-popup>
@endforeach