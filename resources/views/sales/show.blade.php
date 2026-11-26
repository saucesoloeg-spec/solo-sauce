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
    .sales-activity-card {
        margin-top: 24px;
    }
    [data-sales-activity-results] {
        position: relative;
    }
    [data-sales-activity-results][aria-busy="true"]::before {
        content: '';
        position: absolute;
        inset: 0;
        z-index: 2;
        background: rgba(255, 255, 255, 0.7);
    }
    .sales-activity-loading {
        display: none;
        position: absolute;
        inset: 0;
        z-index: 3;
        align-items: center;
        justify-content: center;
    }
    [data-sales-activity-results][aria-busy="true"] .sales-activity-loading {
        display: flex;
    }
    .sales-activity-spinner {
        width: 2rem;
        height: 2rem;
        border: 0.25rem solid #dee2e6;
        border-top-color: #6c757d;
        border-radius: 50%;
        animation: sales-activity-spin 0.75s linear infinite;
    }
    @keyframes sales-activity-spin {
        to {
            transform: rotate(360deg);
        }
    }
    .sales-filter-form {
        display: flex;
        flex-wrap: nowrap;
        gap: 8px;
    }
    .sales-filter-form .form-select {
        flex: 0 0 160px;
        min-width: 0;
    }
    .sales-custom-range {
        display: flex;
        flex: 1 0 100%;
        flex-wrap: wrap;
        align-items: end;
        gap: 8px;
    }
    .sales-custom-range .form-group {
        margin-bottom: 0;
    }
    .sales-custom-range .form-control {
        min-width: 150px;
    }
    .sales-activity-card .pagination .page-link {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    .sales-activity-card .pagination .page-item.active .page-link {
        background-color: #6c757d;
        border-color: #6c757d;
        color: #fff;
    }
    @media (max-width: 767.98px) {
        .sales-filter-form {
            width: 100%;
        }
        .sales-filter-form .form-select {
            flex: 1 1 0;
            width: 0;
        }
    }
</style>
@stop

@section('content')
<div class="row">
        <div id="page-data"
            data-company-name="{{ $sales->name }}"
            data-selected-city-ids='@json(array_map('intval', old('allowed_city_ids', $selectedCityIds)))'
            data-cities='@json($cities)'
            style="display: none;"></div>
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
                                <label for="country" class="form-label">{{ __('sales.country') }}</label>
                                <select id="country" name="country_odoo_id" class="form-select">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country['id'] }}" {{ old('country_odoo_id', $selectedCountryId) == $country['id'] ? 'selected' : '' }}>
                                            {{ $country['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('country_odoo_id')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="state" class="form-label">{{ __('sales.state') }}</label>
                                <select id="state" name="state_odoo_id" class="form-select" {{ empty($states) ? 'disabled' : '' }}>
                                    <option value="">Select State</option>
                                    @foreach($states as $stateOption)
                                        <option value="{{ $stateOption['id'] }}" {{ old('state_odoo_id', $selectedStateId) == $stateOption['id'] ? 'selected' : '' }}>
                                            {{ $stateOption['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('state_odoo_id')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="storage_location_odoo_id" class="form-label">Storage Location <span style="color: red;">*</span></label>
                                <select id="storage_location_odoo_id" name="storage_location_odoo_id" class="form-select" required>
                                    <option value="">Select Storage Location</option>
                                    @php
                                        $currentLocationId = (int) old('storage_location_odoo_id', $sales->storage_location_odoo_id);
                                    @endphp
                                    @foreach($storageLocations as $location)
                                        @php
                                            $isCurrentLocation = (int) $location['id'] === $currentLocationId;
                                            $isTakenByOther = !empty($location['is_assigned']) && (int) $location['id'] !== (int) $sales->storage_location_odoo_id;
                                        @endphp
                                        <option value="{{ $location['id'] }}" {{ $isCurrentLocation ? 'selected' : '' }} {{ $isTakenByOther ? 'disabled' : '' }}>{{ $location['name'] ?? $location['id'] }}{{ !empty($location['warehouse_name']) ? ' - ' . $location['warehouse_name'] : '' }}{{ $isTakenByOther ? ' (assigned)' : '' }}</option>
                                    @endforeach
                                </select>
                                @error('storage_location_odoo_id')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="cityDropdownToggle" class="form-label">{{ __('sales.city') }}</label>
                                <div class="dropdown">
                                    <button type="button" id="cityDropdownToggle" class="form-select text-start d-flex justify-content-between align-items-center" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" {{ empty($cities) ? 'disabled' : '' }}>
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
    <div class="col-12">
        <div class="card border shadow-xs sales-activity-card">
            <div class="card-header border-bottom pb-3">
                <h6 class="font-weight-semibold text-lg mb-0">{{ __('sales.sales_activity') }}</h6>
                <p class="text-sm mb-0">{{ __('sales.sales_activity_description') }}</p>
            </div>
            <div class="card-body p-4">
                <section id="sales-new-deals-section" class="mb-5" data-sales-activity-section data-sales-activity-page-param="new_deals_page">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                        <div>
                            <h6 class="font-weight-semibold mb-1">{{ __('sales.new_deals') }}</h6>
                            <p class="text-sm text-muted mb-0">{{ __('sales.first_orders_description') }}</p>
                        </div>
                        <form class="sales-filter-form" method="GET" action="{{ route('sales.show', ['id' => $sales->id]) }}" data-sales-activity-filter>
                            <input type="hidden" name="sold_products_period" value="{{ $soldProductsPeriod }}">
                            <input type="hidden" name="sold_products_from" value="{{ $soldProductsFrom }}">
                            <input type="hidden" name="sold_products_to" value="{{ $soldProductsTo }}">
                            <input type="hidden" name="sold_products_page" value="{{ request('sold_products_page', 1) }}">
                            <input type="hidden" name="reorders_period" value="{{ $reordersPeriod }}">
                            <input type="hidden" name="reorders_from" value="{{ $reordersFrom }}">
                            <input type="hidden" name="reorders_to" value="{{ $reordersTo }}">
                            <input type="hidden" name="reorders_status" value="{{ $reordersStatus }}">
                            <input type="hidden" name="reorders_page" value="{{ request('reorders_page', 1) }}">
                            <label class="sr-only" for="new-deals-status">{{ __('sales.status') }}</label>
                            <select id="new-deals-status" name="new_deals_status" class="form-select" onchange="this.form.requestSubmit()">
                                <option value="all" {{ $newDealsStatus === 'all' ? 'selected' : '' }}>{{ __('orders.all') }}</option>
                                <option value="pending" {{ $newDealsStatus === 'pending' ? 'selected' : '' }}>{{ __('orders.pending') }}</option>
                                <option value="confirmed" {{ $newDealsStatus === 'confirmed' ? 'selected' : '' }}>{{ __('orders.confirmed') }}</option>
                                <option value="shipped" {{ $newDealsStatus === 'shipped' ? 'selected' : '' }}>{{ __('orders.shipped') }}</option>
                                <option value="delivered" {{ $newDealsStatus === 'delivered' ? 'selected' : '' }}>{{ __('orders.delivered') }}</option>
                                <option value="canceled" {{ $newDealsStatus === 'canceled' ? 'selected' : '' }}>{{ __('orders.canceled') }}</option>
                                <option value="suspended" {{ $newDealsStatus === 'suspended' ? 'selected' : '' }}>{{ __('orders.suspended') }}</option>
                            </select>
                            <label class="sr-only" for="new-deals-period">{{ __('sales.date_range') }}</label>
                            <select id="new-deals-period" name="new_deals_period" class="form-select" data-sales-period>
                                <option value="today" {{ $newDealsPeriod === 'today' ? 'selected' : '' }}>{{ __('sales.today') }}</option>
                                <option value="last_week" {{ $newDealsPeriod === 'last_week' ? 'selected' : '' }}>{{ __('sales.last_week') }}</option>
                                <option value="this_month" {{ $newDealsPeriod === 'this_month' ? 'selected' : '' }}>{{ __('sales.this_month') }}</option>
                                <option value="custom" {{ $newDealsPeriod === 'custom' ? 'selected' : '' }}>{{ __('sales.custom_range') }}</option>
                            </select>
                            <div class="sales-custom-range {{ $newDealsPeriod === 'custom' ? '' : 'd-none' }}" data-custom-range>
                                <div class="form-group">
                                    <label for="new-deals-from">{{ __('sales.from_date') }}</label>
                                    <input id="new-deals-from" type="date" name="new_deals_from" class="form-control" value="{{ $newDealsFrom }}" {{ $newDealsPeriod === 'custom' ? 'required' : '' }}>
                                </div>
                                <div class="form-group">
                                    <label for="new-deals-to">{{ __('sales.to_date') }}</label>
                                    <input id="new-deals-to" type="date" name="new_deals_to" class="form-control" value="{{ $newDealsTo }}" {{ $newDealsPeriod === 'custom' ? 'required' : '' }}>
                                </div>
                                <button type="submit" class="btn btn-secondary mb-0">{{ __('sales.apply_date_range') }}</button>
                            </div>
                        </form>
                    </div>
                    <div data-sales-activity-results>
                        <div class="sales-activity-loading" role="status" aria-label="{{ __('sales.loading_activity') }}">
                            <span class="sales-activity-spinner" aria-hidden="true"></span>
                        </div>
                        <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7">{{ __('sales.order') }}</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7">{{ __('sales.customer') }}</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7">{{ __('sales.status') }}</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7 text-end">{{ __('sales.total_amount') }}</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7">{{ __('sales.date') }}</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($newDeals as $order)
                                    @php
                                        $status = [
                                            'pending' => ['label' => __('orders.pending'), 'class' => 'border-secondary text-secondary bg-secondary'],
                                            'confirmed' => ['label' => __('orders.confirmed'), 'class' => 'border-info text-info bg-info'],
                                            'shipped' => ['label' => __('orders.shipped'), 'class' => 'border-warning text-warning bg-warning'],
                                            'delivered' => ['label' => __('orders.delivered'), 'class' => 'border-success text-success bg-success'],
                                            'canceled' => ['label' => __('orders.canceled'), 'class' => 'border-danger text-danger bg-danger'],
                                            'cancelled' => ['label' => __('orders.canceled'), 'class' => 'border-danger text-danger bg-danger'],
                                            'suspended' => ['label' => __('orders.suspended'), 'class' => 'border-danger text-danger bg-danger'],
                                        ][$order->state] ?? null;
                                    @endphp
                                    <tr>
                                        <td class="text-sm font-weight-semibold">{{ $order->invoice_id ?? $order->code }}</td>
                                        <td class="text-sm">{{ $order->customer->name ?? $order->customer_name }}</td>
                                        <td class="align-middle text-center text-sm">
                                            @if($status)
                                                <span class="badge badge-sm border {{ $status['class'] }} reservation">{{ $status['label'] }}</span>
                                            @endif
                                        </td>
                                        <td class="text-sm text-end">{{ number_format((float) $order->amount_total, 2) }}</td>
                                        <td class="text-sm">{{ optional($order->created_at)->format('Y-m-d') }}</td>
                                        <td class="align-middle">
                                            <a href="{{ route('dashboard.orders.show', ['id' => $order->id]) }}" class="text-secondary font-weight-bold text-xs m-2 view cursor-pointer" data-bs-toggle="tooltip" data-bs-title="{{ __('sales.view_order') }}" aria-label="{{ __('sales.view_order') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-4 text-secondary">{{ __('sales.no_new_deals') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-3">
                        <strong>{{ __('sales.total_new_deals', ['total' => $newDeals->total()]) }}</strong>
                        <span>{{ __('sales.showing_rows', ['from' => $newDeals->firstItem() ?? 0, 'to' => $newDeals->lastItem() ?? 0, 'total' => $newDeals->total()]) }}</span>
                        {{ $newDeals->withQueryString()->links() }}
                    </div>
                    </div>
                </section>

                <section id="sales-reorders-section" class="mb-5" data-sales-activity-section data-sales-activity-page-param="reorders_page">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                        <div>
                            <h6 class="font-weight-semibold mb-1">{{ __('sales.reorders') }}</h6>
                            <p class="text-sm text-muted mb-0">{{ __('sales.reorders_description') }}</p>
                        </div>
                        <form class="sales-filter-form" method="GET" action="{{ route('sales.show', ['id' => $sales->id]) }}" data-sales-activity-filter>
                            <input type="hidden" name="new_deals_period" value="{{ $newDealsPeriod }}">
                            <input type="hidden" name="new_deals_from" value="{{ $newDealsFrom }}">
                            <input type="hidden" name="new_deals_to" value="{{ $newDealsTo }}">
                            <input type="hidden" name="new_deals_status" value="{{ $newDealsStatus }}">
                            <input type="hidden" name="new_deals_page" value="{{ request('new_deals_page', 1) }}">
                            <input type="hidden" name="sold_products_period" value="{{ $soldProductsPeriod }}">
                            <input type="hidden" name="sold_products_from" value="{{ $soldProductsFrom }}">
                            <input type="hidden" name="sold_products_to" value="{{ $soldProductsTo }}">
                            <input type="hidden" name="sold_products_page" value="{{ request('sold_products_page', 1) }}">
                            <label class="sr-only" for="reorders-status">{{ __('sales.status') }}</label>
                            <select id="reorders-status" name="reorders_status" class="form-select" onchange="this.form.requestSubmit()">
                                <option value="all" {{ $reordersStatus === 'all' ? 'selected' : '' }}>{{ __('orders.all') }}</option>
                                <option value="pending" {{ $reordersStatus === 'pending' ? 'selected' : '' }}>{{ __('orders.pending') }}</option>
                                <option value="confirmed" {{ $reordersStatus === 'confirmed' ? 'selected' : '' }}>{{ __('orders.confirmed') }}</option>
                                <option value="shipped" {{ $reordersStatus === 'shipped' ? 'selected' : '' }}>{{ __('orders.shipped') }}</option>
                                <option value="delivered" {{ $reordersStatus === 'delivered' ? 'selected' : '' }}>{{ __('orders.delivered') }}</option>
                                <option value="canceled" {{ $reordersStatus === 'canceled' ? 'selected' : '' }}>{{ __('orders.canceled') }}</option>
                                <option value="suspended" {{ $reordersStatus === 'suspended' ? 'selected' : '' }}>{{ __('orders.suspended') }}</option>
                            </select>
                            <label class="sr-only" for="reorders-period">{{ __('sales.date_range') }}</label>
                            <select id="reorders-period" name="reorders_period" class="form-select" data-sales-period>
                                <option value="today" {{ $reordersPeriod === 'today' ? 'selected' : '' }}>{{ __('sales.today') }}</option>
                                <option value="last_week" {{ $reordersPeriod === 'last_week' ? 'selected' : '' }}>{{ __('sales.last_week') }}</option>
                                <option value="this_month" {{ $reordersPeriod === 'this_month' ? 'selected' : '' }}>{{ __('sales.this_month') }}</option>
                                <option value="custom" {{ $reordersPeriod === 'custom' ? 'selected' : '' }}>{{ __('sales.custom_range') }}</option>
                            </select>
                            <div class="sales-custom-range {{ $reordersPeriod === 'custom' ? '' : 'd-none' }}" data-custom-range>
                                <div class="form-group">
                                    <label for="reorders-from">{{ __('sales.from_date') }}</label>
                                    <input id="reorders-from" type="date" name="reorders_from" class="form-control" value="{{ $reordersFrom }}" {{ $reordersPeriod === 'custom' ? 'required' : '' }}>
                                </div>
                                <div class="form-group">
                                    <label for="reorders-to">{{ __('sales.to_date') }}</label>
                                    <input id="reorders-to" type="date" name="reorders_to" class="form-control" value="{{ $reordersTo }}" {{ $reordersPeriod === 'custom' ? 'required' : '' }}>
                                </div>
                                <button type="submit" class="btn btn-secondary mb-0">{{ __('sales.apply_date_range') }}</button>
                            </div>
                            <input type="hidden" name="reorders_page" value="{{ request('reorders_page', 1) }}">
                        </form>
                    </div>
                    <div data-sales-activity-results>
                        <div class="sales-activity-loading" role="status" aria-label="{{ __('sales.loading_activity') }}">
                            <span class="sales-activity-spinner" aria-hidden="true"></span>
                        </div>
                        <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7">{{ __('sales.order') }}</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7">{{ __('sales.customer') }}</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7">{{ __('sales.status') }}</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7 text-end">{{ __('sales.total_amount') }}</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7">{{ __('sales.date') }}</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reorders as $order)
                                    @php
                                        $status = [
                                            'pending' => ['label' => __('orders.pending'), 'class' => 'border-secondary text-secondary bg-secondary'],
                                            'confirmed' => ['label' => __('orders.confirmed'), 'class' => 'border-info text-info bg-info'],
                                            'shipped' => ['label' => __('orders.shipped'), 'class' => 'border-warning text-warning bg-warning'],
                                            'delivered' => ['label' => __('orders.delivered'), 'class' => 'border-success text-success bg-success'],
                                            'canceled' => ['label' => __('orders.canceled'), 'class' => 'border-danger text-danger bg-danger'],
                                            'cancelled' => ['label' => __('orders.canceled'), 'class' => 'border-danger text-danger bg-danger'],
                                            'suspended' => ['label' => __('orders.suspended'), 'class' => 'border-danger text-danger bg-danger'],
                                        ][$order->state] ?? null;
                                    @endphp
                                    <tr>
                                        <td class="text-sm font-weight-semibold">{{ $order->invoice_id ?? $order->code }}</td>
                                        <td class="text-sm">{{ $order->customer->name ?? $order->customer_name }}</td>
                                        <td class="align-middle text-center text-sm">
                                            @if($status)
                                                <span class="badge badge-sm border {{ $status['class'] }} reservation">{{ $status['label'] }}</span>
                                            @endif
                                        </td>
                                        <td class="text-sm text-end">{{ number_format((float) $order->amount_total, 2) }}</td>
                                        <td class="text-sm">{{ optional($order->created_at)->format('Y-m-d') }}</td>
                                        <td class="align-middle">
                                            <a href="{{ route('dashboard.orders.show', ['id' => $order->id]) }}" class="text-secondary font-weight-bold text-xs m-2 view cursor-pointer" data-bs-toggle="tooltip" data-bs-title="{{ __('sales.view_order') }}" aria-label="{{ __('sales.view_order') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-4 text-secondary">{{ __('sales.no_reorders') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-3">
                        <strong>{{ __('sales.total_reorders', ['total' => $reorders->total()]) }}</strong>
                        <span>{{ __('sales.showing_rows', ['from' => $reorders->firstItem() ?? 0, 'to' => $reorders->lastItem() ?? 0, 'total' => $reorders->total()]) }}</span>
                        {{ $reorders->withQueryString()->links() }}
                    </div>
                    </div>
                </section>

                <section id="sales-sold-products-section" data-sales-activity-section data-sales-activity-page-param="sold_products_page">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                        <div>
                            <h6 class="font-weight-semibold mb-1">{{ __('sales.items_sold') }}</h6>
                            <p class="text-sm text-muted mb-0">{{ __('sales.sold_products_description') }}</p>
                        </div>
                        <form class="sales-filter-form" method="GET" action="{{ route('sales.show', ['id' => $sales->id]) }}" data-sales-activity-filter>
                            <input type="hidden" name="new_deals_period" value="{{ $newDealsPeriod }}">
                            <input type="hidden" name="new_deals_from" value="{{ $newDealsFrom }}">
                            <input type="hidden" name="new_deals_to" value="{{ $newDealsTo }}">
                            <input type="hidden" name="new_deals_status" value="{{ $newDealsStatus }}">
                            <input type="hidden" name="new_deals_page" value="{{ request('new_deals_page', 1) }}">
                            <input type="hidden" name="reorders_period" value="{{ $reordersPeriod }}">
                            <input type="hidden" name="reorders_from" value="{{ $reordersFrom }}">
                            <input type="hidden" name="reorders_to" value="{{ $reordersTo }}">
                            <input type="hidden" name="reorders_status" value="{{ $reordersStatus }}">
                            <input type="hidden" name="reorders_page" value="{{ request('reorders_page', 1) }}">
                            <label class="sr-only" for="sold-products-period">{{ __('sales.date_range') }}</label>
                            <select id="sold-products-period" name="sold_products_period" class="form-select" data-sales-period>
                                <option value="today" {{ $soldProductsPeriod === 'today' ? 'selected' : '' }}>{{ __('sales.today') }}</option>
                                <option value="last_week" {{ $soldProductsPeriod === 'last_week' ? 'selected' : '' }}>{{ __('sales.last_week') }}</option>
                                <option value="this_month" {{ $soldProductsPeriod === 'this_month' ? 'selected' : '' }}>{{ __('sales.this_month') }}</option>
                                <option value="custom" {{ $soldProductsPeriod === 'custom' ? 'selected' : '' }}>{{ __('sales.custom_range') }}</option>
                            </select>
                            <div class="sales-custom-range {{ $soldProductsPeriod === 'custom' ? '' : 'd-none' }}" data-custom-range>
                                <div class="form-group">
                                    <label for="sold-products-from">{{ __('sales.from_date') }}</label>
                                    <input id="sold-products-from" type="date" name="sold_products_from" class="form-control" value="{{ $soldProductsFrom }}" {{ $soldProductsPeriod === 'custom' ? 'required' : '' }}>
                                </div>
                                <div class="form-group">
                                    <label for="sold-products-to">{{ __('sales.to_date') }}</label>
                                    <input id="sold-products-to" type="date" name="sold_products_to" class="form-control" value="{{ $soldProductsTo }}" {{ $soldProductsPeriod === 'custom' ? 'required' : '' }}>
                                </div>
                                <button type="submit" class="btn btn-secondary mb-0">{{ __('sales.apply_date_range') }}</button>
                            </div>
                        </form>
                    </div>
                    <div data-sales-activity-results>
                        <div class="sales-activity-loading" role="status" aria-label="{{ __('sales.loading_activity') }}">
                            <span class="sales-activity-spinner" aria-hidden="true"></span>
                        </div>
                        <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7">{{ __('sales.product') }}</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7 text-end">{{ __('sales.quantity') }}</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7 text-end">{{ __('sales.unit_price') }}</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7 text-end">{{ __('sales.total_price') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($soldProducts as $product)
                                    <tr>
                                        <td class="text-sm font-weight-semibold">{{ $product['name'] }}</td>
                                        <td class="text-sm text-end">{{ $product['quantity'] }}</td>
                                        <td class="text-sm text-end">{{ number_format($product['unit_price'], 2) }}</td>
                                        <td class="text-sm text-end">{{ number_format($product['total_price'], 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-4 text-secondary">{{ __('sales.no_items_sold') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-3">
                        <strong>{{ __('sales.total_quantity_and_price', ['quantity' => number_format($totalSoldQuantity), 'price' => number_format($totalSoldPrice, 2)]) }}</strong>
                        <span>{{ __('sales.showing_rows', ['from' => $soldProducts->firstItem() ?? 0, 'to' => $soldProducts->lastItem() ?? 0, 'total' => $soldProducts->total()]) }}</span>
                        {{ $soldProducts->withQueryString()->links() }}
                    </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@stop

@section('JavaScript')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const pageData = document.getElementById('page-data');
    const country = document.getElementById('country');
    const state = document.getElementById('state');
    const cityDropdownToggle = document.getElementById('cityDropdownToggle');
    const cityDropdownLabel = document.getElementById('cityDropdownLabel');
    const cityDropdownOptions = document.getElementById('cityDropdownOptions');
    const selectedCitiesContainer = document.getElementById('selectedCitiesContainer');
    const selectedCitiesHiddenInputs = document.getElementById('selectedCitiesHiddenInputs');
    const initialSelectedCityIds = JSON.parse(pageData?.dataset?.selectedCityIds || '[]').map(Number);
    const cityData = JSON.parse(pageData?.dataset?.cities || '[]');
    let selectedCities = [];

    const initializeSalesPeriodControls = (root) => {
        root.querySelectorAll('[data-sales-period]').forEach(periodSelect => {
            const rangeFields = periodSelect.form.querySelector('[data-custom-range]');
            const dateInputs = rangeFields.querySelectorAll('input[type="date"]');
            const fromDate = rangeFields.querySelector('input[name$="_from"]');
            const toDate = rangeFields.querySelector('input[name$="_to"]');
            const updateRangeVisibility = (submitPreset) => {
                const isCustom = periodSelect.value === 'custom';
                rangeFields.classList.toggle('d-none', !isCustom);
                dateInputs.forEach(input => {
                    input.required = isCustom;
                });

                if (!isCustom && submitPreset) {
                    periodSelect.form.requestSubmit();
                }
            };

            const updateEndDateMinimum = () => {
                toDate.min = fromDate.value;
            };

            updateRangeVisibility(false);
            updateEndDateMinimum();
            fromDate.addEventListener('change', updateEndDateMinimum);
            periodSelect.addEventListener('change', () => updateRangeVisibility(true));
        });
    };

    initializeSalesPeriodControls(document);

    const salesActivityPageParams = ['new_deals_page', 'reorders_page', 'sold_products_page'];
    const synchronizeSalesActivityNavigation = (url) => {
        document.querySelectorAll('[data-sales-activity-filter] input[type="hidden"]').forEach(input => {
            const value = url.searchParams.get(input.name);
            if (value !== null || salesActivityPageParams.includes(input.name)) {
                input.value = value !== null ? value : '1';
            }
        });

        document.querySelectorAll('[data-sales-activity-section]').forEach(section => {
            const pageParam = section.dataset.salesActivityPageParam;
            section.querySelectorAll('.pagination a').forEach(link => {
                const linkUrl = new URL(link.href, window.location.href);
                const page = linkUrl.searchParams.get(pageParam);
                url.searchParams.forEach((value, key) => {
                    if (key !== pageParam) {
                        linkUrl.searchParams.set(key, value);
                    }
                });
                salesActivityPageParams.forEach(key => {
                    if (key !== pageParam) {
                        linkUrl.searchParams.set(key, url.searchParams.get(key) || '1');
                    }
                });
                if (page !== null) {
                    linkUrl.searchParams.set(pageParam, page);
                }
                link.href = linkUrl.href;
            });
        });
    };

    const activeActivityRequests = new Map();
    const updateSalesActivitySection = async (section, url) => {
        const previousRequest = activeActivityRequests.get(section.id);
        if (previousRequest) {
            previousRequest.abort();
        }

        const requestController = new AbortController();
        const results = section.querySelector('[data-sales-activity-results]');
        results.setAttribute('aria-busy', 'true');
        activeActivityRequests.set(section.id, requestController);

        try {
            const response = await fetch(url.href, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                signal: requestController.signal,
            });

            if (!response.ok || response.redirected) {
                throw new Error('Sales activity request failed.');
            }

            const pageMarkup = await response.text();
            const parsedPage = new DOMParser().parseFromString(pageMarkup, 'text/html');
            const updatedSection = parsedPage.getElementById(section.id);
            if (!updatedSection) {
                throw new Error('Sales activity response did not contain the requested table.');
            }

            if (activeActivityRequests.get(section.id) !== requestController) {
                return;
            }

            section.replaceWith(updatedSection);
            window.history.replaceState(null, '', url.pathname + url.search + url.hash);
            initializeSalesPeriodControls(updatedSection);
            synchronizeSalesActivityNavigation(url);
        } catch (error) {
            if (error.name === 'AbortError') {
                return;
            }

            console.error('Unable to update salesman activity in the background.', error);
            window.location.assign(url.href);
        } finally {
            if (activeActivityRequests.get(section.id) === requestController) {
                activeActivityRequests.delete(section.id);
                results.removeAttribute('aria-busy');
            }
        }
    };

    document.addEventListener('submit', event => {
        const form = event.target;
        if (!form.matches('[data-sales-activity-filter]')) {
            return;
        }

        event.preventDefault();
        const section = form.closest('[data-sales-activity-section]');
        const url = new URL(form.action, window.location.href);
        url.search = new URLSearchParams(new FormData(form)).toString();
        url.searchParams.set(section.dataset.salesActivityPageParam, '1');
        updateSalesActivitySection(section, url);
    });

    document.addEventListener('click', event => {
        const link = event.target.closest('[data-sales-activity-section] .pagination a');
        if (!link || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
            return;
        }

        const url = new URL(link.href, window.location.href);
        if (url.origin !== window.location.origin) {
            return;
        }

        event.preventDefault();
        updateSalesActivitySection(link.closest('[data-sales-activity-section]'), url);
    });

    const resetState = () => {
        state.innerHTML = '<option value="">Select State</option>';
        state.disabled = true;
    };

    const resetCityDropdown = () => {
        cityDropdownOptions.innerHTML = '';
        selectedCitiesContainer.innerHTML = '';
        selectedCitiesHiddenInputs.innerHTML = '';
        selectedCities = [];
        cityDropdownLabel.textContent = 'Select City';
        cityDropdownToggle.disabled = true;
    };

    const populateStateOptions = (data) => {
        state.innerHTML = '<option value="">Select State</option>';
        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.name;
            state.appendChild(option);
        });
        state.disabled = false;
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

    const populateCityOptions = (data, preselectedIds = []) => {
        cityDropdownOptions.innerHTML = '';
        selectedCities = [];

        if (!data.length) {
            const emptyState = document.createElement('div');
            emptyState.className = 'text-muted small';
            emptyState.textContent = 'No cities available for this state.';
            cityDropdownOptions.appendChild(emptyState);
            cityDropdownToggle.disabled = true;
            renderSelectedCities();
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
            const isSelected = preselectedIds.includes(Number(item.id));
            if (isSelected) {
                checkbox.checked = true;
                selectedCities.push({ id: Number(item.id), name: item.name });
            }
            checkbox.addEventListener('change', () => {
                if (checkbox.checked) {
                    if (!selectedCities.some(city => city.id === Number(item.id))) {
                        selectedCities.push({ id: Number(item.id), name: item.name });
                    }
                } else {
                    selectedCities = selectedCities.filter(city => city.id !== Number(item.id));
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
        renderSelectedCities();
    };

    const initializeCitySelection = () => {
        if (cityData && cityData.length) {
            populateCityOptions(cityData, initialSelectedCityIds);
        } else {
            resetCityDropdown();
        }
    };

    country.addEventListener('change', async () => {
        const countryId = country.value;
        resetState();
        resetCityDropdown();

        if (!countryId) return;

        const res = await fetch(`/api/odoo/states/${countryId}`);
        const data = await res.json();
        populateStateOptions(data['response_data']['states']);
    });

    state.addEventListener('change', async () => {
        const stateId = state.value;
        resetCityDropdown();

        if (!stateId) return;

        const res = await fetch(`/api/odoo/cities/${stateId}`);
        const data = await res.json();
        populateCityOptions(data['response_data']['cities']);
    });

    initializeCitySelection();
});
</script>
@stop
