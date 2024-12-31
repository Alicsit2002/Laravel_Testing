<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{

    public function test_store_user()
    {
        $response = $this->postJson('/api/users', [
            'name' => 'Test User',
            'email' => 'testd@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertSuccessful();
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    public function test_index_users()
    {
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/auth');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    public function test_update_user()
    {
        $user = User::factory()->create();

        $response = $this->putJson("/api/auth/{$user->id}", [
            'name' => 'Updated User',
            'email' => 'updated@example.com',
            'password' => 'newpassword',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'name' => 'Updated User',
            'email' => 'updated@example.com',
        ]);
    }

    public function test_destroy_user()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/auth/{$user->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
