<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PetResource;
use App\Services\PetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PetController extends Controller
{
    public function __construct(
        protected PetService $petService
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->query('per_page', 15);
        $pets = $this->petService->getAllPaginated($perPage);

        return PetResource::collection($pets);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:100',
            'breed' => 'nullable|string|max:100',
            'gender' => 'nullable|in:Macho,Fêmea,Desconhecido',
            'birth_date' => 'nullable|date|before_or_equal:today',
            'weight' => 'nullable|numeric|min:0|max:999.99',
            'microchip_number' => 'nullable|string|max:50|unique:pets,microchip_number',
            'notes' => 'nullable|string',
        ]);

        $pet = $this->petService->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Pet criado com sucesso.',
            'data' => new PetResource($pet),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $pet = $this->petService->getById($id);

        return response()->json([
            'success' => true,
            'data' => new PetResource($pet),
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'species' => 'sometimes|required|string|max:100',
            'breed' => 'nullable|string|max:100',
            'gender' => 'nullable|in:Macho,Fêmea,Desconhecido',
            'birth_date' => 'nullable|date|before_or_equal:today',
            'weight' => 'nullable|numeric|min:0|max:999.99',
            'microchip_number' => 'nullable|string|max:50|unique:pets,microchip_number,' . $id,
            'notes' => 'nullable|string',
        ]);

        $pet = $this->petService->update($id, $data);

        return response()->json([
            'success' => true,
            'message' => 'Pet atualizado com sucesso.',
            'data' => new PetResource($pet),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->petService->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Pet excluído com sucesso.',
        ]);
    }

    public function search(Request $request): AnonymousResourceCollection
    {
        $searchTerm = $request->query('q', '');
        $perPage = $request->query('per_page', 15);
        
        $pets = $this->petService->search($searchTerm, $perPage);

        return PetResource::collection($pets);
    }
}
