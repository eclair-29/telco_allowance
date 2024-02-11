@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header">{{ __('Telco Allowance Journal') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <div class="pb-3 d-flex justify-content-end">
                        <button type="button" class="btn btn-outline-success" id="generate_worksheet_btn">Generate
                            Worksheet</button>
                    </div>

                    <x-table :id="'excess_table'">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Account No.</th>
                                <th>Mobile No.</th>
                                <th>ID No.</th>
                                <th>Assignee</th>
                                <th>Position</th>
                                <th>Allowance</th>
                                <th>Plan Fee</th>
                                <th>Excess Allowance</th>
                                <th>12% VAT</th>
                            </tr>
                        </thead>
                    </x-table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/datatable_overrides.js') }}"></script>
<script src="{{ asset('js/excess.js') }}"></script>
@endsection