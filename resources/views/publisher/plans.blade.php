@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header">{{ __('Manage Plans') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <div class="pb-3 d-flex justify-content-end">
                        <button class="btn btn-outline-success">Add New Plan</button>
                    </div>

                    <x-table :id="'plans_table'">
                        <thead>
                            <th class="text-center">Action</th>
                            <th class="text-center">No.</th>
                            <th class="text-center">Plan</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Telco Provider</th>
                            <th class="text-center">Subscription Fee.</th>
                            <th class="text-center">Status</th>
                            <!-- <th>Last Updated At</th> -->
                        </thead>
                        <tbody>
                            @foreach($plans as $index => $plan)
                            <tr>
                                <td class="text-center">
                                    <a href="#" class="link-success">Edit</a>
                                </td>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $plan->description }}</td>
                                <td>{{ $plan->type }}</td>
                                <td>{{ $plan->provider }}</td>
                                <td>{{ $plan->subscription_fee }}</td>
                                <td>{{ Str::title($plan->status->description) }}</td>
                                <!--<td>
                                    {{ $plan->updated_at == ''
                                    ? $plan->created_at
                                    : $plan->updated_at
                                    }}
                                </td> -->
                            </tr>
                            @endforeach
                        </tbody>
                    </x-table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/datatable_overrides.js') }}"></script>
@endsection