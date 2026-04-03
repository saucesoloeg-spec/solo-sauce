<?php

namespace App\Domains\Products\Services;

use App\Domains\Odoo\Services\OdooAuthService;

class ProductService
{
    protected $odoo_auth_service;

    public function __construct(OdooAuthService $odoo_auth_service)
    {
        $this->odoo_auth_service = $odoo_auth_service;
    }

    public function getAllProducts($request)
    {
        $products = $this->odoo_auth_service->getProductsFromOdoo($request);

        if($products['success']) {
            return [
                'response_code'    => 200,
                'response_message' => 'Products retrieved successfully',
                'response_data'    => $products['data']
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'No products found',
            'response_data'    => []
        ];
    }

    public function getProductById($id)
    {
        $product = $this->odoo_auth_service->getProductByIdFromOdoo($id);

        if($product['success']) {
            return [
                'response_code'    => 200,
                'response_message' => 'Product retrieved successfully',
                'response_data'    => $product['data']
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'Product not found',
            'response_data'    => null
        ];
    }
    
}