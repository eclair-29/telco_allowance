@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header">{{ __('Telco Allowance') }}</div>

                <div class="card-body">
                    <x-alert />

                    <div class="pb-3 d-flex">
                        <select class="border-dark form-select w-auto" id="series_select">
                            <option selected disabled>Select Series</option>

                            @foreach ($series as $data)
                            <option value="{{ $data->id }}">{{ $data->description }}</option>
                            @endforeach
                        </select>

                        <div class="btn-group px-3" role="group">
                            <button type="button" class="btn btn-outline-dark" id="generate_worksheet_btn">
                                Generate
                            </button>
                            <button type="button" class="btn btn-outline-dark" id="download_worksheet">
                                Download
                            </button>
                        </div>

                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-success" id="save_worksheet">
                                Save
                            </button>
                            <button type="button" class="btn btn-outline-success" id="publish_worksheet">
                                Publish
                            </button>
                        </div>
                    </div>

                    <div class="pb-3">
                        <span class="fw-bold" id="series_header"></span>
                        <span class="fw-bold d-block" id="status_header"></span>
                    </div>

                    <x-table :id="'excess_table'">
                        <thead>
                            <tr>
                                <th class="text-center">Account No.</th>
                                <th class="text-center">Mobile No.</th>
                                <th class="text-center">ID No.</th>
                                <th class="text-center">Assignee</th>
                                <th class="text-center">Position</th>
                                <th class="text-center">Allowance</th>
                                <th class="text-center">Plan Fee</th>
                                <th class="text-center">Excess Usage</th>
                                <th class="text-center">VAT</th>
                                <th class="text-center">Loan Progress</th>
                                <th class="text-center">Loan Monthly Fee</th>
                                <th class="text-center">Excess Charges</th>
                                <th class="text-center">Charged to Asignee VAT</th>
                                <th class="text-center">Non Vattable</th>
                                <th class="text-center">Total Bill</th>
                                <th class="text-center">For Deduction</th>
                                <th class="text-center">Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </x-table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/datatable_overrides.js') }}"></script>
<script src="{{ asset('js/excess.js') }}"></script>
@endsection