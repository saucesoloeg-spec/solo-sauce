<?php

namespace App\Domains\Products\Services;

class ProductService
{
    public function getProductsFromOdoo()
    {
        // Here you would typically make an API call to Odoo to fetch the products data
        // For example, using GuzzleHttp to send a request to Odoo's API endpoint
        // and then process the response to return the products data in the desired format.

        // This is a placeholder for the actual implementation.
    }

    public function getProductFromOdooById($id)
    {
        // Similar to the above method, but you would include the product ID in the API request
        // to fetch the specific product details from Odoo.
    }

    public function getAllProducts()
    {
        // $products = $this->product_repository->getAll();

        // Creating a pesudo response to test the API endpoint, you can replace it with the actual data from the repository
        $products = [
            [
                "id"                 => 10,
                "name"               => "Product A",
                "code"               => "PROD-A-001",
                "barcode"            => "1234567890123",
                "category_id"        => 5,
                "category_name"      => "Electronics",
                "list_price"         => 500.00,
                "standard_price"     => 350.00,
                "uom"                => "Units",
                "uom_po"             => "Units",
                "image_url"          => "https://example.com/img/prod-a.jpg",
                "description"        => "High quality product",
                "available_quantity" => 100.0,
                "active"             => true
            ],
            [
                "id"                 => 11,
                "name"               => "Product B",
                "code"               => "PROD-B-002",
                "barcode"            => "9876543210987",
                "category_id"        => 5,
                "category_name"      => "Electronics",
                "list_price"         => 300.00,
                "standard_price"     => 200.00,
                "uom"                => "Units",
                "uom_po"             => "Units",
                "image_url"          => "https://example.com/img/prod-b.jpg",
                "description"        => "Affordable product",
                "available_quantity" => 50.0,
                "active"             => true
            ],
            [
                "id"                 => 12,
                "name"               => "Product C",
                "code"               => "PROD-C-003",
                "barcode"            => "5555555555555",
                "category_id"        => 5,
                "category_name"      => "Electronics",
                "list_price"         => 150.00,
                "standard_price"     => 100.00,
                "uom"                => "Units",
                "uom_po"             => "Units",
                "image_url"          => "https://example.com/img/prod-c.jpg",
                "description"        => "Budget-friendly product",
                "available_quantity" => 200.0,
                "active"             => true
            ]
        ];

        if($products) {
            return [
                'response_code'    => 200,
                'response_message' => 'Products retrieved successfully',
                'response_data'    => $products
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
        // You would typically fetch the product details from odoo using the provided ID
        // For example:
        $product = [
            "id"                 => $id,
            "name"               => "Product A",
            "code"               => "PROD-A-001",
            "barcode"            => "1234567890123",
            "category_id"        => 5,
            "category_name"      => "Electronics",
            "list_price"         => 500.00,
            "standard_price"     => 350.00,
            "uom"                => "Units",
            "uom_po"             => "Units",
            "image_url"          => "https://example.com/img/prod-a.jpg",
            "description"        => "High quality product",
            "available_quantity" => 100.0,
            "active"             => true
        ];

        if($product) {
            return [
                'response_code'    => 200,
                'response_message' => 'Product retrieved successfully',
                'response_data'    => $product
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'Product not found',
            'response_data'    => null
        ];
    }
    
}