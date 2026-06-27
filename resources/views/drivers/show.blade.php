@extends('layouts.dashboard')

@section('CSS')
<style>
    .form-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-label {
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
        color: #333;
    }
    .form-control {
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }
    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    .row-fields {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }
    .btn-group-action {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }
    .btn-update {
        background: #007bff;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        min-width: 120px;
        text-align: center;
        margin: 0;
        /* height: fit-content; */
    }
    .btn-update:hover {
        background: #0056b3;
    }
    .btn-back {
        background: #6c757d;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 120px;
        text-align: center;
        margin: 0;
        /* height: fit-content; */
    }
    .btn-back:hover {
        background: #5a6268;
        text-decoration: none;
    }
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
    }
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .password-fields {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 4px;
        background: white;
    }
    .error-message {
        color: #dc3545;
        font-size: 12px;
        margin-top: 5px;
    }
</style>
@stop

@section('content')
<div class="row">
    <div id="page-data" data-company-name="{{ $driver->name }}" style="display: none;"></div>
    <div class="col-12">
        <div class="card border shadow-xs mb-4">
            <div class="card-header border-bottom pb-3">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="font-weight-semibold text-lg mb-0">{{ __('drivers.basic_information') }}</h6>
                        <p class="text-sm mb-0">{{ $driver->name }}</p>
                    </div>
                    <a href="{{ route('drivers.get') }}" class="btn btn-back mt-3 mt-sm-0">{{ __('drivers.back_to_drivers') }}</a>
                </div>
            </div>

            <div class="card-body p-4">
                <!-- Success Message -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Error Message -->
                @if(session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Validation Errors -->
                @if($errors->any())
                    <div class="alert alert-error">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('drivers.update', ['id' => $driver->id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-section">
                        <h6 class="font-weight-semibold mb-3">{{ __('drivers.basic_information') }}</h6>
                        
                        <div class="row-fields">
                            <div class="form-group">
                                <label for="uuid" class="form-label">{{ __('drivers.uuid') }}</label>
                                <input type="text" class="form-control" id="uuid" name="uuid" value="{{ $driver->uuid }}" disabled readonly>
                                <small class="text-muted">{{ __('drivers.uuid_description') }}</small>
                            </div>

                            <div class="form-group">
                                <label for="name" class="form-label">{{ __('drivers.name') }} <span style="color: red;">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $driver->name) }}" required>
                                @error('name')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">{{ __('drivers.email') }} <span style="color: red;">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $driver->email) }}" required>
                                @error('email')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone" class="form-label">{{ __('drivers.phone') }} <span style="color: red;">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $driver->phone) }}" required>
                                @error('phone')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="national_number" class="form-label">{{ __('drivers.national_number') }}</label>
                                <input type="text" class="form-control @error('national_number') is-invalid @enderror" id="national_number" name="national_number" value="{{ old('national_number', $driver->national_number) }}">
                                @error('national_number')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <label for="address" class="form-label">{{ __('drivers.address') }}</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $driver->address) }}</textarea>
                        @error('address')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-section">
                        <h6 class="font-weight-semibold mb-3">{{ __('drivers.password_setup') }}</h6>
                        <p class="text-sm text-muted mb-3">{{ __('drivers.password_setup_description') }}</p>
                        
                        <div class="row-fields">
                            <div class="form-group">
                                <label for="password" class="form-label">{{ __('drivers.password') }}</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="{{ __('drivers.password_optional') }}">
                                @error('password')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">{{ __('drivers.password_confirmation') }}</label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="{{ __('drivers.password_confirmation_optional') }}">
                                @error('password_confirmation')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-section">
                        <h6 class="font-weight-semibold mb-3">{{ __('drivers.orders_list') }}</h6>
                        <p class="text-sm text-muted mb-3">{{ __('drivers.orders_list_description') }}</p>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('drivers.order_code') }}</th>
                                    <th>{{ __('drivers.order_total') }}</th>
                                    <th>{{ __('drivers.order_date') }}</th>
                                    <th>{{ __('drivers.order_status') }}</th>
                                </tr>
                            </thead>
                            <tbody style="text-align: center;">
                                @forelse($driver->orders as $order)
                                    <tr>
                                        <td>{{ $order->code }}</td>
                                        <td>{{ $order->amount_total }}</td>
                                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            @if($order->state == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($order->state == 'delivering')
                                                <span class="badge badge-primary">Delivering</span>
                                            @elseif($order->state == 'completed')
                                                <span class="badge badge-success">Completed</span>
                                            @elseif($order->state == 'cancelled')
                                                <span class="badge badge-danger">Cancelled</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst($order->state) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">{{ __('drivers.no_orders_found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                            
                    </div>

                    <div class="btn-group-action" style="justify-content: flex-end;">
                        <a href="{{ route('drivers.get') }}" class="btn btn-back">{{ __('drivers.cancel') }}</a>
                        <button type="submit" class="btn-update">{{ __('drivers.update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
