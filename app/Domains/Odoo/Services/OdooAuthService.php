<?php

namespace App\Domains\Odoo\Services;

use App\Domains\Odoo\Repositories\OdooAuthRepository;

class OdooAuthService
{
    public $odoo_auth_repository;

    public function __construct(OdooAuthRepository $odoo_auth_repository)
    {
        $this->odoo_auth_repository = $odoo_auth_repository;
    }

    public function createOdooAccount($request = [])
    {
        $response = curl_init(env('ODOO_API_URL').'/auth/signup');
        curl_setopt($response, CURLOPT_POST, true);
        curl_setopt($response, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($response, CURLOPT_POSTFIELDS, json_encode([
            'name'       => $request['name'] ?? "Solo Sauce",
            'email'      => $request['email'] ?? env('ODOO_EMAIL'),
            'password'   => env('ODOO_PASSWORD'),
            'phone'      => $request['phone'] ?? "01234567890",
            'country_id' => $request['country_odoo_id'] ?? 65, // Egypt country id in Odoo
            'state_id'   => $request['state_odoo_id'] ?? null,
            'city_id'    => $request['city_odoo_id'] ?? 5, // Egypt city id in Odoo
        ]));
        curl_setopt($response, CURLOPT_RETURNTRANSFER, true);
        
        try {
            $result = json_decode(curl_exec($response), true);
            if(isset($result['error'])) {
                throw new \Exception('Failed to create Odoo account: ' . $result['error']['detail']);
            }            

            $this->odoo_auth_repository->create([
                'email'         => $request['email'] ?? env('ODOO_EMAIL'),
            ]);
            
            return $result;
        } catch (\Throwable $th) {
            dd($result, $th->getMessage());
            throw new \Exception($result);
        }

        curl_close($response);
        
        return $result;
    }

    public function updateOdooAccount($request = [])
    {
        $token    = $this->getAccessToken()['access_token'];
        $response = curl_init(env('ODOO_API_URL').'/managers/salespersons/'.$request['odoo_id']);
        curl_setopt($response, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($response, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
        ));
        curl_setopt($response, CURLOPT_POSTFIELDS, json_encode([
            'allowed_city_ids' => [$request['city_odoo_id']] ?? 5, // Egypt city id in Odoo
        ]));
        curl_setopt($response, CURLOPT_RETURNTRANSFER, true);
        
        try {
            $result = json_decode(curl_exec($response), true);
            if(isset($result['error'])) {
                throw new \Exception('Failed to update Odoo account: ' . $result['error']['detail']);
            }
            
            return $result;
        } catch (\Throwable $th) {
            dd($result, $th->getMessage());
            throw new \Exception($result);
        }

        curl_close($response);
        return $result;
    }

    protected function getAccessToken()
    {
        $sales = auth('sales')->user();
        $email = $sales ? $sales->email : env('ODOO_SUPERUSER_EMAIL');
        $password = $sales ? env('ODOO_PASSWORD') : env('ODOO_SUPERUSER_PASSWORD');
        
        $result = [];
        $true = true;
        while ($true) {
            if($exists = $this->odoo_auth_repository->getLatestToken($email)) {
                // add the number of minutes in expires_at to the created_at timestamp and compare it with the current time to check if the token is still valid
                if(isset($exists->access_token) && isset($exists->expires_at) && strtotime($exists->updated_at) + $exists->expires_at > now()->timestamp){
                    $true = false;
                    return $exists->toArray();
                } 
                else {
                    $response = curl_init(env('ODOO_API_URL').'/auth/signin');
                    curl_setopt($response, CURLOPT_POST, true);
                    curl_setopt($response, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                    curl_setopt($response, CURLOPT_POSTFIELDS, json_encode([
                        'email'    => $email,
                        'password' => $password,
                    ]));
                    curl_setopt($response, CURLOPT_RETURNTRANSFER, true);
                    $data = curl_exec($response);
                    $data = json_decode($data, true);
                    curl_close($response);
                    
                    if($data['success'] && isset($data['data']['tokens'])) {
                        $result = $this->odoo_auth_repository->create([
                            'email'         => $email,
                            'access_token'  => $data['data']['tokens']['access_token'],
                            'refresh_token' => $data['data']['tokens']['refresh_token'],
                            'expires_at'    => $data['data']['tokens']['expires_in'],
                        ]);
                    }
                    $true = false;
                }
            }
            else {
                $this->createOdooAccount([
                    'name'       => $sales->name ?? "Solo Sauce",
                    'email'      => $email,
                    'password'   => env('ODOO_PASSWORD'),
                    'phone'      => $sales->phone ?? "01234567890",
                    'country_id' => $sales->country_odoo_id ?? 65, // Egypt country id in Odoo
                    'state_id'   => $sales->state_odoo_id ?? null,
                    'city_id'    => $sales->city_odoo_id ?? 5, // Egypt city id in Odoo
                ]);
            }
        }
        
        return [
            'access_token'  => $result->access_token,
            'refresh_token' => $result->refresh_token,
            'expires_at'    => $result->expires_at,
        ];
    }

    public function getProductsFromOdoo($filters = [])
    {
        $token = $this->getAccessToken()['access_token'];
        $url = env('ODOO_API_URL').'/products';

        if (!empty($filters)) {
            $url .= '?' . http_build_query($filters);
        }

        $response = curl_init($url);
        curl_setopt($response, CURLOPT_HTTPGET, true);
        curl_setopt($response, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
        ));
        curl_setopt($response, CURLOPT_RETURNTRANSFER, true);
        
        try {
            $result = json_decode(curl_exec($response), true);
            if(isset($result['error'])) {
                throw new \Exception('Failed to fetch products from Odoo: ' . $result['error']['details']);
            }
        } catch (\Throwable $th) {
            throw new \Exception('Failed to fetch products from Odoo: ' . $th->getMessage());
        }

        curl_close($response);

        return $result;
    }

    public function getProductByIdFromOdoo($id)
    {
        $token = $this->getAccessToken()['access_token'];

        $response = curl_init(env('ODOO_API_URL').'/products/' . $id);
        curl_setopt($response, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($response, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
        ));
        curl_setopt($response, CURLOPT_RETURNTRANSFER, true);
        
        try {
            $result = json_decode(curl_exec($response), true);
            if(isset($result['error'])) {
                throw new \Exception('Failed to fetch product from Odoo: ' . $result['error']['details']);
            }
        } catch (\Throwable $th) {
            throw new \Exception('Failed to fetch product from Odoo: ' . $th->getMessage());
        }

        curl_close($response);

        return $result;
    }

    public function getCustomers($filters = [])
    {
        $token = $this->getAccessToken()['access_token'];
        $url = env('ODOO_API_URL').'/customers';

        if (!empty($filters)) {
            $url .= '?' . http_build_query($filters);
        }
        
        $response = curl_init($url);
        curl_setopt($response, CURLOPT_HTTPGET, true);
        curl_setopt($response, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Authorization: Bearer ' . $token,
        ));
        curl_setopt($response, CURLOPT_RETURNTRANSFER, true);
        
        try {
            $result = json_decode(curl_exec($response), true);
            if(isset($result['error'])) {
                throw new \Exception('Failed to fetch customers from Odoo: ' . $result['error']['details']);
            }
        } catch (\Throwable $th) {
            throw new \Exception('Failed to fetch customers from Odoo: ' . $th->getMessage());
        }

        curl_close($response);

        return $result;
    }

