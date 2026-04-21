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
                        <h6 class="font-weight-semibold text-lg mb-0">Send Message</h6>
                        <p class="text-sm">Here you can Send a new Message</p>
                    </div>
                </div>
            </div>

            <div class="card-body px-0 py-0">
                <div class="border-bottom py-3 px-3">
                    <form action="{{ route('messages.store') }}" method="POST" id="create-messages-form">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input 
                                type="text" 
                                name="title" 
                                class="form-control" 
                                id="title" 
                                value="{{ old('title') }}" 
                                placeholder="Enter Notification Title" 
                                required>
                            @error('title')
                                <small id="title-error" class="error-text">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="Message" class="form-label">Message</label>
                            <input 
                                type="text" 
                                name="message" 
                                class="form-control" 
                                id="message" 
                                value="{{ old('message') }}" 
                                placeholder="Enter Notification body" 
                                required>
                            @error('message')
                                <small id="message-error" class="error-text">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Send To</label>
                            <select 
                                name="type" 
                                class="form-control" 
                                id="type" 
                                required>
                                <option value="" {{ old('type') === null ? 'selected' : '' }}>Select Type</option>
                                <option value="companies" {{ old('type') === 'companies' ? 'selected' : '' }}>Companies</option>
                                <option value="users" {{ old('type') === 'users' ? 'selected' : '' }}>Users</option>
                                <option value="admin" {{ old('type') === 'admin' ? 'selected' : '' }}>All</option>
                            </select>
                            @error('type')
                                <small id="type-error" class="error-text">{{ $message }}</small>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
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
        let isValid   = true;

        const title   = document.getElementById('title').value;
        const message = document.getElementById('message').value;
        const type    = document.getElementById('type').value;

        // Validate Name
        if (!title) {
            showError('title', 'Title is required.');
            isValid = false;
        } else {
            hideError('title');
        }

        // Validate Email
        if (!message) {
            showError('message', 'Please enter a Message.');
            isValid = false;
        } else {
            hideError('message');
        }

        // Validate Type
        if (!role) {
            showError('type', 'Please Choose a type to send to.');
            isValid = false;
        } else {
            hideError('type');
        }

        return isValid;
    };

    document.getElementById('create-message-form').addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault(); // Prevent form submission
        }
    });

    // Real-time validation
    document.getElementById('title').addEventListener('input', () => hideError('title'));
    document.getElementById('message').addEventListener('input', () => hideError('message'));
    document.getElementById('type').addEventListener('change', () => hideError('type'));
</script>
@stop
