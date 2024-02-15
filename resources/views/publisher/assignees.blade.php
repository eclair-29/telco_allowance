@extends('layouts.app')

@section('content')
<div class="container" id="assignees">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header">{{ __('Manage Assignee Profiles') }}</div>

                <div class="card-body">
                    <x-alert />

                    <div class="pb-3 d-flex justify-content-end">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                                data-bs-target="#requests_popup">Requests</button>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#add_assignee_popup">
                                <!-- <i data-feather="plus"></i> -->
                                Add Assignee
                            </button>
                        </div>
                    </div>

                    <x-table :id="'assignees_table'">
                        <thead>
                            <tr>
                                <th class="text-center">Action</th>
                                <th class="text-center">No.</th>
                                <th class="text-center">Account No.</th>
                                <th class="text-center">Mobile No.</th>
                                <th class="text-center">ID No.</th>
                                <th class="text-center">Assignee</th>
                                <th class="text-center">Position</th>
                                <th class="text-center">Plan</th>
                                <th class="text-center">Plan Fee</th>
                                <th class="text-center">Allowance</th>
                                <th class="text-center">SIM Only</th>
                                <th class="text-center">Notes</th>
                                <th class="text-center">Last Updated Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignees as $index => $assignee)
                            <tr>
                                <td class="text-center">
                                    <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#edit_assignee_popup_{{ $assignee->id }}"
                                        class="link-success">Edit</a>
                                </td>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $assignee->account_no }}</td>
                                <td>{{ $assignee->phone_no }}</td>
                                <td>{{ $assignee->assignee_code }}</td>
                                <td>{{ $assignee->assignee }}</td>
                                <td>{{ $assignee->position->description ?? '' }}</td>
                                <td>{{ $assignee->plan->description }}</td>
                                <td>{{ $assignee->plan->subscription_fee }}</td>
                                <td>{{ $assignee->allowance }}</td>
                                <td>{{ $assignee->SIM_only == 1 ? 'Yes' : '' }}</td>
                                <td class="notes-cell">{{ $assignee->notes ?? '' }}</td>
                                <td>
                                    {{ $assignee->updated_at == ''
                                    ? $assignee->created_at
                                    : $assignee->updated_at
                                    }}
                                </td>
                            </tr>

                            <x-popup :id="'edit_assignee_popup_' . $assignee->id" :title="'Edit Assignee Info'"
                                :size="'lg'" :button="'Update'" :dnone="false"
                                :post="'update_assignee_fields' . '_' . $assignee->id">
                                @include('publisher.partials.update-assignee')
                            </x-popup>
                            @endforeach
                        </tbody>
                    </x-table>
                </div>
            </div>
        </div>
    </div>
</div>

<x-popup :id="'requests_popup'" :title="'Requests'" :size="'xl'" :dnone="true" :button="''" :post="''">
    <x-tickets-table :tickets="$tickets" />
</x-popup>

<x-popup :id="'add_assignee_popup'" :title="'Add Assignee'" :size="'lg'" :button="'Save'" :dnone="false"
    :post="'add_assignee_fields'">
    @include('publisher.partials.add-assignee')
</x-popup>

<script src="{{ asset('js/datatable_overrides.js') }}"></script>
<script src="{{ asset('js/assignee_actions.js') }}"></script>
@endsection