<?php

namespace App\Domains\Products\Controllers;

use App\Domains\Odoo\Requests\GetProductRequest;
use App\Domains\Products\Services\ProductService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public $product_service;

    public function __construct(ProductService $product_service)
    {
        $this->product_service = $product_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GetProductRequest $request)
    {
        $response = $this->product_service->getAllProducts($request->validated());
        
        return response()->json($response);
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
        $response = $this->product_service->getProductById($id);
        return response()->json($response);
    }

    public function stockBalance(Request $request)
    {
        $filters = $request->validate([
            'product_id'  => ['sometimes', 'nullable', 'integer'],
        ]);

        $sales = auth('sales')->user();
        $location = Validator::make(
            ['location_id' => $sales ? $sales->storage_location_odoo_id : null],
            ['location_id' => ['required', 'integer']]
        )->validate();

        $filters['location_id'] = $location['location_id'];

        $response = $this->product_service->getStockBalance($filters);

        return response()->json($response);
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