    public function getCustomerById($id)
    {
        $token = $this->getAccessToken()['access_token'];
        $url = env('ODOO_API_URL').'/customers/' . $id;

        $response = curl_init($url);
        curl_setopt($response, CURLOPT_HTTPGET, true);
        curl_setopt($response, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Authorization: Bearer ' . $token,
        ));
        curl_setopt($response, CURLOPT_RETURNTRANSFER, true);

        try {
            $result = json_decode(curl_exec($response), true);
            if(isset($result['error'])) {
                throw new \Exception('Failed to fetch customer from Odoo: ' . $result['error']['details']);
            }
        } catch (\Throwable $th) {
            throw new \Exception('Failed to fetch customer from Odoo: ' . $th->getMessage());
        }

        curl_close($response);

        return $result;
    }

    public function getOrders($filters = [])
    {
        $token = $this->getAccessToken()['access_token'];
        if(!empty($filters)) {
            $filters = array_filter($filters, function($value) {
                return !is_null($value) && $value !== '';
            });
            $url = env('ODOO_API_URL').'/sales-orders?'. http_build_query($filters);
        }
            $url = env('ODOO_API_URL').'/sales-orders';

        if (!empty($filters)) {
            $url .= '?' . http_build_query($filters);
        }
        
        $response = curl_init($url);
        curl_setopt($response, CURLOPT_HTTPGET, true);
        // 
        curl_setopt($response, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'content-type: application/json',
            'Authorization: Bearer ' . $token,
        ));

        curl_setopt($response, CURLOPT_RETURNTRANSFER, true);
        
        try {
            $result = json_decode(curl_exec($response), true);
            if(isset($result['error'])) {
                throw new \Exception('Failed to fetch orders from Odoo: ' . $result['error']['details']);
            }
        } catch (\Throwable $th) {
            throw new \Exception('Failed to fetch orders from Odoo: ' . $th->getMessage());
        }

        curl_close($response);

        return $result;
    }

    public function sendOrderToOdoo($order)
    {
        $token = $this->getAccessToken()['access_token'];
        $url = env('ODOO_API_URL').'/sales-orders';
        
        $response = curl_init($url);
        curl_setopt($response, CURLOPT_POST, true);
        curl_setopt($response, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'content-type: application/json',
            'Authorization: Bearer ' . $token,
        ));
        curl_setopt($response, CURLOPT_POSTFIELDS, json_encode($order));
        curl_setopt($response, CURLOPT_RETURNTRANSFER, true);
        
        try {
            $result = json_decode(curl_exec($response), true);
            if(isset($result['error'])) {
                throw new \Exception('Failed to send order to Odoo: ' . $result['error']['detail']);
            }
        } catch (\Throwable $th) {
            throw new \Exception('Failed to send order to Odoo: ' . $th->getMessage());
        }

        curl_close($response);

        return $result;
    }

    public function getOrderById($id)
    {
        $token = $this->getAccessToken()['access_token'];
        $url = env('ODOO_API_URL').'/sales-orders/' . $id;

        $response = curl_init($url);
        curl_setopt($response, CURLOPT_HTTPGET, true);
        curl_setopt($response, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'content-type: application/json',
            'Authorization: Bearer ' . $token,
        ));
        curl_setopt($response, CURLOPT_RETURNTRANSFER, true);

        try {
            $result = json_decode(curl_exec($response), true);
            if(isset($result['error'])) {
                throw new \Exception('Failed to fetch order from Odoo: ' . $result['error']['detail']);
            }
        } catch (\Throwable $th) {
            throw new \Exception('Failed to fetch order from Odoo: ' . $th->getMessage());
        }

        curl_close($response);

        return $result;
    }

    public function getCountries()
    {
        $token = $this->getAccessToken()['access_token'];
        $url = env('ODOO_API_URL').'/locations/countries';

        $response = curl_init($url);
        curl_setopt($response, CURLOPT_HTTPGET, true);
        curl_setopt($response, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'content-type: application/json',
            'Authorization: Bearer ' . $token,
        ));
        curl_setopt($response, CURLOPT_RETURNTRANSFER, true);

        try {
            $result = json_decode(curl_exec($response), true);
            
            if(isset($result['error'])) {
                throw new \Exception('Failed to fetch order from Odoo: ' . $result['error']['detail']);
            }
        } catch (\Throwable $th) {
            throw new \Exception('Failed to fetch order from Odoo: ' . $th->getMessage());
        }

        curl_close($response);

        return $result;
    }

    public function getStates($country_id)
    {
        $token = $this->getAccessToken()['access_token'];
        $url = env('ODOO_API_URL').'/locations/states?country_id=' . $country_id;

        $response = curl_init($url);
        curl_setopt($response, CURLOPT_HTTPGET, true);
        curl_setopt($response, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            // 'content-type: application/json',
            'Authorization: Bearer ' . $token,
        ));
        curl_setopt($response, CURLOPT_RETURNTRANSFER, true);

        try {
            $result = json_decode(curl_exec($response), true);
            if(isset($result['error'])) {
                throw new \Exception('Failed to fetch order from Odoo: ' . $result['error']['detail']);
            }
            
            if($result['success']) {
                return [
                    'response_code'    => 200,
                    'response_message' => 'states retrieved successfully',
                    'response_data'    => $result['data'],
                ];
            }

            return [
                'response_code'    => 400,
                'response_message' => 'states retrieving Faild',
                'response_data'    => [],
            ];

        } catch (\Throwable $th) {
            throw new \Exception('Failed to fetch order from Odoo: ' . $th->getMessage());
        }

        curl_close($response);

        return $result;
    }

    public function getCities($state_id)
    {
        $token = $this->getAccessToken()['access_token'];
        $url = env('ODOO_API_URL').'/locations/cities?state_id=' . $state_id;

        $response = curl_init($url);
        curl_setopt($response, CURLOPT_HTTPGET, true);
        curl_setopt($response, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            // 'content-type: application/json',
            'Authorization: Bearer ' . $token,
        ));
        curl_setopt($response, CURLOPT_RETURNTRANSFER, true);

        try {
            $result = json_decode(curl_exec($response), true);
            if(isset($result['error'])) {
                throw new \Exception('Failed to fetch order from Odoo: ' . $result['error']['detail']);
            }
            
            if($result['success']) {
                return [
                    'response_code'    => 200,
                    'response_message' => 'Cities retrieved successfully',
                    'response_data'    => $result['data'],
                ];
            }

            return [
                'response_code'    => 400,
                'response_message' => 'Cities retrieving Faild',
                'response_data'    => [],
            ];

        } catch (\Throwable $th) {
            throw new \Exception('Failed to fetch order from Odoo: ' . $th->getMessage());
        }

        curl_close($response);

        return $result;
    }

}