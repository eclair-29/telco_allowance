@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header">{{ __('Approvals') }}</div>

                <div class="card-body">
                    <x-alert />

                </div>
            </div>
        </div>
    </div>
</div>
@endsection