<?php

namespace App\Domains\Odoo\Controllers;

use App\Domains\Odoo\Services\OdooAuthService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OdooController extends Controller
{
    public $odoo_service;

    public function __construct(OdooAuthService $odoo_service) 
    {
        $this->odoo_service = $odoo_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function countries()
    {
        $response = $this->odoo_service->getCountries();
        
        return response()->json([
            'response_code'    => 200,
            'response_message' => 'Countries fetched successfully',
            'response_data'    => $response['data']['countries']
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $country_id
     * @return \Illuminate\Http\Response
     */
    public function states($country_id)
    {
        $response = $this->odoo_service->getStates($country_id);
        
        return response()->json($response, $response['response_code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $city_id
     * @return \Illuminate\Http\Response
     */
    public function cities($state_id)
    {
        $response = $this->odoo_service->getCities($state_id);
        
        return response()->json($response, $response['response_code']);
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
