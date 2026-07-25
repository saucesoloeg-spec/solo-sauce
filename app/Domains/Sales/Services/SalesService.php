<?php

namespace App\Domains\Sales\Services;

use App\Domains\Customers\Repositories\CustomerRepository;
use App\Domains\Customers\Services\CustomerService;
use App\Domains\Orders\Repositories\OrderRepository;
use App\Domains\Sales\Repositories\SalesRepository;
use App\Domains\Surveys\Repositories\SurveyRepository;
use Illuminate\Support\Facades\Log;

class SalesService
{
    protected $sales_repository;
    protected $order_repository;
    protected $survey_repository;
    protected $customer_repository;

    public function __construct(
        SalesRepository $sales_repository, 
        OrderRepository $order_repository, 
        SurveyRepository $survey_repository,
        CustomerRepository $customer_repository
    )
    {
        $this->sales_repository    = $sales_repository;
        $this->order_repository    = $order_repository;
        $this->survey_repository   = $survey_repository;
        $this->customer_repository = $customer_repository;
    }

    public function dashboard($data) 
    {
        $sales = auth('sales')->user();

        $visits = $this->sales_repository->getAllBySalesId($sales->id, $data);
        
        $new_deals = $this->order_repository->getNewDealsForSales($sales->id, $data);
        $regular_deals = $this->order_repository->getRegularDealsForSales($sales->id, $data);

        $response = [
            'total_visits'     => $visits->count(),
            'today_visits'     => $visits->where('visit_at', date('Y-m-d'))->count(),
            'upcoming_visits'  => $visits->where('visit_at', '>', date('Y-m-d'))->count(),
            'past_visits'      => $visits->where('visit_at', '<', date('Y-m-d'))->where('status', 'completed')->count(),
            'cancelled_visits' => $visits->where('visit_at', '<', date('Y-m-d'))->where('status', 'cancelled')->count(),
            'new_deals'        => $new_deals->count(),
            'regular_deal'     => $regular_deals->count()
        ];

        if($visits->isNotEmpty() || $new_deals->isNotEmpty() || $regular_deals->isNotEmpty()) {
            return [
                'response_code'    => 200,
                'response_message' => 'Dashboard data retrieved successfully.',
                'response_data'    => $response
            ];
        }

        return [
            'response_code'    => 200,
            'response_message' => 'No dashboard data found.',
            'response_data'    => $response
        ];
    }

    public function getAll($customer_id = null) 
    {
        $customer = null;
        $cityOdooId = null;

        if ($customer_id) {
            $customer = $this->customer_repository->find($customer_id);
            if ($customer) {
                $cityOdooId = $customer->city_odoo_id;
            }
        }

        $sales = $this->sales_repository->getAllSales($cityOdooId);
        
        if($sales->isNotEmpty()) {
            return [
                'response_code'    => 200,
                'response_message' => 'Sales retrieved successfully.',
                'response_data'    => $sales
            ];
        }

        return [
            'response_code'    => 200,
            'response_message' => 'No sales found for the selected customer.',
            'response_data'    => []
        ];
    }

