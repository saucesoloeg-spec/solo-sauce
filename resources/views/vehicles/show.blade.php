@extends('layouts.dashboard')

@section('content')
<div id="page-data" data-page-name="{{ $vehicle->license_plate }}" style="display:none;"></div>
<div class="row">
    <div class="col-12">
        <div class="card border shadow-xs mb-4">
            <div class="card-header border-bottom pb-3">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="font-weight-semibold text-lg mb-0">{{ __('vehicles.vehicle_details') }}</h6>
                        <p class="text-sm mb-0">{{ __('vehicles.vehicle_details_description') }}</p>
                    </div>
                    <a href="{{ route('vehicles.get') }}" class="btn btn-back mt-3 mt-sm-0">{{ __('vehicles.back_to_vehicles') }}</a>
                </div>
            </div>

            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('vehicles.update', ['id' => $vehicle->id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="brand">{{ __('vehicles.brand') }}</label>
                            <input type="text" id="brand" name="brand" class="form-control" value="{{ old('brand', $vehicle->brand) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="model">{{ __('vehicles.model') }}</label>
                            <input type="text" id="model" name="model" class="form-control" value="{{ old('model', $vehicle->model) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="color">{{ __('vehicles.color') }}</label>
                            <input type="text" id="color" name="color" class="form-control" value="{{ old('color', $vehicle->color) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="license_plate">{{ __('vehicles.license_plate') }}</label>
                            <input type="text" id="license_plate" name="license_plate" class="form-control" value="{{ old('license_plate', $vehicle->license_plate) }}" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="driver_id">{{ __('vehicles.assign_driver') }}</label>
                            <select id="driver_id" name="driver_id" class="form-select">
                                <option value="">{{ __('vehicles.select_driver') }}</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}" {{ old('driver_id', $vehicle->driver_id) == $driver->id ? 'selected' : '' }}>{{ $driver->name }} - {{ $driver->phone ?? $driver->email }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <button type="submit" class="btn btn-primary">{{ __('vehicles.update') }}</button>
                        <form action="{{ route('vehicles.delete', ['id' => $vehicle->id]) }}" method="POST" class="m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">{{ __('vehicles.delete') }}</button>
                        </form>
                        @if($vehicle->driver_id)
                            <form action="{{ route('vehicles.unassign.driver', ['id' => $vehicle->id]) }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-warning">{{ __('vehicles.unassign_driver') }}</button>
                            </form>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
