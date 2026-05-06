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
    <div id="page-data" data-company-name="{{ $sales->name }}" style="display: none;"></div>
    <div class="col-12">
        <div class="card border shadow-xs mb-4">
            <div class="card-header border-bottom pb-3">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="font-weight-semibold text-lg mb-0">{{ __('sales.basic_information') }}</h6>
                        <p class="text-sm mb-0">{{ $sales->name }}</p>
                    </div>
                    <a href="{{ route('sales.get') }}" class="btn btn-back mt-3 mt-sm-0">{{ __('sales.back_to_sales') }}</a>
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

                <form action="{{ route('sales.update', ['id' => $sales->id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-section">
                        <h6 class="font-weight-semibold mb-3">{{ __('sales.basic_information') }}</h6>
                        
                        <div class="row-fields">
                            <div class="form-group">
                                <label for="uuid" class="form-label">{{ __('sales.uuid') }}</label>
                                <input type="text" class="form-control" id="uuid" name="uuid" value="{{ $sales->uuid }}" disabled readonly>
                                <small class="text-muted">{{ __('sales.uuid_description') }}</small>
                            </div>

                            <div class="form-group">
                                <label for="name" class="form-label">{{ __('sales.name') }} <span style="color: red;">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $sales->name) }}" required>
                                @error('name')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">{{ __('sales.email') }} <span style="color: red;">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $sales->email) }}" required>
                                @error('email')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone" class="form-label">{{ __('sales.phone') }} <span style="color: red;">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $sales->phone) }}" required>
                                @error('phone')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="national_number" class="form-label">{{ __('sales.national_number') }}</label>
                                <input type="text" class="form-control @error('national_number') is-invalid @enderror" id="national_number" name="national_number" value="{{ old('national_number', $sales->national_number) }}">
                                @error('national_number')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="state" class="form-label">{{ __('sales.state') }}</label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state', $sales->state) }}">
                                @error('state')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="city" class="form-label">{{ __('sales.city') }}</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $sales->city) }}">
                                @error('city')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <label for="address" class="form-label">{{ __('sales.address') }}</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $sales->address) }}</textarea>
                        @error('address')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-section">
                        <h6 class="font-weight-semibold mb-3">{{ __('sales.password_setup') }}</h6>
                        <p class="text-sm text-muted mb-3">{{ __('sales.password_setup_description') }}</p>
                        
                        <div class="row-fields">
                            <div class="form-group">
                                <label for="password" class="form-label">{{ __('sales.password') }}</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="{{ __('sales.password_optional') }}">
                                @error('password')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">{{ __('sales.password_confirmation') }}</label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="{{ __('sales.password_confirmation_optional') }}">
                                @error('password_confirmation')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="btn-group-action" style="justify-content: flex-end;">
                        <a href="{{ route('sales.get') }}" class="btn btn-back">{{ __('sales.cancel') }}</a>
                        <button type="submit" class="btn-update">{{ __('sales.update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
