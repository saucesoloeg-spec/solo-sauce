@extends('layouts.dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border shadow-xs mb-4">
            <div class="card-header border-bottom pb-0">
                <div class="d-sm-flex align-items-center">
                    <div>
                        <h6 class="font-weight-semibold text-lg mb-0">{{ __('orders.manage_orders') }}</h6>
                        <p class="text-sm">{{ __('orders.manage_orders_description') }}</p>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 py-0">
                <div class="table-responsive p-3">
                    <table class="table align-items-center mb-0">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7">{{ __('orders.order_code') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">{{ __('orders.customer_name') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">{{ __('orders.total_amount') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">{{ __('orders.delivery_date') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">{{ __('orders.order_status') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">{{ __('orders.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr data-order-id="{{ $order->id }}">
                                    <td>
                                        <p class="text-sm text-dark font-weight-semibold mb-0">{{ $order->code }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm text-dark font-weight-semibold mb-0">{{ $order->customer->name ?? $order->customer_name }}</p>
                                        <p class="text-sm text-secondary mb-0">{{ $order->customer->phone ?? $order->customer_phone }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm text-dark font-weight-semibold mb-0">{{ $order->amount_total }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm text-dark font-weight-semibold mb-0">{{ $order->delivery_date ?? $order->created_at->format('Y-m-d') }}</p>
                                    </td>
                                    <td>
                                        @php
                                            $status = strtolower($order->delivery_status ?? $order->state);
                                            $badgeClass = 'badge-secondary border-secondary text-secondary bg-secondary';

                                            if ($status === 'pending') {
                                                $badgeClass = 'badge-secondary border-secondary text-secondary bg-secondary';
                                            } elseif ($status === 'assigned') {
                                                $badgeClass = 'badge-warning border-warning text-warning bg-warning';
                                            } elseif ($status === 'confirmed') {
                                                $badgeClass = 'badge-warning border-warning text-warning bg-warning';
                                            } elseif ($status === 'delivered' || $status === 'completed') {
                                                $badgeClass = 'badge-success border-success text-success bg-success';
                                            } elseif ($status === 'canceled' || $status === 'cancelled') {
                                                $badgeClass = 'badge-danger border-danger text-danger bg-danger';
                                            } elseif ($status === 'suspended' || $status === 'on hold') {
                                                $badgeClass = 'badge-dark border-dark text-dark bg-dark';
                                            }
                                        @endphp
                                        <span class="badge badge-sm {{ $badgeClass }}">{{ ucfirst($order->delivery_status ?? $order->state) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('orders.show', ['id' => $order->id]) }}" class="text-secondary font-weight-bold text-xs m-2 view cursor-pointer" data-bs-toggle="tooltip" data-bs-title="{{ __('orders.view_order') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>
                                        @if (empty($order->driver_id))
                                            <button type="button" class="text-secondary font-weight-bold text-xs m-2 assign-driver-button cursor-pointer border-0 bg-transparent p-0" data-order-id="{{ $order->id }}" data-bs-toggle="tooltip" data-bs-title="{{ __('orders.assign_driver') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="1" y="3" width="15" height="13"></rect>
                                                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                                                </svg>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">{{ __('orders.no_unassigned_orders') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="assignDriverModal" tabindex="-1" aria-labelledby="assignDriverModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignDriverModalLabel">{{ __('orders.assign_driver') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assign-driver-form" method="POST" data-base-action="{{ route('manager.orders.assign.driver', ['id' => 0]) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="driver_id" class="form-label">{{ __('orders.select_driver') }}</label>
                        <select name="driver_id" id="driver_id" class="form-select" required>
                            <option value="">{{ __('orders.select_driver') }}</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->name }} - {{ $driver->phone ?? $driver->email }}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="order_id" id="modal_order_id" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('orders.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('orders.assign_driver') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('JavaScript')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const assignButtons = document.querySelectorAll('.assign-driver-button');
        const assignForm = document.getElementById('assign-driver-form');
        const modalOrderId = document.getElementById('modal_order_id');
        const assignModal = new bootstrap.Modal(document.getElementById('assignDriverModal'));

        assignButtons.forEach(button => {
            button.addEventListener('click', function () {
                const orderId = this.dataset.orderId;
                const baseAction = assignForm.dataset.baseAction;
                assignForm.action = baseAction.replace('/0/', `/${orderId}/`);
                modalOrderId.value = orderId;
                assignModal.show();
            });
        });
    });
</script>
@stop
