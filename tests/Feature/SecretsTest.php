<?php

namespace Tests\Feature;

use App\Models\Secret;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class SecretsTest extends TestCase
{
    use DatabaseMigrations;

    const CRUD_ENDPOINT = '/api/secret';

    public function testSecretCreated()
    {
        $parameters = [
            'secret' => 'foobar',
            'password' => 'password123',
            'expire_sec' => 10
        ];

        $response = $this->call('POST', self::CRUD_ENDPOINT, $parameters);

        $response->assertStatus(201);
        $response->assertJsonStructure(['uuid']);
    }

    public function testSecretRetrieveRequiresPasswordParam()
    {
        /** @var Secret $secret */
        $secret = factory(Secret::class)->create();

        $response = $this->getJson(self::CRUD_ENDPOINT . '/' . $secret->uuid);

        $response->assertStatus(422);
        $response->assertJsonStructure(['password']);
    }

    public function testSecretDecrypt()
    {
        /** @var Secret $secret */
        $secret = factory(Secret::class)->create();

        $response = $this->getJson(self::CRUD_ENDPOINT . '/' . $secret->uuid . '?' . http_build_query([
            'password' => '12345'
        ]));

        $response->assertStatus(200);
        $response->assertJson(['text' => 'foobar']);
    }

    public function testSecretDeleteWhenAttemptsExceeded()
    {
        $secret = factory(Secret::class)->create();

        for ($i = Secret::ATTEMPTS_MAX; $i > 0; $i--) {
            $response = $this->getJson(self::CRUD_ENDPOINT . '/' . $secret->uuid . '?' . http_build_query([
                'password' => '123456'
            ]));

            $response->assertStatus(400);
            $response->assertJson([
                'error' => 'Wrong password',
                'attempts_left' => $i - 1
            ]);
        }

        $response = $this->getJson(self::CRUD_ENDPOINT . '/' . $secret->uuid . '?' . http_build_query([
            'password' => '123456'
        ]));

        $this->assertDatabaseMissing('secrets', ['uuid' => $secret->uuid]);
        $response->assertStatus(404);
    }

    public function testSecretDeleteItselfWhenExpired()
    {
        $secret = factory(Secret::class)->create([
            'expires_at' => date('Y-m-d H:i:s', time() - 1000)
        ]);

        $response = $this->getJson(self::CRUD_ENDPOINT . '/' . $secret->uuid . '?' . http_build_query([
            'password' => '12345'
        ]));

        $response->assertStatus(400);
        $response->assertJson(['error' => 'Secret expired']);
        $this->assertDatabaseMissing('secrets', ['uuid' => $secret->uuid]);
    }
    
    public function testSecretDeleteWithCommandWhenExpired()
    {
        $secret = factory(Secret::class)->create([
            'expires_at' => date('Y-m-d H:i:s', time() - 1000)
        ]);

        Artisan::call('secrets:delete-expired');

        $this->assertDatabaseMissing('secrets', ['uuid' => $secret->uuid]);
    }
}
