<?php

namespace Tests\Feature;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->token = auth('api')->login($this->user);
    }

    public function test_user_can_create_pet(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->postJson('/api/pets', [
                'name' => 'Buddy',
                'species' => 'Cachorro',
                'breed' => 'Golden Retriever',
                'gender' => 'Macho',
                'birth_date' => '2022-05-10',
                'weight' => 30.5,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Buddy')
            ->assertJsonPath('data.species', 'Cachorro');
    }

    public function test_user_can_list_own_pets(): void
    {
        Pet::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/pets');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_user_can_view_own_pet(): void
    {
        $pet = Pet::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson("/api/pets/{$pet->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.name', $pet->name);
    }

    public function test_user_cannot_view_other_users_pet(): void
    {
        $otherUser = User::factory()->create();
        $pet = Pet::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson("/api/pets/{$pet->id}");

        $response->assertStatus(404);
    }

    public function test_user_can_update_own_pet(): void
    {
        $pet = Pet::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->putJson("/api/pets/{$pet->id}", [
                'name' => 'Max',
                'species' => 'Cachorro',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Max');
    }

    public function test_user_can_delete_own_pet(): void
    {
        $pet = Pet::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->deleteJson("/api/pets/{$pet->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('pets', ['id' => $pet->id]);
    }

    public function test_microchip_must_be_unique(): void
    {
        Pet::factory()->create([
            'user_id' => $this->user->id,
            'microchip_number' => '123456789012',
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->postJson('/api/pets', [
                'name' => 'Rex',
                'species' => 'Cachorro',
                'microchip_number' => '123456789012',
            ]);

        $response->assertStatus(422);
    }

    public function test_unauthenticated_user_cannot_access_pets(): void
    {
        $response = $this->getJson('/api/pets');

        $response->assertStatus(401);
    }
}
