<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeputyRequest;
use App\Http\Services\DeputyService;

class DeputyController extends Controller
{
    private $deputy_service;

    public function __construct(DeputyService $deputy_service)
    {
        $this->deputy_service = $deputy_service;
    }

    public function index()
    {
        $deputiesResp = $this->deputy_service->getAll();

        return view('deputies.manager', [
            'deputies' => collect($deputiesResp['response_data'] ?? []),
            'orders' => $this->deputy_service->getOrdersForAssignment(),
            'vehicles' => $this->deputy_service->getVehiclesForAssignment(),
        ]);
    }

    public function store(StoreDeputyRequest $request)
    {
        $response = $this->deputy_service->createDeputy($request->validated());

        if ($response['response_code'] === 201) {
            return redirect()->route('manager.deputies.get')->with('success', __('deputies.created_success'));
        }

        return redirect()->back()->withInput()->with('error', __('deputies.create_failed'));
    }
}
