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
                        <h6 class="font-weight-semibold text-lg mb-0">{{ __('sales.create_sales_representative') }}</h6>
                        <p class="text-sm mb-0">{{ __('sales.create_sales_representative_description') }}</p>
                    </div>
                    <a href="{{ route('sales.get') }}" class="btn btn-back mt-3 mt-sm-0">{{ __('sales.back_to_sales') }}</a>
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
                        <h6 class="font-weight-semibold mb-3">{{ __('sales.basic_information') }}</h6>
                        
                        <div class="row-fields">
                            <div class="form-group">
                                <label for="name" class="form-label">{{ __('sales.name') }} <span style="color: red;">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">{{ __('sales.email') }} <span style="color: red;">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone" class="form-label">{{ __('sales.phone') }} <span style="color: red;">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="national_number" class="form-label">{{ __('sales.national_number') }}</label>
                                <input type="text" class="form-control @error('national_number') is-invalid @enderror" id="national_number" name="national_number" value="{{ old('national_number') }}">
                                @error('national_number')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="country_odoo_id" class="form-label">{{ __('sales.country') }}</label>
                                <select id="country" name="country_odoo_id" class="form-select">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country['id'] }}">{{ $country['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('country')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="state_odoo_id" class="form-label">{{ __('sales.state') }}</label>
                                <select id="state" name="state_odoo_id" class="form-select" disabled>
                                    <option value="">Select State</option>
                                </select>

                                @error('state')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="cityDropdownToggle" class="form-label">{{ __('sales.city') }}</label>
                                <div class="dropdown">
                                    <button type="button" id="cityDropdownToggle" class="form-select text-start d-flex justify-content-between align-items-center" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" disabled>
                                        <span id="cityDropdownLabel">Select City</span>
                                        <span class="ms-2">▼</span>
                                    </button>
                                    <div class="dropdown-menu p-2" style="min-width: 260px; max-height: 280px; overflow-y: auto;">
                                        <div id="cityDropdownOptions" class="d-grid gap-2"></div>
                                    </div>
                                </div>
                                <div id="selectedCitiesContainer" class="d-flex flex-wrap gap-2 mt-2"></div>
                                <div id="selectedCitiesHiddenInputs"></div>
                                <small class="text-muted">Choose one or more cities for this salesman.</small>
                                @error('allowed_city_ids')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <label for="address" class="form-label">{{ __('sales.address') }}</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-section">
                        <h6 class="font-weight-semibold mb-3">{{ __('sales.password_setup') }}</h6>
                        <div class="row-fields">
                            <div class="form-group">
                                <label for="password" class="form-label">{{ __('sales.password') }} <span style="color: red;">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter password (min 8 characters)" required>
                                @error('password')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">{{ __('sales.password_confirmation') }} <span style="color: red;">*</span></label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required>
                                @error('password_confirmation')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="btn-group-action " style="justify-content: flex-end;">
                        <a href="{{ route('sales.get') }}" class="btn btn-back">{{ __('sales.cancel') }}</a>
                        <button type="submit" class="btn-create">{{ __('sales.create') }}</button>
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

    const country  = document.getElementById('country');
    const state    = document.getElementById('state');
    const cityDropdownToggle = document.getElementById('cityDropdownToggle');
    const cityDropdownLabel = document.getElementById('cityDropdownLabel');
    const cityDropdownOptions = document.getElementById('cityDropdownOptions');
    const selectedCitiesContainer = document.getElementById('selectedCitiesContainer');
    const selectedCitiesHiddenInputs = document.getElementById('selectedCitiesHiddenInputs');
    let selectedCities = [];

    const resetSelect = (el, placeholder) => {
        el.innerHTML = `<option value="">${placeholder}</option>`;
        el.disabled  = true;
    };

    const populateSelect = (el, data) => {
        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.name;
            el.appendChild(option);
        });
        el.disabled = false;
    };

    const resetCityDropdown = () => {
        cityDropdownOptions.innerHTML = '';
        selectedCitiesContainer.innerHTML = '';
        selectedCitiesHiddenInputs.innerHTML = '';
        selectedCities = [];
        cityDropdownLabel.textContent = 'Select City';
        cityDropdownToggle.disabled = true;
    };

    const renderSelectedCities = () => {
        selectedCitiesContainer.innerHTML = '';
        selectedCitiesHiddenInputs.innerHTML = '';

        if (selectedCities.length === 0) {
            cityDropdownLabel.textContent = 'Select City';
            return;
        }

        cityDropdownLabel.textContent = `${selectedCities.length} city${selectedCities.length > 1 ? 'ies' : 'y'} selected`;

        selectedCities.forEach(city => {
            const chip = document.createElement('span');
            chip.className = 'badge rounded-pill bg-primary-subtle text-primary-emphasis d-inline-flex align-items-center gap-2';

            const text = document.createElement('span');
            text.textContent = city.name;

            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'btn-close btn-close-sm';
            button.setAttribute('aria-label', `Remove ${city.name}`);
            button.addEventListener('click', () => {
                selectedCities = selectedCities.filter(item => item.id !== city.id);
                const checkbox = document.getElementById(`city-option-${city.id}`);
                if (checkbox) {
                    checkbox.checked = false;
                }
                renderSelectedCities();
            });

            chip.appendChild(text);
            chip.appendChild(button);
            selectedCitiesContainer.appendChild(chip);

            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'allowed_city_ids[]';
            hiddenInput.value = city.id;
            selectedCitiesHiddenInputs.appendChild(hiddenInput);
        });
    };

    const populateCityDropdown = (data) => {
        cityDropdownOptions.innerHTML = '';
        selectedCities = [];
        renderSelectedCities();

        if (!data.length) {
            const emptyState = document.createElement('div');
            emptyState.className = 'text-muted small';
            emptyState.textContent = 'No cities available for this state.';
            cityDropdownOptions.appendChild(emptyState);
            cityDropdownToggle.disabled = true;
            return;
        }

        data.forEach(item => {
            const wrapper = document.createElement('div');
            wrapper.className = 'form-check';

            const checkbox = document.createElement('input');
            checkbox.className = 'form-check-input';
            checkbox.type = 'checkbox';
            checkbox.value = item.id;
            checkbox.id = `city-option-${item.id}`;
            checkbox.addEventListener('change', () => {
                if (checkbox.checked) {
                    if (!selectedCities.some(city => city.id === item.id)) {
                        selectedCities.push({ id: item.id, name: item.name });
                    }
                } else {
                    selectedCities = selectedCities.filter(city => city.id !== item.id);
                }
                renderSelectedCities();
            });

            const label = document.createElement('label');
            label.className = 'form-check-label w-100';
            label.setAttribute('for', checkbox.id);
            label.textContent = item.name;

            wrapper.appendChild(checkbox);
            wrapper.appendChild(label);
            cityDropdownOptions.appendChild(wrapper);
        });

        cityDropdownToggle.disabled = false;
    };

    // Country → States
    country.addEventListener('change', async () => {
        const countryId = country.value;

        resetSelect(state, 'Select State');
        resetCityDropdown();

        if (!countryId) return;

        const res = await fetch(`/api/odoo/states/${countryId}`);
        const data = await res.json();

        populateSelect(state, data['response_data']['states']);
    });

    // State → Cities
    state.addEventListener('change', async () => {
        const stateId = state.value;

        resetCityDropdown();

        if (!stateId) return;

        const res = await fetch(`/api/odoo/cities/${stateId}`);
        const data = await res.json();

        populateCityDropdown(data['response_data']['cities']);
    });

});
</script>
@stop
