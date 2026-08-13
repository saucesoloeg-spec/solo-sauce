<?php

namespace App\Http\Controllers;

use App\Domains\Odoo\Services\OdooAuthService;
use App\Http\Requests\StoreSalesRequest;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateSalesRequest;
use App\Http\Services\SalesService;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public $sales_service;
    public $odoo_service;

    public function __construct(SalesService $sales_service, OdooAuthService $odoo_service) 
    {
        $this->sales_service = $sales_service;
        $this->odoo_service  = $odoo_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = $this->sales_service->getAll();

        return view('sales.index', ['sales' => $response['response_data']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = $this->odoo_service->getCountries();
        
        return view('sales.create', ['countries' => $countries['data']['countries']]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSalesRequest $request)
    {
        $response = $this->sales_service->createSales($request->validated());

        if($response['response_code'] == 201) {
            return redirect()->route('sales.get')->with('success', 'Sales representative created successfully.');
        }

        return redirect()->back()->withInput()->with('error', $response['response_message']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = $this->sales_service->getById($id);

        if($response['response_code'] != 200) {
            return redirect()->route('sales.get')->with('error', $response['response_message']);
        }

        $sales = $response['response_data'];
        $countries = $this->odoo_service->getCountries();
        $countriesData = $countries['data']['countries'] ?? [];

        $selectedCountryId = (int) old('country_odoo_id', $sales->country_odoo_id);
        $selectedStateId = (int) old('state_odoo_id', $sales->state_odoo_id);
        $selectedCityIds = array_map('intval', old('allowed_city_ids', $sales->allowedCities()->pluck('city_odoo_id')->all()));

        $states = [];
        $cities = [];

        if ($selectedCountryId) {
            $statesResponse = $this->odoo_service->getStates($selectedCountryId);
            $states = $statesResponse['response_data']['states'] ?? [];
        }

        if ($selectedStateId) {
            $citiesResponse = $this->odoo_service->getCities($selectedStateId);
            $cities = $citiesResponse['response_data']['cities'] ?? [];
        }

        return view('sales.show', [
            'sales' => $sales,
            'countries' => $countriesData,
            'states' => $states,
            'cities' => $cities,
            'selectedCountryId' => $selectedCountryId,
            'selectedStateId' => $selectedStateId,
            'selectedCityIds' => $selectedCityIds,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSalesRequest $request, $id)
    {
        $response = $this->sales_service->updateSales($id, $request->validated());

        if($response['response_code'] == 200) {
            return redirect()->route('sales.show', ['id' => $id])->with('success', 'Sales updated successfully.');
        }

        return redirect()->back()->withInput()->with('error', $response['response_message']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = $this->sales_service->deleteSales($id);

        if($response['response_code'] == 200) {
            return response()->json([
                'success' => true,
                'message' => $response['response_message'],
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => $response['response_message'],
        ], $response['response_code']);
    }

    /*
     * Display a listing of the schedule.
     *
     * @return \Illuminate\Http\Response
     */
    public function schedule()
    {
        $response = $this->sales_service->getSchedule();
        $responseData = $response['response_data'] ?? ['schedules' => [], 'sales' => []];

        return view('sales.schedule.index', [
            'schedules' => $responseData['schedules'] ?? [],
            'sales' => $responseData['sales'] ?? []
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createSchedule()
    {
        $response = $this->sales_service->getScheduleInfo();
        $responseData = $response['response_data'] ?? ['sales' => [], 'customers' => []];

        return view('sales.schedule.create', [
            'sales'     => $responseData['sales'] ?? [],
            'customers' => $responseData['customers'] ?? []
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeSchedule(StoreScheduleRequest $request)
    {
        $response = $this->sales_service->createSchedule($request->validated());

        if($response['response_code'] == 200) {
            return redirect()->route('schedules.get')->with('success', 'Sales representative created successfully.');
        }

        return redirect()->back()->withInput()->with('error', $response['response_message']);
    }

    /**
     * Update visit date for a schedule.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateVisitDate(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:sales_customers,id',
            'visit_date'  => 'required|date',
            'sales_id'    => 'required|exists:sales,id'
        ]);

        $response = $this->sales_service->updateVisitDate($request->schedule_id, $request->visit_date, $request->sales_id);

        return response()->json($response, $response['response_code']);
    }

    /**
     * Delete a schedule.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteSchedule(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:sales_customers,id'
        ]);

        $response = $this->sales_service->deleteSchedule($request->schedule_id);

        return response()->json($response, $response['response_code']);
    }
}
