@extends('layouts.dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border shadow-xs mb-4">
            <div class="card-header border-bottom pb-3">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="font-weight-semibold text-lg mb-0">{{ __('dashboard.inventory') }} {{ __('dashboard.today_orders') }}</h6>
                        <p class="text-sm mb-0">{{ __('dashboard.todays_delivery_workload') }}</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ route('inventory.index') }}" class="row g-3 align-items-end mb-4">
                    <div class="col-md-4">
                        <label for="from" class="form-label text-sm">From</label>
                        <input type="date" id="from" name="from" class="form-control" value="{{ $from }}">
                    </div>
                    <div class="col-md-4">
                        <label for="to" class="form-label text-sm">To</label>
                        <input type="date" id="to" name="to" class="form-control" value="{{ $to }}">
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary mb-0">Filter</button>
                        <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary mb-0">Reset</a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 text-center">{{ __('dashboard.vehicle') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 text-center">{{ __('dashboard.driver') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 text-center">{{ __('dashboard.license_plate') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 text-center">{{ __('dashboard.today_orders') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 text-center">{{ __('dashboard.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vehicles as $vehicle)
                                <tr class="text-center">
                                    <td>{{ $vehicle->brand }} {{ $vehicle->model }}</td>
                                    <td>{{ $vehicle->driver?->name ?? 'Unassigned' }}</td>
                                    <td>{{ $vehicle->license_plate }}</td>
                                    <td>{{ $vehicle->driver?->orders->count() ?? 0 }}</td>
                                    <td>
                                        <a href="{{ route('inventory.vehicles.show', ['vehicle' => $vehicle->id, 'from' => $from, 'to' => $to]) }}" class="btn btn-sm btn-outline-primary">{{ __('dashboard.view') }}</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">{{ __('dashboard.no_vehicles_found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
