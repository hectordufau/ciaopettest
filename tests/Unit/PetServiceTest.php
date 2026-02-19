<?php

namespace Tests\Unit;

use App\Models\Pet;
use App\Models\User;
use App\Services\PetService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetServiceTest extends TestCase
{
    use RefreshDatabase;

    protected PetService $service;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->service = new PetService();
        $this->user = User::factory()->create();
        
        $this->actingAs($this->user, 'api');
    }

    public function test_can_create_pet(): void
    {
        $data = [
            'name' => 'Buddy',
            'species' => 'Cachorro',
            'breed' => 'Golden Retriever',
            'gender' => 'Macho',
            'weight' => 30.5,
        ];

        $pet = $this->service->create($data);

        $this->assertInstanceOf(Pet::class, $pet);
        $this->assertEquals('Buddy', $pet->name);
        $this->assertEquals('Cachorro', $pet->species);
    }

    public function test_can_get_all_pets(): void
    {
        Pet::factory()->count(5)->create(['user_id' => $this->user->id]);

        $pets = $this->service->getAllPaginated();

        $this->assertEquals(5, $pets->total());
    }

    public function test_can_get_pet_by_id(): void
    {
        $pet = Pet::factory()->create(['user_id' => $this->user->id]);

        $foundPet = $this->service->getById($pet->id);

        $this->assertEquals($pet->id, $foundPet->id);
    }

    public function test_can_update_pet(): void
    {
        $pet = Pet::factory()->create(['user_id' => $this->user->id]);

        $updatedPet = $this->service->update($pet->id, ['name' => 'Max']);

        $this->assertEquals('Max', $updatedPet->name);
    }

    public function test_can_delete_pet(): void
    {
        $pet = Pet::factory()->create(['user_id' => $this->user->id]);

        $this->service->delete($pet->id);

        $this->assertDatabaseMissing('pets', ['id' => $pet->id]);
    }

    public function test_can_search_pets(): void
    {
        Pet::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Buddy',
            'species' => 'Cachorro',
        ]);
        
        Pet::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Whiskers',
            'species' => 'Gato',
        ]);

        $results = $this->service->search('Buddy');

        $this->assertEquals(1, $results->total());
    }

    public function test_user_can_only_see_own_pets(): void
    {
        $otherUser = User::factory()->create();
        
        Pet::factory()->count(3)->create(['user_id' => $this->user->id]);
        Pet::factory()->count(2)->create(['user_id' => $otherUser->id]);

        $pets = $this->service->getAllPaginated();

        $this->assertEquals(3, $pets->total());
    }
}
