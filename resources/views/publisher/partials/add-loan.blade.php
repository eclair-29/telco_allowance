<form class="add-loan" action="{{ route('publisher.loans.store') }}" id="add_loan_fields" method="POST">
    @csrf
    <x-loan-fields :loan="null" :assignees="$assignees" :positions="$positions" :statuses="$statuses" :action="'add'" />
</form>