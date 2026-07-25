@extends('layouts.dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border shadow-xs mb-4">
            <div class="card-header border-bottom pb-0">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="font-weight-semibold text-lg mb-0">{{ __('deputies.deputies') }}</h6>
                        <p class="text-sm">{{ __('deputies.create_deputy_description') }}</p>
                    </div>
                    <button type="button" class="btn btn-outline-secondary btn-sm d-flex align-items-center" style="gap: 5px; white-space: nowrap;" data-bs-toggle="modal" data-bs-target="#createDeputyModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        {{ __('deputies.create_deputy') }}
                    </button>
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
                                <th class="text-secondary text-xs font-weight-semibold opacity-7">{{ __('deputies.deputy_name') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">{{ __('deputies.deputy_email') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">{{ __('deputies.deputy_phone') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">{{ __('deputies.assigned_vehicles') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">{{ __('deputies.deputy_notes') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">{{ __('deputies.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($deputies as $deputy)
                                @php
                                    $assignedVehicles = $deputy->vehicles ?? collect();
                                    $orderAssignmentHistory = $deputy->orderAssignments ?? collect();
                                    $vehicleOptions = $assignedVehicles->map(function ($vehicle) {
                                        return [
                                            'id' => $vehicle->id,
                                            'label' => $vehicle->brand . ' ' . $vehicle->model . ' (' . $vehicle->license_plate . ')',
                                        ];
                                    })->values();
                                    $vehicleViewData = $assignedVehicles->map(function ($vehicle) {
                                        return [
                                            'label' => $vehicle->brand . ' ' . $vehicle->model . ' (' . $vehicle->license_plate . ')',
                                            'driver' => $vehicle->driver->name ?? '-',
                                        ];
                                    })->values();
                                    $orderHistoryViewData = $orderAssignmentHistory->map(function ($assignment) {
                                        return [
                                            'order_code' => $assignment->order->code ?? '-',
                                            'customer_name' => $assignment->order->customer->name ?? $assignment->order->customer_name ?? '-',
                                            'assigned_at' => optional($assignment->assigned_at ?? $assignment->created_at)->format('Y-m-d H:i'),
                                        ];
                                    })->values();
                                @endphp
                                <tr>
                                    <td>
                                        <p class="text-sm text-dark font-weight-semibold mb-0">{{ $deputy->name }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm text-dark font-weight-semibold mb-0">{{ $deputy->email ?? '-' }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm text-dark font-weight-semibold mb-0">{{ $deputy->phone ?? '-' }}</p>
                                    </td>
                                    <td>
                                        @if($assignedVehicles->isNotEmpty())
                                            @foreach($assignedVehicles as $vehicle)
                                                <span class="badge badge-sm border border-info text-info bg-info me-1 mb-1">
                                                    {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->license_plate }})
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="text-sm text-secondary">{{ __('deputies.none') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="text-sm text-secondary mb-0">{{ $deputy->notes ? \Illuminate\Support\Str::limit($deputy->notes, 60) : '-' }}</p>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center" style="gap: 0.5rem;">
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-dark mb-0 view-deputy-btn"
                                                data-deputy-name="{{ $deputy->name }}"
                                                data-deputy-email="{{ $deputy->email ?? '-' }}"
                                                data-deputy-phone="{{ $deputy->phone ?? '-' }}"
                                                data-deputy-notes="{{ $deputy->notes ?? '-' }}"
                                                data-deputy-vehicles='@json($vehicleViewData)'
                                                data-deputy-order-history='@json($orderHistoryViewData)'
                                            >
                                                {{ __('deputies.view') }}
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary mb-0 assign-order-deputy-btn"
                                                data-deputy-id="{{ $deputy->id }}"
                                            >
                                                {{ __('deputies.assign_to_order') }}
                                            </button>
                                            @if($assignedVehicles->isNotEmpty())
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-outline-danger mb-0 vehicle-assignment-toggle-btn"
                                                    data-mode="unassign"
                                                    data-vehicles='@json($vehicleOptions)'
                                                >
                                                    {{ __('deputies.unassign_from_vehicle') }}
                                                </button>
                                            @else
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-outline-success mb-0 vehicle-assignment-toggle-btn"
                                                    data-mode="assign"
                                                    data-deputy-id="{{ $deputy->id }}"
                                                >
                                                    {{ __('deputies.assign_to_vehicle') }}
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">{{ __('deputies.no_deputies_found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createDeputyModal" tabindex="-1" aria-labelledby="createDeputyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDeputyModalLabel">{{ __('deputies.create_deputy') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('manager.deputies.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('deputies.deputy_name') }}</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('deputies.deputy_email') }}</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('deputies.deputy_phone') }}</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('deputies.deputy_notes') }}</label>
                        <textarea name="notes" rows="3" class="form-control">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('deputies.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('deputies.confirm') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="viewDeputyModal" tabindex="-1" aria-labelledby="viewDeputyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDeputyModalLabel">{{ __('deputies.view') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2"><strong>{{ __('deputies.deputy_name') }}:</strong> <span id="viewDeputyName">-</span></p>
                <p class="mb-2"><strong>{{ __('deputies.deputy_email') }}:</strong> <span id="viewDeputyEmail">-</span></p>
                <p class="mb-2"><strong>{{ __('deputies.deputy_phone') }}:</strong> <span id="viewDeputyPhone">-</span></p>
                <p class="mb-0"><strong>{{ __('deputies.deputy_notes') }}:</strong> <span id="viewDeputyNotes">-</span></p>

                <hr class="my-3">

                <div class="mb-3">
                    <h6 class="font-weight-semibold mb-2">{{ __('deputies.assigned_vehicles') }}</h6>
                    <div id="viewDeputyVehicles" class="small text-secondary"></div>
                </div>

                <div>
                    <h6 class="font-weight-semibold mb-2">{{ __('deputies.order_assignment_history') }}</h6>
                    <div class="table-responsive">
                        <table class="table table-sm align-items-center mb-0">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7">{{ __('deputies.order_code') }}</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">{{ __('deputies.customer') }}</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">{{ __('deputies.assigned_at') }}</th>
                                </tr>
                            </thead>
                            <tbody id="viewDeputyOrderHistoryBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="assignOrderDeputyModal" tabindex="-1" aria-labelledby="assignOrderDeputyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignOrderDeputyModalLabel">{{ __('deputies.assign_order_deputy') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assign-order-deputy-form" method="POST" data-base-action="{{ route('manager.orders.assign.deputy', ['id' => 0]) }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="deputy_id" id="order_deputy_id" value="">
                    <label for="order_id_select" class="form-label">{{ __('deputies.select_order') }}</label>
                    <select class="form-select" id="order_id_select" required>
                        <option value="">{{ __('deputies.select_order') }}</option>
                        @foreach($orders as $order)
                            <option value="{{ $order->id }}">{{ $order->code }} - {{ $order->customer->name ?? $order->customer_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('deputies.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('deputies.confirm') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="assignVehicleDeputyModal" tabindex="-1" aria-labelledby="assignVehicleDeputyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignVehicleDeputyModalLabel">{{ __('deputies.assign_vehicle_deputy') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assign-vehicle-deputy-form" method="POST" data-base-action="{{ route('manager.vehicles.assign.deputy', ['id' => 0]) }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="deputy_id" id="vehicle_deputy_id" value="">
                    <label for="vehicle_id_select" class="form-label">{{ __('deputies.select_vehicle') }}</label>
                    <select class="form-select" id="vehicle_id_select" required>
                        <option value="">{{ __('deputies.select_vehicle') }}</option>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}">{{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->license_plate }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('deputies.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('deputies.confirm') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="unassignVehicleDeputyModal" tabindex="-1" aria-labelledby="unassignVehicleDeputyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="unassignVehicleDeputyModalLabel">{{ __('deputies.unassign_from_vehicle') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="unassign-vehicle-deputy-form" method="POST" data-base-action="{{ route('manager.vehicles.unassign.deputy', ['id' => 0]) }}">
                @csrf
                <div class="modal-body">
                    <label for="unassign_vehicle_id_select" class="form-label">{{ __('deputies.select_vehicle') }}</label>
                    <select class="form-select" id="unassign_vehicle_id_select" required>
                        <option value="">{{ __('deputies.select_vehicle') }}</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('deputies.cancel') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('deputies.confirm') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('JavaScript')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const viewButtons = document.querySelectorAll('.view-deputy-btn');
        const assignOrderButtons = document.querySelectorAll('.assign-order-deputy-btn');
        const vehicleToggleButtons = document.querySelectorAll('.vehicle-assignment-toggle-btn');
        const assignOrderForm = document.getElementById('assign-order-deputy-form');
        const assignVehicleForm = document.getElementById('assign-vehicle-deputy-form');
        const unassignVehicleForm = document.getElementById('unassign-vehicle-deputy-form');
        const orderSelect = document.getElementById('order_id_select');
        const vehicleSelect = document.getElementById('vehicle_id_select');
        const unassignVehicleSelect = document.getElementById('unassign_vehicle_id_select');
        const viewDeputyVehicles = document.getElementById('viewDeputyVehicles');
        const viewDeputyOrderHistoryBody = document.getElementById('viewDeputyOrderHistoryBody');
        const orderDeputyId = document.getElementById('order_deputy_id');
        const vehicleDeputyId = document.getElementById('vehicle_deputy_id');
        const orderModal = new bootstrap.Modal(document.getElementById('assignOrderDeputyModal'));
        const vehicleModal = new bootstrap.Modal(document.getElementById('assignVehicleDeputyModal'));
        const unassignVehicleModal = new bootstrap.Modal(document.getElementById('unassignVehicleDeputyModal'));
        const viewDeputyModal = new bootstrap.Modal(document.getElementById('viewDeputyModal'));
        const selectVehicleText = "{{ __('deputies.select_vehicle') }}";
        const noneText = "{{ __('deputies.none') }}";

        viewButtons.forEach(button => {
            button.addEventListener('click', function () {
                document.getElementById('viewDeputyName').textContent = this.dataset.deputyName || '-';
                document.getElementById('viewDeputyEmail').textContent = this.dataset.deputyEmail || '-';
                document.getElementById('viewDeputyPhone').textContent = this.dataset.deputyPhone || '-';
                document.getElementById('viewDeputyNotes').textContent = this.dataset.deputyNotes || '-';

                const vehicles = JSON.parse(this.dataset.deputyVehicles || '[]');
                viewDeputyVehicles.innerHTML = vehicles.length
                    ? vehicles.map((vehicle) => `<div>• ${vehicle.label} <span class="text-muted">(${vehicle.driver})</span></div>`).join('')
                    : `<span>${noneText}</span>`;

                const orderHistory = JSON.parse(this.dataset.deputyOrderHistory || '[]');
                viewDeputyOrderHistoryBody.innerHTML = orderHistory.length
                    ? orderHistory.map((item) => `
                        <tr>
                            <td><span class="text-sm text-dark font-weight-semibold mb-0">${item.order_code}</span></td>
                            <td><span class="text-sm text-dark font-weight-semibold mb-0">${item.customer_name}</span></td>
                            <td><span class="text-sm text-secondary mb-0">${item.assigned_at}</span></td>
                        </tr>
                    `).join('')
                    : `<tr><td colspan="3" class="text-center py-3">{{ __('deputies.no_order_history_found') }}</td></tr>`;

                viewDeputyModal.show();
            });
        });

        assignOrderButtons.forEach(button => {
            button.addEventListener('click', function () {
                orderDeputyId.value = this.dataset.deputyId;
                orderSelect.value = '';
                orderModal.show();
            });
        });

        vehicleToggleButtons.forEach(button => {
            button.addEventListener('click', function () {
                if (this.dataset.mode === 'assign') {
                    vehicleDeputyId.value = this.dataset.deputyId;
                    vehicleSelect.value = '';
                    vehicleModal.show();
                    return;
                }

                const vehicles = JSON.parse(this.dataset.vehicles || '[]');
                unassignVehicleSelect.innerHTML = '';

                const placeholderOption = document.createElement('option');
                placeholderOption.value = '';
                placeholderOption.textContent = selectVehicleText;
                unassignVehicleSelect.appendChild(placeholderOption);

                vehicles.forEach(vehicle => {
                    const option = document.createElement('option');
                    option.value = vehicle.id;
                    option.textContent = vehicle.label;
                    unassignVehicleSelect.appendChild(option);
                });

                unassignVehicleModal.show();
            });
        });

        assignOrderForm.addEventListener('submit', function (event) {
            const orderId = orderSelect.value;

            if (!orderId) {
                event.preventDefault();
                return;
            }

            const baseAction = assignOrderForm.dataset.baseAction;
            assignOrderForm.action = baseAction.replace('/0', `/${orderId}`);
        });

        assignVehicleForm.addEventListener('submit', function (event) {
            const vehicleId = vehicleSelect.value;

            if (!vehicleId) {
                event.preventDefault();
                return;
            }

            const baseAction = assignVehicleForm.dataset.baseAction;
            assignVehicleForm.action = baseAction.replace('/0', `/${vehicleId}`);
        });

        if (unassignVehicleForm) {
            unassignVehicleForm.addEventListener('submit', function (event) {
                const vehicleId = unassignVehicleSelect.value;

                if (!vehicleId) {
                    event.preventDefault();
                    return;
                }

                const baseAction = unassignVehicleForm.dataset.baseAction;
                unassignVehicleForm.action = baseAction.replace('/0', `/${vehicleId}`);
            });
        }
    });
</script>
@stop
