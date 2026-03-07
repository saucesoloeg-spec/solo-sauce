<?php

namespace App\Http\Services;

use App\Http\Repositories\SalesRepository;

class SalesService
{
    private $sales_repository;

    public function __construct(SalesRepository $sales_repository) 
    {
        $this->sales_repository = $sales_repository;
    }

    public function getAll() 
    {
        $sales = $this->sales_repository->getAll();  
        
        if($sales->isNotEmpty()) {
            return [
                'response_code'    => 200,
                'response_message' => 'Sales retrieved successfully.',
                'response_data'    => $sales
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'No customers found.',
            'response_data'    => null
        ];
    }

}