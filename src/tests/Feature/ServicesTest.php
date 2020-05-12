<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServicesTest extends TestCase
{
    use RefreshDatabase;
    
    public function testServicesAreListedCorrectly()
    {
        factory(Service::class)->create([
            'name' => 'First Service',
            'price' => '100',
        ]);

        factory(Service::class)->create([
            'name' => 'Second Service',
            'price' => '200',
        ]);

        $user = factory(User::class)->create();
        $token = $user->api_token;
        $headers = ['Authorization' => "Bearer $token"];

        $this->json('GET', '/api/services', [], $headers)
            ->assertStatus(200)
            ->assertJsonStructure(['response' =>
                [
                    '*' => ['id', 'name', 'price', 'created_at', 'updated_at']
                ]
            ])
            ->assertJson(['response' => 
                [
                    [ 'name' => 'First Service', 'price' => '100' ],
                    [ 'name' => 'Second Service', 'price' => '200' ]
                ]
            ]);
    }

    public function testsServicesAreCreatedCorrectly()
    {
        $user = factory(User::class)->create();
        $token = $user->api_token;
        $headers = ['Authorization' => "Bearer $token"];
        $payload = [
            'name' => 'Test Service',
            'price' => '100',
        ];

        $this->json('POST', '/api/services', $payload, $headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'response' => ['id', 'name', 'price', 'created_at', 'updated_at']
            ])
            ->assertJson(['response' => 
                [ 'name' => $payload['name'], 'price' => $payload['price'] ]
            ]);

        // Assert that service was added to database
        $this->assertEquals(1, Service::count());
    }

    public function testsServicesAreUpdatedCorrectly()
    {
        $user = factory(User::class)->create();
        $token = $user->api_token;
        $headers = ['Authorization' => "Bearer $token"];
        $service = factory(Service::class)->create([
            'name' => 'Test Service',
            'price' => '100',
        ]);

        // Assert that service was added to database
        $this->assertEquals(1, Service::count());

        $payload = [
            'name' => 'Changed Service',
            'price' => '200',
        ];

        $this->json('PUT', '/api/services/' . $service->id, $payload, $headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'response' => ['id', 'name', 'price', 'created_at', 'updated_at']
            ])
            ->assertJson(['response' =>
                [ 'id' => $service->id, 'name' => $payload['name'], 'price' => $payload['price'] ]
            ]);
    }

    public function testsServicesAreDeletedCorrectly()
    {
        $user = factory(User::class)->create();
        $token = $user->api_token;
        $headers = ['Authorization' => "Bearer $token"];
        $service = factory(Service::class)->create([
            'name' => 'Test Service',
            'price' => '100',
        ]);

        // Assert that service was added to database
        $this->assertEquals(1, Service::count());

        $this->json('DELETE', '/api/services/' . $service->id, [], $headers)
            ->assertStatus(200);

        // Assert that service was deleted from database
        $this->assertEquals(0, Service::count());
    }
}
