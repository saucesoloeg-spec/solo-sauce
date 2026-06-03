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

    /* 🔥 Status Timeline Styles */
    .status-timeline {
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-top: 40px;
    }

    /* 🔥 Full grey line */
    .timeline-line {
        position: absolute;
        top: 20px;
        left: 5%;
        right: 5%;
        height: 4px;
        background: #e0e0e0;
        z-index: 0;
    }

    /* Each step */
    .status-step {
        position: relative;
        text-align: center;
        flex: 1;
        z-index: 1;
    }

    /* Circle */
    .circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: #d6d6d6;
        margin: 0 auto;
        line-height: 45px;
        font-size: 18px;
        font-weight: bold;
        color: #6c7a89;
    }

    /* ✅ Completed */
    .status-step.completed .circle {
        background: #28a745;
        color: white;
    }

    /* Labels */
    .label {
        margin-top: 12px;
        font-weight: 600;
        color: #6c7a89;
    }

    .status-step.completed .label {
        color: #28a745;
    }

    /* Date */
    .date {
        font-size: 12px;
        color: #999;
        margin-top: 4px;
    }
</style>
@stop

@section('content')
<div class="row">
    <div id="page-data" data-company-name="{{ $order->code }}" style="display: none;"></div>
    <div class="col-12">
        <div class="card border shadow-xs mb-4">
            <div class="card-header border-bottom pb-3">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="font-weight-semibold text-lg mb-0">{{ __('orders.order_details') }}</h6>
                        <p class="text-sm mb-0">{{ __('orders.order') }}: {{$order->code}} {{ __('orders.description') }}</p>
                    </div>
                    <a href="{{ route('orders.get') }}" class="btn btn-back mt-3 mt-sm-0">{{ __('orders.back_to_orders') }}</a>
                </div>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('sales.store') }}" method="POST">
                    @csrf

                    <div class="form-section">
                        <div class="row-fields">
                            <div class="form-group">
                                <label for="code" class="form-label">{{ __('orders.order_code') }}</label>
                                <input type="text" class="form-control" id="code" name="code" value="{{ $order->code }}" disabled required>
                            </div>

                            <div class="form-group">
                                <label for="name" class="form-label">{{ __('orders.customer_name') }}</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $order->customer->name }}" disabled required>
                            </div>

                            <div class="form-group">
                                <label for="phone" class="form-label">{{ __('orders.phone') }}</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ $order->customer->phone }}" disabled required>
                            </div>

                            @if($order->sales)
                            <div class="form-group">
                                <label for="sales" class="form-label">{{ __('orders.representative') }}</label>
                                <input type="text" class="form-control" id="sales" name="sales_id" value="{{ $order->sales->name }}" disabled required>
                            </div>

                            <div class="form-group">
                                <label for="city" class="form-label">{{ __('orders.work_area') }}</label>
                                <input type="text" class="form-control" id="city" name="city_id" value="{{ $order->customer->city }}" disabled required>
                            </div>
                            @else
                            <span class=""> << {{ __('orders.system_order') }} >></span>
                            @endif
                            
                            <div class="form-group">
                                <label for="order_type" class="form-label">{{ __('orders.order_type') }}</label>
                                <input type="text" class="form-control" id="order_type" name="order_type" value="{{ $order->order_type }}" disabled required>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h6 class="font-weight-semibold text-md mb-3">{{ __('orders.order_products') }}</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('orders.product') }}</th>
                                        <th>{{ __('orders.price') }}</th>
                                        <th>{{ __('orders.quantity') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="order-products-body">
                                    <tr>
                                        <td colspan="4" class="text-center">Loading product information...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="status-timeline">
                            <!-- Background line -->
                            <div class="timeline-line"></div>

                            @if($order->statusHistory->isEmpty())
                                <div class="status-step completed">
                                    
                                    <div class="circle">
                                        ✓
                                    </div>

                                    <div class="label">
                                        {{ __('orders.pending') }}
                                    </div>

                                    <div class="date">
                                        {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y, h:i A') }}
                                    </div>

                                </div>
                            @else
                            @foreach($order->statusHistory as $index => $status)
                                <div class="status-step completed">
                                    
                                    <div class="circle">
                                        ✓
                                    </div>

                                    <div class="label">
                                        {{ strtoupper($status->status) }}
                                    </div>

                                    <div class="date">
                                        {{ \Carbon\Carbon::parse($status->created_at)->format('M d, Y, h:i A') }}
                                    </div>

                                </div>
                            @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- <div class="btn-group-action " style="justify-content: flex-end;">
                        <a href="{{ route('orders.get') }}" class="btn btn-back">Cancel</a>
                        <button type="submit" class="btn-create">Create</button>
                    </div> -->
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
    const city     = document.getElementById('city');
    const salesman = document.getElementById('salesman');

    const resetSelect = (el, placeholder) => {
        if (!el) return;
        el.innerHTML = `<option value="">${placeholder}</option>`;
        el.disabled  = true;
    };

    const populateSelect = (el, data) => {
        if (!el) return;
        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.name;
            el.appendChild(option);
        });
        el.disabled = false;
    };

    // Country → States
    if (country) {
        country.addEventListener('change', async () => {
            const countryId = country.value;

            resetSelect(state, 'Select State');
            resetSelect(city, 'Select City');
            // resetSelect(salesman, 'Select Salesman');

            if (!countryId) return;

            const res = await fetch(`/api/odoo/states/${countryId}`);
            const data = await res.json();

            populateSelect(state, data);
        });
    }

    // State → Cities
    if (state) {
        state.addEventListener('change', async () => {
            const stateId = state.value;

            resetSelect(city, 'Select City');
            // resetSelect(salesman, 'Select Salesman');

            if (!stateId) return;

            const res = await fetch(`/api/odoo/cities/${stateId}`);
            const data = await res.json();

            populateSelect(city, data);
        });
    }

    // // City → Salesmen
    // city.addEventListener('change', async () => {
    //     const cityId = city.value;

    //     resetSelect(salesman, 'Select Salesman');

    //     if (!cityId) return;

    //     const res = await fetch(`/api/salesmen?city_id=${cityId}`);
    //     const data = await res.json();

    //     populateSelect(salesman, data);
    // });

    const orderProducts = @json($order->products->map(function($product) {
        return [
            'product_id' => $product->product_id,
            'quantity'   => $product->quantity,
        ];
    }));

    const orderProductsBody = document.getElementById('order-products-body');

    const createProductRow = (index, productData) => {
        const imageUrl = productData.image_url || 'https://via.placeholder.com/80?text=No+Image';
        const productName = productData.name || 'Unknown Product';
        const productCode = productData.code ? ` (${productData.code})` : '';
        const listPrice = typeof productData.list_price !== 'undefined' ? productData.list_price : '-';

        return `
            <tr>
                <td>${index}</td>
                <td>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <img src="${imageUrl}" alt="${productName}" style="width: 72px; height: 72px; object-fit: cover; border-radius: 8px;" />
                        <div>
                            <div style="font-weight: 600;">${productName}${productCode}</div>
                            <div style="font-size: 13px; color: #6c757d;">ID: ${productData.id || 'N/A'}</div>
                        </div>
                    </div>
                </td>
                <td>${listPrice}</td>
                <td>${productData.quantity}</td>
            </tr>
        `;
    };

    const fetchProductData = async (productId) => {
        const response = await fetch(`/api/products/${productId}`, {
            credentials: 'include'
        });
        if (!response.ok) {
            throw new Error(`Product ${productId} fetch failed with status ${response.status}`);
        }

        const json = await response.json();
        if (!json || json.response_code !== 200 || !json.response_data) {
            throw new Error(`Product ${productId} returned invalid response`);
        }

        return json.response_data;
    };

    const loadOrderProducts = async () => {
        if (!orderProductsBody) {
            return;
        }

        if (!orderProducts || !orderProducts.length) {
            orderProductsBody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center">No products found for this order.</td>
                </tr>
            `;
            return;
        }

        const rows = await Promise.all(orderProducts.map(async (item, index) => {
            try {
                const productData = await fetchProductData(item.product_id);
                return createProductRow(index + 1, {
                    ...productData,
                    quantity: item.quantity,
                });
            } catch (error) {
                return `
                    <tr>
                        <td>${index + 1}</td>
                        <td colspan="3" class="text-danger">Unable to load product ${item.product_id}: ${error.message}</td>
                    </tr>
                `;
            }
        }));

        orderProductsBody.innerHTML = rows.join('');
    };

    loadOrderProducts();

});
</script>
@stop
