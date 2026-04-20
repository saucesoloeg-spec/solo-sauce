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
    .btn-create {
        background: #1380ed; 
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        min-width: 120px;
        text-align: center;
        margin: 0;
        /*height: fit-content;8*/
    }
    .btn-create:hover {
        background: #0a55a0;
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
        /*height: fit-content;*/
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
    .error-message {
        color: #dc3545;
        font-size: 12px;
        margin-top: 5px;
    }
</style>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border shadow-xs mb-4">
            <div class="card-header border-bottom pb-3">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="font-weight-semibold text-lg mb-0">Create New Sales Representative</h6>
                        <p class="text-sm mb-0">Fill in the information below to create a new sales representative</p>
                    </div>
                    <a href="{{ route('sales.get') }}" class="btn btn-back mt-3 mt-sm-0">Back to Sales</a>
                </div>
            </div>

            <div class="card-body p-4">
                <!-- Error Messages -->
                @if($errors->any())
                    <div class="alert alert-error">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('sales.store') }}" method="POST">
                    @csrf

                    <div class="form-section">
                        <h6 class="font-weight-semibold mb-3">Basic Information</h6>
                        
                        <div class="row-fields">
                            <div class="form-group">
                                <label for="name" class="form-label">Name <span style="color: red;">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">Email <span style="color: red;">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone" class="form-label">Phone <span style="color: red;">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="national_number" class="form-label">National Number</label>
                                <input type="text" class="form-control @error('national_number') is-invalid @enderror" id="national_number" name="national_number" value="{{ old('national_number') }}">
                                @error('national_number')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="zone" class="form-label">Zone</label>
                                <input type="text" class="form-control @error('zone') is-invalid @enderror" id="zone" name="zone" value="{{ old('zone') }}">
                                @error('zone')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}">
                                @error('city')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-section">
                        <h6 class="font-weight-semibold mb-3">Password Setup</h6>
                        
                        <div class="row-fields">
                            <div class="form-group">
                                <label for="password" class="form-label">Password <span style="color: red;">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter password (min 8 characters)" required>
                                @error('password')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">Confirm Password <span style="color: red;">*</span></label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required>
                                @error('password_confirmation')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="btn-group-action " style="justify-content: flex-end;">
                        <a href="{{ route('sales.get') }}" class="btn btn-back">Cancel</a>
                        <button type="submit" class="btn-create">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
