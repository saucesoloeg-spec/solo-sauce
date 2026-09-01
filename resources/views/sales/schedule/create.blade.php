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
    .searchable-select-wrap {
        position: relative;
    }
    .custom-select-trigger {
        width: 100%;
        text-align: left;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 10px 12px;
        color: #333;
        font-size: 14px;
    }
    .custom-select-trigger:after {
        content: '▾';
        font-size: 16px;
        color: #666;
    }
    .custom-select-menu {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 6px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        z-index: 20;
        display: none;
        overflow: hidden;
        touch-action: pan-y;
    }
    .custom-select-menu.open {
        display: block;
    }
    .custom-select-search {
        width: 100%;
        border: 0;
        border-bottom: 1px solid #eef0f2;
        padding: 10px 12px;
        outline: none;
    }
    .custom-select-options {
        max-height: 220px;
        overflow-y: auto;
    }
    .custom-select-option {
        padding: 10px 12px;
        cursor: pointer;
        border-bottom: 1px solid #f5f5f5;
        color: #333;
    }
    .custom-select-option:hover,
    .custom-select-option.selected {
        background: #f3f8ff;
    }
    .custom-select-option.hidden {
        display: none;
    }
    .custom-select-option.empty {
        color: #6c757d;
        cursor: default;
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
                                <div class="searchable-select-wrap custom-select-wrapper" data-field="customer">
                                    <button type="button" class="custom-select-trigger" data-placeholder="{{ __('visits.select_customer') }}">
                                        {{ __('visits.select_customer') }}
                                    </button>
                                    <div class="custom-select-menu">
                                        <input type="text" class="custom-select-search" placeholder="Search customer..." aria-label="Search customer">
                                        <div class="custom-select-options">
                                            @foreach($customers as $customer)
                                                <div class="custom-select-option" data-value="{{ $customer->id }}" data-label="{{ $customer->name }}" {{ old('customer_id') == $customer->id ? 'data-selected="selected"' : '' }}>
                                                    {{ $customer->name }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <select class="d-none @error('customer_id') is-invalid @enderror" id="customers" name="customer_id" required>
                                        <option value=""> {{ __('visits.select_customer') }} </option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="sales_id" class="form-label">{{ __('visits.salesman') }}</label>
                                <div class="searchable-select-wrap custom-select-wrapper" data-field="salesman">
                                    <button type="button" class="custom-select-trigger" data-placeholder="{{ __('visits.select_salesman') }}" disabled>
                                        {{ __('visits.select_salesman') }}
                                    </button>
                                    <div class="custom-select-menu">
                                        <input type="text" class="custom-select-search" placeholder="Search salesman..." aria-label="Search salesman" disabled>
                                        <div class="custom-select-options"></div>
                                    </div>
                                    <select class="d-none @error('sales_id') is-invalid @enderror" id="salesmen" name="sales_id" required>
                                        <option value=""> {{ __('visits.select_salesman') }} </option>
                                    </select>
                                </div>
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

    const setupCustomSelect = (wrapper) => {
        const trigger = wrapper.querySelector('.custom-select-trigger');
        const menu = wrapper.querySelector('.custom-select-menu');
        const searchInput = wrapper.querySelector('.custom-select-search');
        const optionsContainer = wrapper.querySelector('.custom-select-options');
        const nativeSelect = wrapper.querySelector('select');

        const getSelectedLabel = () => {
            const selectedOption = nativeSelect.options[nativeSelect.selectedIndex];
            return selectedOption && selectedOption.value ? selectedOption.textContent.trim() : trigger.dataset.placeholder;
        };

        const syncTrigger = () => {
            trigger.textContent = getSelectedLabel();
            trigger.setAttribute('data-value', nativeSelect.value || '');
        };

        const filterOptions = () => {
            const query = (searchInput.value || '').trim().toLowerCase();
            const items = optionsContainer.querySelectorAll('.custom-select-option');

            items.forEach(item => {
                const label = (item.dataset.label || item.textContent || '').toLowerCase();
                const match = !query || label.includes(query);
                item.classList.toggle('hidden', !match);
            });
        };

        const closeMenu = () => {
            menu.classList.remove('open');
        };

        trigger.addEventListener('click', () => {
            if (trigger.disabled) return;
            const isOpen = menu.classList.contains('open');
            closeMenu();
            if (!isOpen) {
                menu.classList.add('open');
                searchInput.focus();
            }
        });

        searchInput.addEventListener('input', filterOptions);

        optionsContainer.addEventListener('wheel', (event) => {
            const maxScroll = optionsContainer.scrollHeight - optionsContainer.clientHeight;
            if (maxScroll <= 0) return;

            event.preventDefault();
            optionsContainer.scrollTop += event.deltaY;
        }, { passive: false });

        optionsContainer.addEventListener('click', (event) => {
            const item = event.target.closest('.custom-select-option');
            if (!item) return;

            nativeSelect.value = item.dataset.value;
            nativeSelect.dispatchEvent(new Event('change'));
            syncTrigger();
            closeMenu();
        });

        document.addEventListener('click', (event) => {
            if (!wrapper.contains(event.target)) {
                closeMenu();
            }
        });

        nativeSelect.addEventListener('change', syncTrigger);
        syncTrigger();
    };

    const customSelects = document.querySelectorAll('.custom-select-wrapper');
    customSelects.forEach(setupCustomSelect);

    const customers = document.getElementById('customers');
    const salesman = document.getElementById('salesmen');
    const salesmanTrigger = document.querySelector('[data-field="salesman"] .custom-select-trigger');
    const salesmanSearch = document.querySelector('[data-field="salesman"] .custom-select-search');
    const salesmanOptions = document.querySelector('[data-field="salesman"] .custom-select-options');

    const setSalesmanOptions = (items) => {
        salesman.innerHTML = '<option value="">Select Representative</option>';
        salesmanOptions.innerHTML = '<div class="custom-select-option empty">Select Representative</div>';

        if (!items || items.length === 0) {
            salesmanTrigger.disabled = false;
            salesmanSearch.disabled = false;
            salesmanTrigger.textContent = 'No Representatives Available';
            return;
        }

        items.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.name;
            salesman.appendChild(option);

            const menuOption = document.createElement('div');
            menuOption.className = 'custom-select-option';
            menuOption.dataset.value = item.id;
            menuOption.dataset.label = item.name;
            menuOption.textContent = item.name;
            salesmanOptions.appendChild(menuOption);
        });

        salesmanTrigger.disabled = false;
        salesmanSearch.disabled = false;
        salesmanTrigger.textContent = 'Select Representative';

        const newSelected = salesman.options[salesman.selectedIndex];
        if (newSelected && newSelected.value) {
            salesmanTrigger.textContent = newSelected.textContent.trim();
        }
    };

    const loadSalesmen = async () => {
        salesmanTrigger.disabled = true;
        salesmanSearch.disabled = true;
        salesmanTrigger.textContent = 'Loading...';

        try {
            const res = await fetch('/api/sales/all');
            const data = await res.json().catch(() => []);
            const salesmenList = Array.isArray(data) ? data : (data?.response_data ?? []);
            setSalesmenOptions(salesmenList);
        } catch (error) {
            setSalesmanOptions([]);
        }
    };

    const setSalesmenOptions = (items) => {
        salesman.innerHTML = '<option value="">Select Representative</option>';
        salesmanOptions.innerHTML = '';

        if (!items || items.length === 0) {
            salesman.innerHTML = '<option value="">No Representatives Available</option>';
            salesmanTrigger.disabled = false;
            salesmanSearch.disabled = false;
            salesmanTrigger.textContent = 'No Representatives Available';
            return;
        }

        items.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.name;
            salesman.appendChild(option);

            const itemEl = document.createElement('div');
            itemEl.className = 'custom-select-option';
            itemEl.dataset.value = item.id;
            itemEl.dataset.label = item.name;
            itemEl.textContent = item.name;
            salesmanOptions.appendChild(itemEl);
        });

        salesmanTrigger.disabled = false;
        salesmanSearch.disabled = false;
        salesmanTrigger.textContent = 'Select Representative';
    };

    loadSalesmen();

    customers.addEventListener('change', () => {
        if (!customers.value) {
            salesman.value = '';
            return;
        }

        salesman.value = salesman.value || '';
    });
});
</script>
@stop
