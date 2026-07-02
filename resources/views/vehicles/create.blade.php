@extends('layouts.dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border shadow-xs mb-4">
            <div class="card-header border-bottom pb-3">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="font-weight-semibold text-lg mb-0">{{ __('vehicles.create_vehicle') }}</h6>
                        <p class="text-sm mb-0">{{ __('vehicles.create_vehicle_description') }}</p>
                    </div>
                    <a href="{{ route('vehicles.get') }}" class="btn btn-back mt-3 mt-sm-0">{{ __('vehicles.back_to_vehicles') }}</a>
                </div>
            </div>

            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('vehicles.store') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="brand">{{ __('vehicles.brand') }}</label>
                            <input type="text" id="brand" name="brand" class="form-control" value="{{ old('brand') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="model">{{ __('vehicles.model') }}</label>
                            <input type="text" id="model" name="model" class="form-control" value="{{ old('model') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="color">{{ __('vehicles.color') }}</label>
                            <input type="text" id="color" name="color" class="form-control" value="{{ old('color') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="license_plate">{{ __('vehicles.license_plate') }}</label>
                            <input type="text" id="license_plate" name="license_plate" class="form-control" value="{{ old('license_plate') }}" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="driver_id">{{ __('vehicles.assign_driver') }}</label>
                            <select id="driver_id" name="driver_id" class="form-select">
                                <option value="">{{ __('vehicles.select_driver') }}</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>{{ $driver->name }} - {{ $driver->phone ?? $driver->email }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end" style="gap: 10px;">
                        <a href="{{ route('vehicles.get') }}" class="btn btn-back">{{ __('vehicles.cancel') }}</a>
                        <button type="submit" class="btn btn-primary">{{ __('vehicles.create') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
