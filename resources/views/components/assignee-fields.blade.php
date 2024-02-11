@props(['assignee', 'positions', 'plans', 'action'])

<div class="row">
    <div class="col-lg-4 pb-3">
        <label for="assignee" class="form-label">
            Assignee
            @if ($action == 'add')
            <span class="text-danger fw-bold"> *</span>
            @endif
        </label>
        <input type="text" class="form-control" id="{{ $action == 'add' ? 'assignee' : '' }}" name="assignee"
            value="{{ $assignee->assignee ?? '' }}" {{ $action=='update' ? 'readonly' : '' }}
            placeholder="{{ $action == 'add' ? 'Last Name, First Name' : '' }}">
    </div>
    <div class="col-lg-4 pb-3">
        <label for="assignee_code" class="form-label">
            ID No.
            @if ($action == 'add')
            <span class="text-danger fw-bold"> *</span>
            @endif
        </label>
        <input type="text" class="form-control" id="{{ $action == 'add' ? 'assignee_code' : '' }}" name="assignee_code"
            value="{{ $assignee->assignee_code ?? '' }}" {{ $action=='update' ? 'readonly' : '' }}>
    </div>
    <div class="col-lg-4 pb-3">
        <label for="position" class="form-label">
            Position
            @if ($action == 'add')
            <span class="text-danger fw-bold"> *</span>
            @endif
        </label>
        <select class="form-select" id="{{ $action == 'add' ? 'position' : '' }}" name="position">
            <option {{ !$assignee ? 'disabled' : '' }} selected value="{{ $assignee->position_id
                    ?? '' }}">{{ $assignee->position->description ??
                'Select Position'
                }}</option>

            @foreach ($positions as $position)
            <option value="{{ $position->id }}">{{ $position->description}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-4 pb-3">
        <label for="account_no" class="form-label">Account No. <span class="text-danger fw-bold">*</span></label>
        <input type="text" class="form-control"
            id="{{ $assignee && $action == 'update' ? 'account_no_' . $assignee->id : 'account_no' }}" name="account_no"
            value="{{ $assignee->account_no ?? '' }}" placeholder="{{ $action == 'add' ? '9 digit account no.' : '' }}">
    </div>
    <div class="col-lg-4 pb-3">
        <label for="phone_no" class="form-label">Mobile No. <span class="text-danger fw-bold">*</span></label>
        <input type="text" class="form-control"
            id="{{ $assignee && $action == 'update' ? 'phone_no_' . $assignee->id : 'phone_no' }}" name="phone_no"
            value="{{ $assignee->phone_no ?? '' }}" placeholder="{{ $action == 'add' ? '11 digit phone no.' : '' }}">
    </div>
    <div class="col-lg-4 pb-3">
        <label for="allowance" class="form-label">Allowance <span class="text-danger fw-bold">*</span></label>
        <div class="input-group">
            <span class="input-group-text">Php</span>
            <input type="text" class="form-control"
                id="{{ $assignee && $action == 'update' ? 'allowance_' . $assignee->id : 'allowance' }}"
                name="allowance" value="{{ $assignee->allowance ?? '' }}">
        </div>
    </div>
    <div class="col-lg-8 pb-3">
        <label for="plan" class="form-label">
            Plan
            @if ($action == 'add')
            <span class="text-danger fw-bold"> *</span>
            @endif
        </label>
        <select class="form-select plan-select" name="plan"
            id="{{ $assignee && $action == 'update' ? 'plan_' . $assignee->id : 'add_assignee_plan' }}">
            <option {{ !$assignee ? 'disabled' : '' }} selected value="{{ $assignee->plan_id ?? '' }}">
                {{ $assignee->plan->provider ?? 'Select Plan' }} {{ $assignee->plan->description ?? '' }}
            </option>

            @foreach ($plans as $plan)
            <option value="{{ $plan->id }}">{{ $plan->provider }} {{ $plan->description}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-4 pb-3">
        <label for="plan_fee" class="form-label">Plan Monthly Fee</label>
        <div class="input-group">
            <span class="input-group-text">Php</span>
            <input type="text" class="form-control" name="plan_fee"
                id="{{ $assignee && $action == 'update' ? 'plan_fee_' . $assignee->id : 'add_assignee_plan_fee' }}"
                value="{{ $assignee->plan->subscription_fee ?? '' }}" readonly>
        </div>
    </div>
    <div class="col-12 pb-3">
        <label for="notes" class="form-label">Notes</label>
        <textarea class="form-control" name="notes" rows="3">{{ $assignee->notes ?? '' }}</textarea>
    </div>
    <div class="pb-3 col-12">
        <div class="form-check">
            <input {{ $assignee && $assignee->SIM_only == 1 ? 'checked' : '' }} type="checkbox"
            class="form-check-input"
            name="SIM_only">
            <label class="form-check-label" for="SIM_only">SIM Only</label>
        </div>
    </div>
</div>