<?php

namespace Tests\Unit;

use App\Domains\Odoo\Repositories\OdooAuthRepository;
use App\Domains\Odoo\Services\OdooAuthService;
use App\Models\Sales;
use Mockery;
use Tests\TestCase;

class OdooAuthServiceTest extends TestCase
{
    private $originalEnvironment = [];

    protected function setUp(): void
    {
        parent::setUp();

        foreach ([
            'ODOO_API_URL',
            'ODOO_EMAIL',
            'ODOO_PASSWORD',
            'ODOO_SUPERUSER_EMAIL',
            'ODOO_SUPERUSER_PASSWORD',
        ] as $name) {
            $this->originalEnvironment[$name] = [
                'process' => getenv($name),
                'env' => array_key_exists($name, $_ENV) ? $_ENV[$name] : null,
                'server' => array_key_exists($name, $_SERVER) ? $_SERVER[$name] : null,
            ];
        }
    }

    protected function tearDown(): void
    {
        foreach ($this->originalEnvironment as $name => $values) {
            if ($values['process'] === false) {
                putenv($name);
            } else {
                putenv($name . '=' . $values['process']);
            }

            if ($values['env'] === null) {
                unset($_ENV[$name]);
            } else {
                $_ENV[$name] = $values['env'];
            }

            if ($values['server'] === null) {
                unset($_SERVER[$name]);
            } else {
                $_SERVER[$name] = $values['server'];
            }
        }

        parent::tearDown();
    }

    public function test_it_resets_invalid_sales_credentials_with_admin_and_retries()
    {
        foreach ([
            'ODOO_API_URL' => 'https://odoo.example.test',
            'ODOO_EMAIL' => 'admin@example.test',
            'ODOO_PASSWORD' => 'shared-system-password',
            'ODOO_SUPERUSER_EMAIL' => 'odoo-admin@example.test',
            'ODOO_SUPERUSER_PASSWORD' => 'admin-password',
        ] as $name => $value) {
            putenv($name . '=' . $value);
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }

        $repository = Mockery::mock(OdooAuthRepository::class);
        $repository->shouldReceive('getLatestToken')
            ->once()
            ->with('sales@example.test')
            ->andReturn((object) [
                'access_token' => 'expired-token',
                'expires_at' => 3600,
                'updated_at' => now()->subHours(2)->toDateTimeString(),
            ]);
        $repository->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($data) {
                return $data['email'] === 'sales@example.test'
                    && $data['access_token'] === 'fresh-token'
                    && $data['refresh_token'] === 'fresh-refresh-token';
            }))
            ->andReturn((object) [
                'access_token' => 'fresh-token',
                'refresh_token' => 'fresh-refresh-token',
                'expires_at' => 3600,
            ]);

        $responses = [
            [
                'status' => 401,
                'body' => [
                    'success' => false,
                    'error' => ['code' => 'INVALID_CREDENTIALS'],
                ],
            ],
            [
                'status' => 200,
                'body' => [
                    'success' => true,
                    'data' => ['tokens' => ['access_token' => 'admin-token']],
                ],
            ],
            ['status' => 200, 'body' => ['success' => true]],
            [
                'status' => 200,
                'body' => [
                    'success' => true,
                    'data' => [
                        'tokens' => [
                            'access_token' => 'fresh-token',
                            'refresh_token' => 'fresh-refresh-token',
                            'expires_in' => 3600,
                        ],
                    ],
                ],
            ],
        ];

        $service = new class($repository, $responses) extends OdooAuthService {
            public $requests = [];
            private $responses;

            public function __construct($repository, $responses)
            {
                parent::__construct($repository);
                $this->responses = $responses;
            }

            public function accessToken()
            {
                return $this->getAccessToken();
            }

            protected function postOdooJson($path, array $payload, $token = null)
            {
                $this->requests[] = compact('path', 'payload', 'token');

                return array_shift($this->responses);
            }
        };

        $sales = new Sales();
        $sales->email = 'sales@example.test';
        auth('sales')->setUser($sales);

        $token = $service->accessToken();

        $this->assertSame('fresh-token', $token['access_token']);
        $this->assertSame('/auth/signin', $service->requests[0]['path']);
        $this->assertSame('sales@example.test', $service->requests[0]['payload']['email']);
        $this->assertSame('/auth/signin', $service->requests[1]['path']);
        $this->assertSame('odoo-admin@example.test', $service->requests[1]['payload']['email']);
        $this->assertSame('/auth/reset-password', $service->requests[2]['path']);
        $this->assertSame('admin-token', $service->requests[2]['token']);
        $this->assertSame('shared-system-password', $service->requests[2]['payload']['new_password']);
        $this->assertSame('shared-system-password', $service->requests[2]['payload']['confirm_password']);
        $this->assertSame('/auth/signin', $service->requests[3]['path']);
    }
}
