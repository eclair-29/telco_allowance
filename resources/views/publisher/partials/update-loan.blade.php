<form class="update-loan" action="{{ route('publisher.loans.update', $loan->id) }}"
    id="{{ 'update_loan_fields_' . $loan->id }}" method="POST">
    @csrf
    @method('PUT')
    <x-loan-fields :loan="$loan" :assignees="$assignees" :positions="$positions" :statuses="$statuses"
        :action="'update'" />
</form>