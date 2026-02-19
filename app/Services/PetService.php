<?php

namespace App\Services;

use App\Models\Pet;
use Illuminate\Pagination\LengthAwarePaginator;

class PetService
{
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Pet::with('user')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getById(int $id): Pet
    {
        return Pet::where('user_id', auth()->id())
            ->findOrFail($id);
    }

    public function create(array $data): Pet
    {
        $data['user_id'] = auth()->id();
        
        return Pet::create($data);
    }

    public function update(int $id, array $data): Pet
    {
        $pet = $this->getById($id);
        $pet->update($data);
        
        return $pet->fresh();
    }

    public function delete(int $id): void
    {
        $pet = $this->getById($id);
        $pet->delete();
    }

    public function search(string $searchTerm, int $perPage = 15): LengthAwarePaginator
    {
        return Pet::with('user')
            ->where('user_id', auth()->id())
            ->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('species', 'like', "%{$searchTerm}%")
                    ->orWhere('breed', 'like', "%{$searchTerm}%")
                    ->orWhere('microchip_number', 'like', "%{$searchTerm}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
