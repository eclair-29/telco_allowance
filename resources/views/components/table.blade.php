@props(['id'])
<table class="table table-bordered py-3" id="{{ $id }}" width="100%">
    {{ $slot }}
</table>