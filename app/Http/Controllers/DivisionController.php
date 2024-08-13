<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DivisionController extends Controller
{
    //
    public function index(Request $request)
    {
        $name = $request->input('name');
        $perPage = $request->input('limit', 15);
        $page = $request->input('page', 1);
        $query = Division::query();

        if ($name) {
            $query->where('name', 'like', "%{$name}%");
        }

        $divisions = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'status' => 'success',
            'message' => $divisions->total() ? 'Data retrieved successfully' : 'No divisions found',
            'data' => [
                'divisions' => $divisions->items(),
            ],
            'pagination' => [
                'total' => $divisions->total(),
                'per_page' => $divisions->perPage(),
                'current_page' => $divisions->currentPage(),
                'last_page' => $divisions->lastPage(),
                'from' => $divisions->firstItem(),
                'to' => $divisions->lastItem(),
            ],
        ]);
    }

    public function show($id)
    {
        $division = Division::find($id);

        if (!$division) {
            return response()->json([
                'status' => 'error',
                'message' => 'Division not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Division retrieved successfully',
            'data' => $division,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $division = Division::find($id);

        if (!$division) {
            return response()->json([
                'status' => 'error',
                'message' => 'Division not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $division->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Division updated successfully',
            'data' => $division,
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);
            $division = Division::create([
                'name' => $validatedData['name'],
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Division created successfully',
                'data' => [
                    'division' => $division,
                ],
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function destroy($id)
    {
        $division = Division::find($id);

        if (!$division) {
            return response()->json([
                'status' => 'error',
                'message' => 'Division not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $division->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Division deleted successfully',
        ]);
    }
}
