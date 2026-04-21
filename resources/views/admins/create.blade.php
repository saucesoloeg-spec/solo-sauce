@extends('layouts.dashboard')

@section('CSS')
<!-- Add this to your CSS -->
<style>
    .form-control {
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }
    .form-label {
        font-weight: 600;
    }
    .btn-primary {
        border-radius: 0.5rem;
    }
    .error-text {
        color: red;
        font-size: 0.875rem;
        margin-top: -0.5rem;
        margin-bottom: 1rem;
    }
</style>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border shadow-xs mb-4">
            <div class="card-header border-bottom pb-0">
                <div class="d-sm-flex align-items-center">
                    <div>
                        <h6 class="font-weight-semibold text-lg mb-0">Create Admin</h6>
                        <p class="text-sm">Here you can create a new Admin</p>
                    </div>
                </div>
            </div>

            <div class="card-body px-0 py-0">
                <div class="border-bottom py-3 px-3">
                    <form action="{{ route('admins.store') }}" method="POST" id="create-admin-form">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input 
                                type="text" 
                                name="name" 
                                class="form-control" 
                                id="name" 
                                value="{{ old('name') }}" 
                                placeholder="Enter admin name" 
                                required>
                            @error('name')
                                <small id="name-error" class="error-text">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input 
                                type="email" 
                                name="email" 
                                class="form-control" 
                                id="email" 
                                value="{{ old('email') }}" 
                                placeholder="Enter admin email" 
                                required>
                            @error('email')
                                <small id="email-error" class="error-text">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input 
                                type="password" 
                                name="password" 
                                class="form-control" 
                                id="password" 
                                placeholder="Enter password" 
                                required>
                            @error('password')
                                <small id="password-error" class="error-text">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                class="form-control" 
                                id="password_confirmation" 
                                placeholder="Confirm password" 
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select 
                                name="role" 
                                class="form-control" 
                                id="role" 
                                required>
                                <option value="" {{ old('role') === null ? 'selected' : '' }}>Select role</option>
                                <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role')
                                <small id="role-error" class="error-text">{{ $message }}</small>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Create Admin</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('JavaScript')
<script>
    const showError = (field, message) => {
        const errorElement = document.getElementById(`${field}-error`);
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        }
    };

    const hideError = (field) => {
        const errorElement = document.getElementById(`${field}-error`);
        if (errorElement) {
            errorElement.style.display = 'none';
        }
    };

    const validateForm = () => {
        let isValid = true;

        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;
        const role = document.getElementById('role').value;

        // Validate Name
        if (!name) {
            showError('name', 'Name is required.');
            isValid = false;
        } else {
            hideError('name');
        }

        // Validate Email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email || !emailRegex.test(email)) {
            showError('email', 'Please enter a valid email.');
            isValid = false;
        } else {
            hideError('email');
        }

        // Validate Password
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
        if (!password || !passwordRegex.test(password)) {
            showError('password', 'Password must be at least 8 characters, including uppercase, lowercase, and numbers.');
            isValid = false;
        } else {
            hideError('password');
        }

        // Validate Password Confirmation
        if (password !== passwordConfirmation) {
            showError('password-confirmation', 'Passwords do not match.');
            isValid = false;
        } else {
            hideError('password-confirmation');
        }

        // Validate Role
        if (!role) {
            showError('role', 'Please select a role.');
            isValid = false;
        } else {
            hideError('role');
        }

        return isValid;
    };

    document.getElementById('create-admin-form').addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault(); // Prevent form submission
        }
    });

    // Real-time validation
    document.getElementById('name').addEventListener('input', () => hideError('name'));
    document.getElementById('email').addEventListener('input', () => hideError('email'));
    document.getElementById('password').addEventListener('input', () => hideError('password'));
    document.getElementById('password_confirmation').addEventListener('input', () => hideError('password-confirmation'));
    document.getElementById('role').addEventListener('change', () => hideError('role'));
</script>
@stop
