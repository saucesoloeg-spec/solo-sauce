<?php

namespace App\Http\Services;

use App\Domains\Odoo\Services\OdooAuthService;
use App\Http\Repositories\CustomerRepository;
use App\Http\Repositories\SalesRepository;
use Illuminate\Support\Facades\Log;

class SalesService
{
    private $sales_repository;
    private $customer_repository;
    private $odoo_service;

    public function __construct(
        SalesRepository $sales_repository, 
        CustomerRepository $customer_repository,
        OdooAuthService $odoo_service
    ) 
    {
        $this->sales_repository    = $sales_repository;
        $this->customer_repository = $customer_repository;
        $this->odoo_service = $odoo_service;
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

    public function getSchedule() 
    {
        $schedules = $this->sales_repository->getSchedule();
        $all_sales = $this->sales_repository->getAll();
        
        if($schedules->isNotEmpty()) {
            return [
                'response_code'    => 200,
                'response_message' => 'Schedule retrieved successfully.',
                'response_data'    => [
                    'schedules' => $schedules,
                    'sales'     => $all_sales
                ]
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'No schedule found.',
            'response_data'    => null
        ];
    }

    public function updateVisitDate($scheduleId, $visitDate, $salesId)
    {
        $updated = $this->sales_repository->updateVisitDate($scheduleId, $visitDate, $salesId);

        if($updated) {
            return [
                'response_code'    => 200,
                'response_message' => 'Visit date updated successfully.',
                'response_data'    => null
            ];
        }

        return [
            'response_code'    => 400,
            'response_message' => 'Failed to update visit date.',
            'response_data'    => null
        ];
    }

    public function deleteSchedule($scheduleId)
    {
        $deleted = $this->sales_repository->deleteSchedule($scheduleId);

        if($deleted) {
            return [
                'response_code'    => 200,
                'response_message' => 'Schedule deleted successfully.',
                'response_data'    => null
            ];
        }

        return [
            'response_code'    => 400,
            'response_message' => 'Failed to delete schedule.',
            'response_data'    => null
        ];
    }

    public function getById($id)
    {
        $sales = $this->sales_repository->getById($id);

        if($sales) {
            return [
                'response_code'    => 200,
                'response_message' => 'Sales retrieved successfully.',
                'response_data'    => $sales
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'Sales not found.',
            'response_data'    => null
        ];
    }

    public function updateSales($id, $data)
    {
        // Only update password if provided
        if(!$data['password']) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        $updated = $this->sales_repository->update($id, $data);

        if($updated) {
            return [
                'response_code'    => 200,
                'response_message' => 'Sales updated successfully.',
                'response_data'    => null
            ];
        }

        return [
            'response_code'    => 400,
            'response_message' => 'Failed to update sales.',
            'response_data'    => null
        ];
    }

    public function createSales($data)
    {
        // Generate UUID
        $data['uuid']     = \Illuminate\Support\Str::uuid();
        $data['password'] = bcrypt($data['password']);

        $authResponse = $this->odoo_service->createOdooAccount($data);

        if($authResponse['success'] == true) {
            $data['odoo_id'] = $authResponse['data']['user']['id'];
            $sales  = $this->sales_repository->create($data);

            // $updated = $this->odoo_service->updateOdooAccount([
            //     'odoo_id'      => $data['odoo_id'],
            //     'city_odoo_id' => $data['city_odoo_id']
            // ]);
            // dd($updated);

            if($sales && $sales->id) {// && $updated['success'] == true
                return [
                    'response_code'    => 201,
                    'response_message' => $updated['error']['detail'] ?? 'Sales created successfully.',
                    'response_data'    => $sales
                ];
            }

            return [
                'response_code'    => 400,
                'response_message' => 'Failed to create sales in Odoo.',
                'response_data'    => null
            ];
        }

        return [
            'response_code'    => 400,
            'response_message' => 'Failed to create sales.',
            'response_data'    => null
        ];
    }

    public function getScheduleInfo() 
    {
        $sales     = $this->sales_repository->getAll();
        $customers = $this->customer_repository->getAll();
        
        if($sales && $customers) {
            return [
                'response_code'    => 200,
                'response_message' => 'Data retrieved successfully',
                'response_data'    => [
                    'sales'     => $sales,
                    'customers' => $customers
                ]
            ];
        }

        return [
            'response_code'    => 500,
            'response_message' => 'Data retrieved Faild',
            'response_data'    => []
        ];
    }

    public function createSchedule($data) 
    {
        $result = $this->sales_repository->createSchedule($data);

        if($result) {
            // After creating schedule, attempt to notify the assigned salesman via FCM
            try {
                $sales = $this->sales_repository->getById($data['sales_id']);

                if($sales && isset($sales->fcm_token) && $sales->fcm_token) {
                    $title = config('firebase.fcm.default.title');
                    $body  = "New visit scheduled on " . ($data['visit_date'] ?? $data['visit_at'] ?? '');

                    $payload = [
                        'notification' => [
                            'title' => $title,
                            'body'  => $body,
                        ],
                        'data' => [
                            'type'        => 'schedule_created',
                            'schedule_id' => $result->id,
                            'customer_id' => $data['customer_id'] ?? null,
                            'visit_date'  => $data['visit_date'] ?? $data['visit_at'] ?? null,
                        ],
                        'to' => $sales->fcm_token,
                    ];

                    $this->sendFirebaseNotification($payload);
                }
            } catch (\Exception $e) {
                Log::error('FCM notification failed: ' . $e->getMessage());
            }
            return [
                'response_code'    => 200,
                'response_message' => 'Visit date Schedule successfully',
                'response_data'    => []
            ];
        }

        return [
            'response_code'    => 500,
            'response_message' => 'Create Visit date Schedule Failed',
            'response_data'    => []
        ];
    }

    private function sendFirebaseNotification(array $payload)
    {
        // If configured to use HTTP v1, delegate to v1 method
        if (config('firebase.fcm.use_v1')) {
            return $this->sendFirebaseV1Notification($payload);
        }

        $serverKey = config('firebase.fcm.server_key');
        $url       = config('firebase.fcm.send_url');

        if (!$serverKey) {
            Log::warning('FIREBASE_SERVER_KEY not configured, skipping push.');
            return false;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $result = curl_exec($ch);
        if ($result === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \Exception('FCM curl error: ' . $error);
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode < 200 || $httpCode >= 300) {
            throw new \Exception('FCM request failed with status ' . $httpCode . ' response: ' . $result);
        }

        return json_decode($result, true);
    }

    private function sendFirebaseV1Notification(array $payload)
    {
        $serviceAccountPath = config('firebase.fcm.service_account_path');
        $projectId = config('firebase.fcm.project_id');

        if (!$serviceAccountPath || !file_exists($serviceAccountPath)) {
            Log::warning('Firebase service account JSON not found: ' . $serviceAccountPath);
            throw new \Exception('Firebase service account JSON not found.');
        }

        if (!$projectId) {
            Log::warning('FIREBASE_PROJECT_ID not configured.');
            throw new \Exception('FIREBASE_PROJECT_ID not configured.');
        }

        $accessToken = $this->getAccessTokenFromServiceAccount($serviceAccountPath);

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        // convert legacy-like payload to v1 format
        $message = [
            'message' => [
                'token' => $payload['to'] ?? null,
                'notification' => $payload['notification'] ?? null,
                'data' => array_map('strval', $payload['data'] ?? []),
            ]
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));

        $result = curl_exec($ch);
        if ($result === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \Exception('FCM v1 curl error: ' . $error);
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode < 200 || $httpCode >= 300) {
            throw new \Exception('FCM v1 request failed with status ' . $httpCode . ' response: ' . $result);
        }

        return json_decode($result, true);
    }

    private function getAccessTokenFromServiceAccount(string $serviceAccountPath)
    {
        $json = json_decode(file_get_contents($serviceAccountPath), true);
        if (!$json) {
            throw new \Exception('Invalid service account JSON');
        }

        $now = time();
        $jwtHeader = ['alg' => 'RS256', 'typ' => 'JWT'];
        $scope = 'https://www.googleapis.com/auth/cloud-platform';

        $jwtClaim = [
            'iss' => $json['client_email'],
            'scope' => $scope,
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now,
        ];

        $base64url = function ($data) {
            return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
        };

        $unsignedJwt = $base64url(json_encode($jwtHeader)) . '.' . $base64url(json_encode($jwtClaim));

        $privateKey = openssl_pkey_get_private($json['private_key']);
        if (!$privateKey) {
            throw new \Exception('Invalid private key in service account JSON');
        }

        $signature = null;
        openssl_sign($unsignedJwt, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        openssl_free_key($privateKey);

        $signedJwt = $unsignedJwt . '.' . $base64url($signature);

        // Exchange JWT for access token
        $post = http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $signedJwt,
        ]);

        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $result = curl_exec($ch);
        if ($result === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \Exception('OAuth token request failed: ' . $error);
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode < 200 || $httpCode >= 300) {
            throw new \Exception('OAuth token request failed with status ' . $httpCode . ' response: ' . $result);
        }

        $data = json_decode($result, true);
        if (!isset($data['access_token'])) {
            throw new \Exception('No access_token in OAuth response: ' . $result);
        }

        return $data['access_token'];
    }
}