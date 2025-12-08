<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApartmentController extends Controller
{
    /**
     * Display a listing of apartments.
     * GET /api/apartments
     */
    public function index(): JsonResponse
    {
        $apartments = Apartment::all();
        return response()->json($apartments);
    }

    /**
     * Store a newly created apartment.
     * POST /api/apartments
     */
    public function store(Request $request): JsonResponse
    {
        // Server-side validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'layout' => 'required|string|max:50',
            'area' => 'required|numeric|min:10|max:500',
            'floor' => 'required|integer|min:1|max:20',
            'price' => 'required|numeric|min:1000',
            'status' => 'required|in:available,reserved,sold',
        ]);

        $apartment = Apartment::create($validated);

        return response()->json([
            'message' => 'Byt bol úspešne vytvorený',
            'apartment' => $apartment
        ], 201);
    }

    /**
     * Display the specified apartment.
     * GET /api/apartments/{id}
     */
    public function show(string $id): JsonResponse
    {
        $apartment = Apartment::findOrFail($id);
        return response()->json($apartment);
    }

    /**
     * Update the specified apartment.
     * PUT /api/apartments/{id}
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $apartment = Apartment::findOrFail($id);

        // Server-side validation
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'layout' => 'sometimes|required|string|max:50',
            'area' => 'sometimes|required|numeric|min:10|max:500',
            'floor' => 'sometimes|required|integer|min:1|max:20',
            'price' => 'sometimes|required|numeric|min:1000',
            'status' => 'sometimes|required|in:available,reserved,sold',
        ]);

        $apartment->update($validated);

        return response()->json([
            'message' => 'Byt bol úspešne aktualizovaný',
            'apartment' => $apartment
        ]);
    }

    /**
     * Remove the specified apartment.
     * DELETE /api/apartments/{id}
     */
    public function destroy(string $id): JsonResponse
    {
        $apartment = Apartment::findOrFail($id);
        $apartment->delete();

        return response()->json([
            'message' => 'Byt bol úspešne vymazaný'
        ]);
    }
}
