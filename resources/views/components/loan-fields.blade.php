@props(['loan', 'assignees', 'positions', 'statuses', 'action'])

<div class="row">
    <div class="col-lg-4 pb-3">
        <label for="position" class="form-label">
            Assignee
            @if ($action == 'add')
            <span class="text-danger fw-bold"> *</span>
            @endif
        </label>
        <select class="form-select assignee-select"
            id="{{ $action == 'add' ? 'loan_assignee' : 'loan_assignee_' . $loan->id }}" name="assignee">
            <option {{ !$loan ? 'disabled' : '' }} selected value="{{ $loan->assignee_id
                    ?? '' }}">{{ $loan->assignee->assignee ??
                'Select Assignee'
                }}</option>

            @foreach ($assignees as $assignee)
            <option value="{{ $assignee->id }}">{{ $assignee->assignee}}</option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-4 pb-3">
        <label for="assignee_code" class="form-label">ID No.</label>
        <input type="text" class="form-control"
            id="{{ $loan && $action == 'update' ? 'loan_assignee_code_' . $loan->id : 'loan_assignee_code' }}"
            name="assignee_code" value="{{ $loan->assignee->assignee_code ?? '' }}" readonly>
    </div>

    <div class="col-lg-4 pb-3">
        <label for="position" class="form-label">Position</label>
        <input type="text" class="form-control"
            id="{{ $loan && $action == 'update' ? 'loan_assignee_position_' . $loan->id : 'loan_assignee_position' }}"
            name="position" value="{{ $loan->assignee->position->description ?? '' }}" readonly>
    </div>

    <div class="col-lg-4 pb-3">
        <label for="current_subscription_count" class="form-label">
            Current Month Count
            <span class="text-danger fw-bold"> *</span>
        </label>
        <input type="text" class="form-control"
            id="{{ $loan && $action == 'update' ? 'current_subscription_count_' . $loan->id : 'current_subscription_count' }}"
            name="current_subscription_count" value="{{ $loan->current_subscription_count ?? '' }}">
    </div>

    <div class="col-lg-4 pb-3">
        <label for="total_subscription_count" class="form-label">
            Loan Total Months
            <span class="text-danger fw-bold"> *</span>
        </label>
        <input type="text" class="form-control"
            id="{{ $loan && $action == 'update' ? 'total_subscription_count_' . $loan->id : 'total_subscription_count' }}"
            name="total_subscription_count" value="{{ $loan->total_subscription_count ?? '' }}">
    </div>

    <div class="col-lg-4 pb-3">
        <label for="subscription_fee" class="form-label">
            Loan Subscription Fee
            <span class="text-danger fw-bold"> *</span>
        </label>
        <div class="input-group">
            <span class="input-group-text">Php</span>
            <input type="text" class="form-control" name="subscription_fee"
                id="{{ $loan && $action == 'update' ? 'loan_subscription_fee_' . $loan->id : 'loan_subscription_fee' }}"
                value="{{ $loan->subscription_fee ?? '' }}">
        </div>
    </div>

    <div class="col-lg-4 pb-3">
        <label for="status" class="form-label">
            Loan Status
            @if ($action == 'add')
            <span class="text-danger fw-bold"> *</span>
            @endif
        </label>
        <select class="form-select" id="{{ $action == 'add' ? 'loan_status' : 'loan_status_' . $loan->id }}"
            name="status">
            <option {{ !$loan ? 'disabled' : '' }} selected value="{{ $loan->status_id
                    ?? '' }}">{{ $loan && $action == 'update' ? Str::title($loan->status->description) : 'Select
                Status' }}</option>

            @foreach ($statuses as $status)
            <option value="{{ $status->id }}">{{ Str::title($status->description) }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-12 pb-3">
        <label for="notes" class="form-label">Notes</label>
        <textarea class="form-control" name="notes" rows="3">{{ $assignee->notes ?? '' }}</textarea>
    </div>
</div>