<?php

namespace Tests\Feature;

use App\Models\User;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    // use RefreshDatabase;

    public function test_user_can_register()
    {
         $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'alimohamed@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            ]);
        $response->assertStatus(201);

    }
    public function test_user_can_login()
    {
         $response = $this->postJson('/api/login', [
            'email' => 'alimohamed@gmail.com',
            'password' => 'password',
            ]);
        $response->assertStatus(200);
        $response->assertSuccessful();
}
    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->postJson('/api/logout');
        $response->assertStatus(200);
        $response->assertSuccessful();
    }
    public function test_user_can_logout2()
    {
        // إنشاء مستخدم يدويًا
        $user = User::create([
            'name' => 'Test Userdssa',
            'email' => 'testsassd@example.com',
            'password' => bcrypt('password123'),
        ]);
        // تسجيل دخول المستخدم وإنشاء توكن
        $token = $user->createToken('auth_token')->plainTextToken;
        // إضافة التوكن إلى رأس الطلب
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');
        // التحقق من أن الاستجابة ناجحة
        $response->assertStatus(200)
                ->assertJson(['message' => 'Logged out successfully']);
        // التحقق من أن التوكن تم حذفه
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);
    }
}