    public function getSchedule($request) 
    {
        $sales         = auth('sales')->user();
        
        $schedules     = $this->sales_repository->getSchedule($sales->id, $request);
        $new_deals     = $this->order_repository->getNewDealsForSales($sales->id, $request);
        $regular_deals = $this->order_repository->getRegularDealsForSales($sales->id, $request);
        $surveys       = $this->survey_repository->getAnswersBySalesId($sales->id); 
        
        if($schedules->isNotEmpty() || $new_deals->isNotEmpty() || $regular_deals->isNotEmpty() || $surveys->isNotEmpty()) {
            return [
                'response_code'    => 200,
                'response_message' => 'Schedule retrieved successfully.',
                'response_data'    => [
                    'visits'         => $schedules,
                    'new_deals'      => $new_deals,
                    'regular_deals'  => $regular_deals,
                    'surveys'        => $surveys
                ]
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'No schedule found.',
            'response_data'    => null
        ];
    }

    public function scheduleHistory($request) 
    {
        $sales         = auth('sales')->user();
        $schedules     = $this->sales_repository->getPastSchedule($sales->id, $request);
        $new_deals     = $this->order_repository->getNewDealsForSales($sales->id, $request);
        $regular_deals = $this->order_repository->getRegularDealsForSales($sales->id, $request);
        $surveys       = $this->survey_repository->getAnswersBySalesId($sales->id); 
        
        if($schedules->isNotEmpty() || $new_deals->isNotEmpty() || $regular_deals->isNotEmpty() || $surveys->isNotEmpty()) {
            return [
                'response_code'    => 200,
                'response_message' => 'Schedule history retrieved successfully.',
                'response_data'    => [
                    'visits'         => $schedules,
                    'new_deals'      => $new_deals,
                    'regular_deals'  => $regular_deals,
                    'surveys'        => $surveys
                ]
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'No schedule history found.',
            'response_data'    => null
        ];
    }

    public function cancelSchedule($scheduleId, $salesId)
    {
        $cancelled = $this->sales_repository->cancelSchedule($scheduleId, $salesId);

        if ($cancelled) {
            return [
                'response_code'    => 200,
                'response_message' => 'Schedule cancelled successfully.',
                'response_data'    => null
            ];
        }

        return [
            'response_code'    => 404,
            'response_message' => 'Schedule not found or not belonging to this sales user.',
            'response_data'    => null
        ];
    }

    public function sendFirebaseTestNotification($sales, array $data = [])
    {
        if (!$sales || empty($sales->fcm_token)) {
            return [
                'response_code'    => 400,
                'response_message' => 'Salesman does not have an FCM token. Log in again with fcm_token first.',
                'response_data'    => null,
            ];
        }

        $payload = [
            'notification' => [
                'title' => $data['title'] ?? config('firebase.fcm.default.title'),
                'body'  => $data['body'] ?? config('firebase.fcm.default.body'),
            ],
            'data' => [
                'type'     => 'sales_test_notification',
                'sales_id' => (string) $sales->id,
                'sent_at'  => now()->toDateTimeString(),
            ],
            'to' => $sales->fcm_token,
        ];

        try {
            $result = $this->sendFirebaseNotification($payload);

            return [
                'response_code'    => 200,
                'response_message' => 'Firebase notification sent successfully.',
                'response_data'    => $result,
            ];
        } catch (\Exception $e) {
            Log::error('Test Firebase notification failed: ' . $e->getMessage());

            return [
                'response_code'    => 500,
                'response_message' => 'Failed to send Firebase notification.',
                'response_data'    => null,
            ];
        }
    }

    private function sendFirebaseNotification(array $payload)
    {
        if (config('firebase.fcm.use_v1')) {
            return $this->sendFirebaseV1Notification($payload);
        }

        $serverKey = config('firebase.fcm.server_key');
        $url = config('firebase.fcm.send_url');

        if (!$serverKey) {
            Log::warning('FIREBASE_SERVER_KEY not configured, skipping push.');
            return false;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json',
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

        $message = [
            'message' => [
                'token' => $payload['to'] ?? null,
                'notification' => $payload['notification'] ?? null,
                'data' => array_map('strval', $payload['data'] ?? []),
            ],
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
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

        $post = http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $signedJwt,
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $result = curl_exec($ch);
        if ($result === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \Exception('OAuth token curl error: ' . $error);
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode < 200 || $httpCode >= 300) {
            throw new \Exception('OAuth token request failed with status ' . $httpCode . ' response: ' . $result);
        }

        $decoded = json_decode($result, true);

        if (!isset($decoded['access_token'])) {
            throw new \Exception('Unable to retrieve access token from service account');
        }

        return $decoded['access_token'];
    }
}