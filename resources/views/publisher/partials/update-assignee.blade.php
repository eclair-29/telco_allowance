<form class="update-assignee" action="{{ route('publisher.assignees.update', $assignee->id) }}"
    id="{{ 'update_assignee_fields_' . $assignee->id }}" method="POST">
    @csrf
    @method('PUT')
    <x-assignee-fields :assignee="$assignee" :positions="$positions" :plans="$plans" :action="'update'" />
</form>