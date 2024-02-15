@props(['id'])
<table class="table table-bordered py-3 table-striped" id="{{ $id }}" width="100%">
    {{ $slot }}
</table>