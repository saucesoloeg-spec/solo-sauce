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
                        <h6 class="font-weight-semibold text-lg mb-0">{{ __('visits.add_schedule') }}</h6>
                        <p class="text-sm mb-0">{{ __('visits.sales_schedules_description') }}</p>
                    </div>
                    <a href="{{ route('schedules.get') }}" class="btn btn-back mt-3 mt-sm-0">{{ __('visits.back_to_schedules') }}</a>
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

                <form action="{{ route('schedules.store') }}" method="POST">
                    @csrf

                    <div class="form-section">
                        <h6 class="font-weight-semibold mb-3">{{ __('visits.basic_information') }}</h6>
                        
                        <div class="row-fields">
                            <div class="form-group">
                                <label for="customer_id" class="form-label">{{ __('visits.customer') }}</label>
                                <select class="form-control @error('customer_id') is-invalid @enderror" id="customers" name="customer_id" required>
                                    <option value=""> {{ __('visits.select_customer') }} </option>

                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="sales_id" class="form-label">{{ __('visits.salesman') }}</label>
                                <select class="form-control @error('sales_id') is-invalid @enderror" id="salesmen" name="sales_id" disabled required>
                                    <option value=""> {{ __('visits.select_salesman') }} </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="visit_date" class="form-label">
                                    {{ __('visits.visit_at') }} <span style="color: red;">*</span>
                                </label>

                                <input 
                                    type="date" 
                                    min="{{ date('Y-m-d') }}"
                                    class="form-control @error('visit_date') is-invalid @enderror" 
                                    id="visit_date" 
                                    name="visit_date" 
                                    value="{{ old('visit_date') }}" 
                                    required
                                >

                                @error('visit_date')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <label for="address" class="form-label">{{ __('visits.visitation_notes') }}</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="btn-group-action " style="justify-content: flex-end;">
                        <a href="{{ route('schedules.get') }}" class="btn btn-back">{{ __('visits.cancel') }}</a>
                        <button type="submit" class="btn-create">{{ __('visits.create') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('JavaScript')
<script>
document.addEventListener('DOMContentLoaded', () => {

    const customers = document.getElementById('customers');
    const salesman  = document.getElementById('salesmen');

    const resetSelect = (el, placeholder) => {
        el.innerHTML = `<option value="">${placeholder}</option>`;
        el.disabled  = true;
    };

    const populateSelect = (el, data) => {
        data.forEach(item => {
            const option       = document.createElement('option');
            option.value       = item.id;
            option.textContent = item.name;
            el.appendChild(option);
        });
        el.disabled = false;
    };

    // Customers → Salesmen
    customers.addEventListener('change', async () => {
        const customerId = customers.value;

        resetSelect(salesman, 'Select Representative');

        if (!customerId) return;

        const res  = await fetch(`/api/sales/all?customer_id=${customerId}`);
        const data = await res.json();

        populateSelect(salesman, data);
    });

});
</script>
@stop
