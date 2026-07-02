<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Http\Services\VehicleService;
use App\Models\Driver;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public $vehicle_service;

    public function __construct(VehicleService $vehicle_service)
    {
        $this->vehicle_service = $vehicle_service;
    }

    public function index()
    {
        $response = $this->vehicle_service->getAll();
        $drivers = Driver::select('id', 'name', 'phone', 'email')->get();
        $vehicles = collect($response['response_data']);

        return view('vehicles.index', [
            'vehicles' => $vehicles,
            'drivers'  => $drivers,
        ]);
    }

    public function create()
    {
        $drivers = Driver::select('id', 'name', 'phone', 'email')->get();

        return view('vehicles.create', ['drivers' => $drivers]);
    }

    public function store(StoreVehicleRequest $request)
    {
        $response = $this->vehicle_service->createVehicle($request->validated());

        if ($response['response_code'] === 201) {
            return redirect()->route('vehicles.get')->with('success', __('vehicles.created_success'));
        }

        return redirect()->back()->withInput()->with('error', __('vehicles.create_failed'));
    }

    public function show($id)
    {
        $response = $this->vehicle_service->getById($id);

        if ($response['response_code'] !== 200) {
            return redirect()->route('vehicles.get')->with('error', __('vehicles.not_found'));
        }

        $drivers = Driver::select('id', 'name', 'phone', 'email')->get();

        return view('vehicles.show', [
            'vehicle' => $response['response_data'],
            'drivers' => $drivers,
        ]);
    }

    public function update(UpdateVehicleRequest $request, $id)
    {
        $response = $this->vehicle_service->updateVehicle($id, $request->validated());

        if ($response['response_code'] === 200) {
            return redirect()->route('vehicles.show', ['id' => $id])->with('success', __('vehicles.updated_success'));
        }

        return redirect()->back()->withInput()->with('error', __('vehicles.update_failed'));
    }

    public function destroy($id)
    {
        $response = $this->vehicle_service->deleteVehicle($id);

        if ($response['response_code'] === 200) {
            return redirect()->route('vehicles.get')->with('success', __('vehicles.deleted_success'));
        }

        return redirect()->route('vehicles.get')->with('error', __('vehicles.delete_failed'));
    }

    public function assignDriver(Request $request, $id)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
        ]);

        $response = $this->vehicle_service->assignDriver($id, $request->input('driver_id'));

        if ($response['response_code'] === 200) {
            return redirect()->route('vehicles.get')->with('success', __('vehicles.driver_assigned_success'));
        }

        return redirect()->route('vehicles.get')->with('error', __('vehicles.driver_assignment_failed'));
    }

    public function unassignDriver($id)
    {
        $response = $this->vehicle_service->unassignDriver($id);

        if ($response['response_code'] === 200) {
            return redirect()->route('vehicles.get')->with('success', __('vehicles.driver_unassigned_success'));
        }

        return redirect()->route('vehicles.get')->with('error', __('vehicles.driver_unassignment_failed'));
    }
}
