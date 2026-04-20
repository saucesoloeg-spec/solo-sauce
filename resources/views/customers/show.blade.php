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
    .pagination .page-item.active .page-link {
        background-color: #e9ecef; /* light gray background */
        border-color: #dee2e6;
        color: #000; /* black text */
    }

    .pagination .page-link {
        color: #6c757d; /* gray numbers */
    }

    .pagination .page-link:hover {
        background-color: #f1f3f5;
        color: #000;
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
                        <h6 class="font-weight-semibold text-lg mb-0">Customer Details</h6>
                        <p class="text-sm mb-0">{{ $customer->name }}</p>
                    </div>
                    <a href="{{ route('customers.get') }}" class="btn btn-back mt-3 mt-sm-0">Back to Customers</a>
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

                <form action="{{ route('sales.update', ['id' => $customer->id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-section">
                        <h6 class="font-weight-semibold mb-3">Basic Information</h6>
                        
                        <div class="row-fields">
                            <div class="form-group">
                                <label for="name" class="form-label">Name <span style="color: red;">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $customer->name) }}" required>
                                @error('name')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">Email <span style="color: red;">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $customer->email) }}" required>
                                @error('email')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone" class="form-label">Phone <span style="color: red;">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" required>
                                @error('phone')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="zone" class="form-label">Zone</label>
                                <input type="text" class="form-control @error('zone') is-invalid @enderror" id="zone" name="zone" value="{{ old('zone', $customer->zone) }}">
                                @error('zone')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $customer->city) }}">
                                @error('city')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $customer->address) }}</textarea>
                        @error('address')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-section">
                        <h6 class="font-weight-semibold mb-3">Order History</h6>
                        @if($customer->orders->isEmpty())
                            <p>No orders found for this customer.</p>
                        @else
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Delivery Date</th>
                                        <th>Creation Date</th>
                                        <th>Total Amount</th>
                                        <th>Payment Method</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody style="text-align: center;">
                                    <!-- create a pagination if orders +10 -->

                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{ $order->code }}</td>
                                            <td>{{ $order->delivery_date }}</td>
                                            <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                            <td>${{ number_format($order->amount_total, 2) }}</td>
                                            <td>{{ ucfirst($order->payment_method) }}</td>
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
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4 d-flex justify-content-center">
                                {{ $orders->links() }}
                            </div>
                            @endif
                        </div>

                    <div class="btn-group-action" style="justify-content: flex-end;">
                        <a href="{{ route('sales.get') }}" class="btn btn-back">Cancel</a>
                        <button type="submit" class="btn-update">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
