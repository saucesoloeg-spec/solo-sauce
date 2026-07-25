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
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="table-responsive p-3">
                    <table class="table align-items-center mb-0">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7">{{ __('orders.order_code') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">{{ __('orders.customer_name') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">{{ __('orders.total_amount') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">{{ __('orders.delivery_date') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">{{ __('orders.order_status') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2 text-center">{{ __('orders.notes') }}</th>
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
                                            $stateStatus = strtolower((string) $order->state);
                                            $statusSource = in_array($stateStatus, ['canceled', 'cancelled'])
                                                ? $order->state
                                                : ($order->delivery_status ?? $order->state);
                                            $status = strtolower((string) $statusSource);
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

                                            $isCancelled = in_array($stateStatus, ['canceled', 'cancelled']);
                                        @endphp
                                        <span class="badge badge-sm {{ $badgeClass }}">{{ ucfirst((string) $statusSource) }}</span>
                                        @if (!empty($order->driver_id) && !empty($order->driver_order_rank))
                                            <p class="text-xs text-secondary mb-0 mt-1">{{ __('orders.driver_rank_short') }}: {{ $order->driver_order_rank }}</p>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $wrappedNotes = $order->notes
                                                ? wordwrap((string) $order->notes, 55, "\n", true)
                                                : '-';
                                        @endphp
                                        <p class="text-sm text-secondary mb-0 mx-auto" style="max-width: 260px; white-space: pre-line; word-break: break-word;">{!! e($wrappedNotes) !!}</p>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center" style="gap: 0.5rem;">
                                                <span class="d-inline-flex align-items-center justify-content-center" style="width: 28px;">
                                                    @if (!$isCancelled)
                                                        <a href="{{ route('orders.show', ['id' => $order->id]) }}" class="text-secondary font-weight-bold text-xs view cursor-pointer" data-bs-toggle="tooltip" data-bs-title="{{ __('orders.view_order') }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                                <circle cx="12" cy="12" r="3"></circle>
                                                            </svg>
                                                        </a>
                                                    @endif
                                                </span>

                                                <span class="d-inline-flex align-items-center justify-content-center" style="width: 28px;">
                                                    @if (!$isCancelled && empty($order->driver_id))
                                                        <button type="button" class="text-secondary font-weight-bold text-xs assign-driver-button cursor-pointer border-0 bg-transparent p-0" data-order-id="{{ $order->id }}" data-bs-toggle="tooltip" data-bs-title="{{ __('orders.assign_driver') }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <rect x="1" y="3" width="15" height="13"></rect>
                                                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                                                <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                                                <circle cx="18.5" cy="18.5" r="2.5"></circle>
                                                            </svg>
                                                        </button>
                                                    @endif
                                                </span>

                                                <span class="d-inline-flex align-items-center justify-content-center" style="width: 28px;">
                                                    @if ($isCancelled)
                                                        <form action="{{ route('manager.orders.reactivate', ['id' => $order->id]) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="text-success font-weight-bold text-xs cursor-pointer border-0 bg-transparent p-0" data-bs-toggle="tooltip" data-bs-title="{{ __('orders.reactivate_order') }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('manager.orders.cancel', ['id' => $order->id]) }}" method="POST" class="d-inline" data-confirm-message="{{ __('orders.confirm_cancel_order') }}" onsubmit="return confirm(this.dataset.confirmMessage)">
                                                            @csrf
                                                            <button type="submit" class="text-danger font-weight-bold text-xs cursor-pointer border-0 bg-transparent p-0" data-bs-toggle="tooltip" data-bs-title="{{ __('orders.cancel_order') }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                                    <path d="M19 6l-1 14H6L5 6"></path>
                                                                    <path d="M10 11v6"></path>
                                                                    <path d="M14 11v6"></path>
                                                                    <path d="M9 6V4h6v2"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </span>
                                            </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                        <td colspan="7" class="text-center py-4">{{ __('orders.no_unassigned_orders') }}</td>
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
                    <div class="mb-3">
                        <label for="driver_order_rank" class="form-label">{{ __('orders.driver_rank') }}</label>
                        <input type="number" min="1" step="1" name="driver_order_rank" id="driver_order_rank" class="form-control" placeholder="{{ __('orders.driver_rank_placeholder') }}">
                        <small class="text-muted">{{ __('orders.driver_rank_help') }}</small>
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
