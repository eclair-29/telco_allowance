<form class="ticket-details" id="{{ 'ticket_fields_' . $ticket->id }}">
    @csrf
    <div class="d-flex justify-content-between align-items-center pb-3">
        @if ($ticket->type == 'excess')
        <span class="fw-bold" id="excess_ticket_series_header">{{ $series->description }} Telco Rundown</span>
        @else
        <div></div>
        @endif

        <div class="d-flex justify-content-end">
            <div class="px-2">
                <button type="button" class="btn btn-outline-success" id="approve_request">Approve</button>
            </div>
            <button type="button" class="btn btn-outline-danger" id="reject_request">Reject</button>
        </div>
    </div>

    @if($ticket->type != 'excess')
    <div class="row">
        <div class="col-lg-4 pb-3">
            <label for="request_type" class="form-label">
                Request Type
            </label>
            <input type="text" class="form-control" value="{{ Str::title($ticket->type) }}" readonly>
        </div>
        <div class="col-lg-4 pb-3">
            <label for="request_type" class="form-label">
                Status
            </label>
            <input type="text" class="form-control" value="{{ Str::title($ticket->status->description) }}" readonly>
        </div>
    </div>
    @endif

    @if($ticket->type == 'assignee')
    <div class="row">
        <div class="col-lg-4 pb-3">
            <label for="assignee" class="form-label">
                Assignee
            </label>
            <input type="text" class="form-control" value="{{ $ticket->request_details['assignee'] }}" readonly>
        </div>
        <div class="col-lg-4 pb-3">
            <label for="assignee_code" class="form-label">
                ID No.
            </label>
            <input type="text" class="form-control" value="{{ $ticket->request_details['assignee_code'] }}" readonly>
        </div>
        <div class="col-lg-4 pb-3">
            <label for="position_desc" class="form-label">
                Position
            </label>
            <input type="text" class="assignee-position form-control"
                value="{{ $ticket->request_details['position_desc'] }}" readonly>
        </div>
        <div class="col-lg-4 pb-3">
            <label for="account_no" class="form-label">
                Account No.
            </label>
            <input type="text" class="form-control" value="{{ $ticket->request_details['account_no'] }}" readonly>
        </div>
        <div class="col-lg-4 pb-3">
            <label for="phone_no" class="form-label">
                Phone No.
            </label>
            <input type="text" class="form-control" value="{{ $ticket->request_details['phone_no'] }}" readonly>
        </div>
        <div class="col-lg-4 pb-3">
            <label for="allowance" class="form-label">
                Allowance
            </label>
            <input type="text" class="form-control" value="{{ $ticket->request_details['allowance'] }}" readonly>
        </div>
        <div class="col-lg-8 pb-3">
            <label for="plan_desc" class="form-label">
                Plan
            </label>
            <input type="text" class="assignee-plan form-control" value="{{ $ticket->request_details['plan_desc'] }}"
                readonly>
        </div>
        <div class="col-lg-4 pb-3">
            <label for="plan_fee" class="form-label">
                Plan Fee
            </label>
            <div class="input-group">
                <span class="input-group-text">Php</span>
                <input type="text" class="form-control" value="{{ $ticket->request_details['plan_fee'] }}" readonly>
            </div>
        </div>
        <div class="col-12 pb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea readonly class="form-control" name="notes"
                rows="3">{{ $ticket->request_details['notes'] }}</textarea>
        </div>
    </div>
    @endif

    @if($ticket->type == 'loan')
    <div class="row">
        <div class="col-lg-4 pb-3">
            <label for="assignee_full" class="form-label">
                Assignee
            </label>
            <input type="text" class="assignee form-control" value="{{ $ticket->request_details['assignee_full'] }}"
                readonly>
        </div>
        <div class="col-lg-4 pb-3">
            <label for="current_subscription_count" class="form-label">
                Current Month Count
            </label>
            <input type="text" class="form-control" value="{{ $ticket->request_details['current_subscription_count'] }}"
                readonly>
        </div>
        <div class="col-lg-4 pb-3">
            <label for="total_subscription_count" class="form-label">
                Loan Total Months
            </label>
            <input type="text" class="form-control" value="{{ $ticket->request_details['total_subscription_count'] }}"
                readonly>
        </div>
        <div class="col-lg-4 pb-3">
            <label for="subscription_fee" class="form-label">
                Assignee
            </label>
            <div class="input-group">
                <span class="input-group-text">Php</span>
                <input type="text" class="form-control" value="{{ $ticket->request_details['subscription_fee'] }}"
                    readonly>
            </div>
        </div>
    </div>
    @endif
</form>

@if ($ticket->type == 'excess')
<x-table :id="'excess_ticket_' . $ticket->id . '_table'">
    <thead>
        <tr>
            <th class="text-center">Account No.</th>
            <th class="text-center">Mobile No.</th>
            <th class="text-center">ID No.</th>
            <th class="text-center">Assignee</th>
            <th class="text-center">Position</th>
            <th class="text-center">Allowance</th>
            <th class="text-center">Plan Fee</th>
            <th class="text-center">Excess Allowance</th>
            <th class="text-center">VAT</th>
            <th class="text-center">Loan Progress</th>
            <th class="text-center">Loan Monthly Fee</th>
            <th class="text-center">Excess Charges</th>
            <th class="text-center">Charged to Asignee VAT</th>
            <th class="text-center">Non Vattable</th>
            <th class="text-center">Total Bill</th>
            <th class="text-center">For Deduction</th>
            <th class="text-center">Notes</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($ticket->request_details as $detail)
        <tr>
            <td>{{ isset($detail['account_no']) ? $detail['account_no'] : '' }}</td>
            <td>{{ isset($detail['phone_no']) ? $detail['phone_no'] : '' }}</td>
            <td>{{ isset($detail['assignee_code']) ? $detail['assignee_code'] : '' }}</td>
            <td>{{ isset($detail['assignee']) ? $detail['assignee'] : '' }}</td>
            <td>{{ isset($detail['position']) ? $detail['position'] : '' }}</td>
            <td>{{ isset($detail['allowance']) ? $detail['allowance'] : '' }}</td>
            <td>{{ isset($detail['plan_fee']) ? $detail['plan_fee'] : '' }}</td>
            <td>{{ isset($detail['excess_balance']) ? $detail['excess_balance'] : '' }}</td>
            <td>{{ isset($detail['excess_balance_vat']) ? $detail['excess_balance_vat'] : '' }}</td>
            <td>{{ isset($detail['loan_progress']) ? $detail['loan_progress'] : '' }}</td>
            <td>{{ isset($detail['loan_fee']) ? $detail['loan_fee'] : '' }}</td>
            <td>{{ isset($detail['excess_charges']) ? $detail['excess_charges'] : '' }}</td>
            <td>{{ isset($detail['excess_charges_vat']) ? $detail['excess_charges_vat'] : '' }}</td>
            <td>{{ isset($detail['non_vattable']) ? $detail['non_vattable'] : '' }}</td>
            <td>{{ isset($detail['total_bill']) ? $detail['total_bill'] : '' }}</td>
            <td>{{ isset($detail['deduction']) ? $detail['deduction'] : '' }}</td>
            <td>{{ isset($detail['notes']) ? $detail['notes'] : '' }}</td>
        </tr>
        @endforeach
    </tbody>
</x-table>

<script>
    let excess_ticket_{{ $ticket->id }}_table = new $('#excess_ticket_{{ $ticket->id }}_table').DataTable();
    overrideTable('excess_ticket_{{ $ticket->id }}');
</script>
@endif