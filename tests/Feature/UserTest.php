<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    /** @test */
    public function an_authenticated_user_can_list_users()
    {
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'Other User',
            'email' => 'other@example.com',
            'password' => bcrypt('password'),
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->getJson('/api/users', [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    /** @test */
    public function an_authenticated_user_can_view_a_single_user()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->getJson("/api/users/{$user->id}", [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(200)
            ->assertJson(['name' => 'Test User']);
    }

    /** @test */
    public function an_authenticated_user_can_update_a_user()
    {
        $user = User::create([
            'name' => 'Original Name',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->putJson("/api/users/{$user->id}", [
            'name' => 'Updated Name'
        ], [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(200)
            ->assertJson(['name' => 'Updated Name']);

        $this->assertDatabaseHas('users', ['name' => 'Updated Name']);
    }

    /** @test */
    public function an_authenticated_user_can_delete_a_user()
    {
        $user = User::create([
            'name' => 'To Delete',
            'email' => 'delete@example.com',
            'password' => bcrypt('password'),
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->deleteJson("/api/users/{$user->id}", [], [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
