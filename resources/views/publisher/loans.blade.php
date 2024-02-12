@extends('layouts.app')

@section('content')
<div class="container" id="loans">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header">{{ __('Manage Loans') }}</div>

                <div class="card-body">
                    <x-alert />

                    <div class="pb-3 d-flex justify-content-end">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                                data-bs-target="#loan_requests_popup">Requests</button>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#add_loan_popup">
                                <!-- <i data-feather="plus"></i> -->
                                Add Loan
                            </button>
                        </div>
                    </div>

                    <x-table :id="'loans_table'">
                        <thead>
                            <tr>
                                <th class="text-center">Action</th>
                                <th class="text-center">No.</th>
                                <th class="text-center">Account No.</th>
                                <th class="text-center">Mobile No.</th>
                                <th class="text-center">ID No.</th>
                                <th class="text-center">Assignee</th>
                                <th class="text-center">Position</th>
                                <th class="text-center">Loan Progress</th>
                                <th class="text-center">Loan Subscription</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Notes</th>
                                <th class="text-center">Last Updated Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loans as $index => $loan)
                            <tr>
                                <td class="text-center">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#edit_loan_popup_{{ $loan->id }}"
                                        class="link-success">Edit</a>
                                </td>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $loan->assignee->account_no }}</td>
                                <td>{{ $loan->assignee->phone_no }}</td>
                                <td>{{ $loan->assignee->assignee_code }}</td>
                                <td>{{ $loan->assignee->assignee }}</td>
                                <td>{{ $loan->assignee->position->description ?? '' }}</td>
                                <td>{{ $loan->current_subscription_count }} / {{ $loan->total_subscription_count }}</td>
                                <td>{{ $loan->subscription_fee }}</td>
                                <td>{{ Str::title($loan->status->description) }}</td>
                                <td class="notes-cell">{{ $loan->notes ?? '' }}</td>
                                <td>
                                    {{ $loan->updated_at == ''
                                    ? $loan->created_at
                                    : $loan->updated_at
                                    }}
                                </td>
                            </tr>

                            <x-popup :id="'edit_loan_popup_' . $loan->id" :title="'Edit Loan Info'" :size="'lg'"
                                :button="'Update'" :dnone="false" :post="'update_loan_fields' . '_' . $loan->id">
                                @include('publisher.partials.update-loan')
                            </x-popup>
                            @endforeach
                        </tbody>
                    </x-table>
                </div>
            </div>
        </div>
    </div>
</div>

<x-popup :id="'add_loan_popup'" :title="'Add Loan'" :size="'lg'" :button="'Save'" :dnone="false"
    :post="'add_loan_fields'">
    @include('publisher.partials.add-loan')
</x-popup>

<script src="{{ asset('js/datatable_overrides.js') }}"></script>
<script src="{{ asset('js/loan_actions.js') }}"></script>
@endsection