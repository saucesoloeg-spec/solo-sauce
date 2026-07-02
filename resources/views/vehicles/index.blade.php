@extends('layouts.dashboard')

@section('CSS')
<style>
    .vehicle-action-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.25rem;
        background: transparent;
        border: none;
        outline: none;
        transition: background-color .2s ease, transform .15s ease;
        color: #495057 !important;
        border-radius: 0;
    }

    .vehicle-action-button:hover {
        background-color: rgba(0, 0, 0, 0.05);
        transform: translateY(-1px);
    }

    .vehicle-action-button svg {
        display: block;
    }

    .vehicle-action-button.assign {
        color: #0d6efd !important;
    }

    .vehicle-action-button.unassign,
    .vehicle-action-button.delete {
        color: #dc3545 !important;
    }

    .vehicle-action-button.view {
        color: #212529 !important;
    }

    .vehicle-action-button + .vehicle-action-button {
        margin-left: 0.35rem;
    }

    .table-responsive {
        overflow-x: auto;
    }
</style>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border shadow-xs mb-4">
            <div class="card-header border-bottom pb-0">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="font-weight-semibold text-lg mb-0">{{ __('vehicles.vehicles_list') }}</h6>
                        <p class="text-sm">{{ __('vehicles.vehicles_list_description') }}</p>
                    </div>
                    <a href="{{ route('vehicles.create') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center" style="gap: 5px; white-space: nowrap;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        {{ __('vehicles.create') }}
                    </a>
                </div>
            </div>
            <div class="card-body px-0 py-0">
                <div class="table-responsive p-3">
                    <table class="table align-items-center mb-0">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7">{{ __('vehicles.brand') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">{{ __('vehicles.model') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">{{ __('vehicles.color') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">{{ __('vehicles.license_plate') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">{{ __('vehicles.driver') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2" style="width: 220px;">{{ __('vehicles.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vehicles as $vehicle)
                                <tr>
                                    <td>
                                        <p class="text-sm text-dark font-weight-semibold mb-0">{{ $vehicle->brand }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm text-dark font-weight-semibold mb-0">{{ $vehicle->model }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm text-dark font-weight-semibold mb-0">{{ $vehicle->color }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm text-dark font-weight-semibold mb-0">{{ $vehicle->license_plate }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm text-dark font-weight-semibold mb-0">{{ $vehicle->driver->name ?? __('vehicles.unassigned') }}</p>
                                    </td>
                                    <td>
                                        <a href="{{ route('vehicles.show', ['id' => $vehicle->id]) }}" class="vehicle-action-button view" data-bs-toggle="tooltip" data-bs-title="{{ __('vehicles.view_vehicle') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>
                                        @if(empty($vehicle->driver_id))
                                            <button type="button" class="vehicle-action-button assign assign-driver-button" data-vehicle-id="{{ $vehicle->id }}" data-bs-toggle="tooltip" data-bs-title="{{ __('vehicles.assign_driver') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M12 5v14"></path>
                                                    <path d="M5 12h14"></path>
                                                </svg>
                                            </button>
                                        @else
                                            <button type="button" class="vehicle-action-button unassign unassign-driver-button" data-vehicle-id="{{ $vehicle->id }}" data-bs-toggle="tooltip" data-bs-title="{{ __('vehicles.unassign_driver') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M6 6l12 12"></path>
                                                    <path d="M18 6l-12 12"></path>
                                                </svg>
                                            </button>
                                        @endif
                                        <button type="button" class="vehicle-action-button delete delete-vehicle-button" data-delete-url="{{ route('vehicles.delete', ['id' => $vehicle->id]) }}" data-bs-toggle="tooltip" data-bs-title="{{ __('vehicles.delete_vehicle') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">{{ __('vehicles.no_vehicles_found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="vehicle-delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<div class="modal fade" id="deleteVehicleModal" tabindex="-1" aria-labelledby="deleteVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteVehicleModalLabel">{{ __('vehicles.delete_vehicle') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('vehicles.delete_vehicle_confirmation') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('vehicles.cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-vehicle-button">{{ __('vehicles.confirm') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="assignDriverModal" tabindex="-1" aria-labelledby="assignDriverModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignDriverModalLabel">{{ __('vehicles.assign_driver') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assign-driver-form" method="POST" data-base-action="{{ route('vehicles.assign.driver', ['id' => 0]) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="driver_id" class="form-label">{{ __('vehicles.select_driver') }}</label>
                        <select name="driver_id" id="driver_id" class="form-select" required>
                            <option value="">{{ __('vehicles.select_driver') }}</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->name }} - {{ $driver->phone ?? $driver->email }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('vehicles.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('vehicles.confirm') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="unassignDriverModal" tabindex="-1" aria-labelledby="unassignDriverModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="unassignDriverModalLabel">{{ __('vehicles.unassign_driver') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="unassign-driver-form" method="POST" data-base-action="{{ route('vehicles.unassign.driver', ['id' => 0]) }}">
                @csrf
                <div class="modal-body">
                    <p>{{ __('vehicles.unassign_driver_confirmation') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('vehicles.cancel') }}</button>
                    <button type="submit" class="btn btn-warning">{{ __('vehicles.confirm') }}</button>
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
        const unassignButtons = document.querySelectorAll('.unassign-driver-button');
        const assignForm = document.getElementById('assign-driver-form');
        const unassignForm = document.getElementById('unassign-driver-form');
        const deleteForm = document.getElementById('vehicle-delete-form');
        const deleteVehicleModal = new bootstrap.Modal(document.getElementById('deleteVehicleModal'));
        const confirmDeleteVehicleButton = document.getElementById('confirm-delete-vehicle-button');
        const assignModal = new bootstrap.Modal(document.getElementById('assignDriverModal'));
        const unassignModal = new bootstrap.Modal(document.getElementById('unassignDriverModal'));

        assignButtons.forEach(button => {
            button.addEventListener('click', function () {
                const vehicleId = this.dataset.vehicleId;
                const baseAction = assignForm.dataset.baseAction;
                assignForm.action = baseAction.replace('/0', `/${vehicleId}`);
                assignModal.show();
            });
        });

        unassignButtons.forEach(button => {
            button.addEventListener('click', function () {
                const vehicleId = this.dataset.vehicleId;
                const baseAction = unassignForm.dataset.baseAction;
                unassignForm.action = baseAction.replace('/0', `/${vehicleId}`);
                unassignModal.show();
            });
        });

        document.querySelectorAll('.delete-vehicle-button').forEach(button => {
            button.addEventListener('click', function () {
                deleteForm.action = this.dataset.deleteUrl;
                deleteVehicleModal.show();
            });
        });

        confirmDeleteVehicleButton.addEventListener('click', function () {
            deleteVehicleModal.hide();
            deleteForm.submit();
        });
    });
</script>
@stop
