@props(['id', 'size', 'title', 'button', 'dnone', 'post'])

<div class="modal fade popup" id="{{ $id }}" tabindex="-1">
    <div class="modal-dialog modal-{{ $size }}">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title">{{ $title }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="{{ $post }}" class="btn btn-outline-success {{ $dnone ? 'd-none' : '' }}"
                    type="submit">{{
                    $button }}</button>
            </div>
        </div>
    </div>
</div>