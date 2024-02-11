<form class="add-assignee" action="{{ route('publisher.assignees.store') }}" id="add_assignee_fields" method="POST">
    @csrf
    <x-assignee-fields :assignee="null" :positions="$positions" :plans="$plans" :action="'add'" />
</form>