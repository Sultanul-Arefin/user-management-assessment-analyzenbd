<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_count_by_created_by(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        User::factory()
            ->count(10)
            ->create(['created_by' => $user->id]);
        $response = $this->get('/api/v1/user');
        $response->assertOk();
        $response->assertJsonCount(10, 'data');
    }

    /**
     * @test
     */
    public function itListsAllUsersInPaginateWay(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        User::factory()
            ->count(1)
            ->create(['created_by' => $user->id]);
        $response = $this->get('/api/v1/user');
        $response->assertOk();
        $this->assertNotNull($response->json('data')[0]['id']);
        $this->assertNotNull($response->json('meta'));
        $this->assertNotNull($response->json('links'));
    }

    /**
     * @test
     */
    public function itChecksThatUserCanBeStored(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->post(route('user.store'), [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'address' => 'Dhaka',
        ]);
        $response->assertSessionHasNoErrors()->assertStatus(201);
        $this->assertEquals(
            'User Created Successfully',
            $response->json('message')
        );
        $this->assertEquals(
            'Test User', 
            $response->json('data.name')
        );
        $this->assertEquals(
            'test@test.com',
            $response->json('data.email')
        );
        $this->assertDatabaseHas(
            'users', ['email' => 'test@test.com']
        );
    }

    /**
     * @test
     */
    public function itChecksThatUserCanBeUpdated(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->put(route('user.update', $user->id), [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'address' => 'Dhaka',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertOk();
        $this->assertEquals(
            'Test User', 
            $response->json('data.name')
        );
        $this->assertEquals(
            'test@test.com',
            $response->json('data.email')
        );
    }

    /**
     * @test
     */
    public function itChecksThatUserCanBeDeleted(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $userForDeleting = User::factory()->create();
        $this->delete(route('user.destroy', $userForDeleting->id));
        $this->assertSoftDeleted('users', [
            'id' => $userForDeleting->id,
            'email' => $userForDeleting->email
        ]);
    }
}
