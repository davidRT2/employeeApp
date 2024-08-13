<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class EmployeeController extends Controller
{
    //
    /**
     * Display a listing of the employees.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Employee::with('division');

        $name = $request->input('name');
        $divisionId = $request->input('division_id');

        if (!empty($name)) {
            $query->where('name', 'like', '%' . $name . '%');
        }
        if (!empty($divisionId)) {
            $query->where('division_id', $divisionId);
        }

        $perPage = $request->input('limit', 10);
        $employees = $query->paginate($perPage);

        if ($employees->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'No employees found',
                'data' => [
                    'employees' => [],
                ],
                'pagination' => [
                    'total' => 0,
                    'per_page' => $employees->perPage(),
                    'current_page' => $employees->currentPage(),
                    'last_page' => $employees->lastPage(),
                    'from' => null,
                    'to' => null,
                ],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Employees fetched successfully',
            'data' => [
                'employees' => $employees->items(),
            ],
            'pagination' => [
                'total' => $employees->total(),
                'per_page' => $employees->perPage(),
                'current_page' => $employees->currentPage(),
                'last_page' => $employees->lastPage(),
                'from' => $employees->firstItem(),
                'to' => $employees->lastItem(),
            ],
        ]);
    }



    /**
     * Store a newly created employee in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'image' => 'required|url',
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'division' => 'required|exists:divisions,id',
                'position' => 'required|string|max:255',
            ]);

            Employee::create([
                'image' => $validatedData['image'],
                'name' => $validatedData['name'],
                'phone' => $validatedData['phone'],
                'division_id' => $validatedData['division'],
                'position' => $validatedData['position'],
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Employee created successfully',
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Update the specified employee in storage.
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $employee = Employee::findOrFail($id);

            $validatedData = $request->validate([
                'image' => 'required|url',
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'division' => 'required|exists:divisions,id',
                'position' => 'required|string|max:255',
            ]);
            $employee->update([
                'image' => $validatedData['image'],
                'name' => $validatedData['name'],
                'phone' => $validatedData['phone'],
                'division_id' => $validatedData['division'],
                'position' => $validatedData['position'],
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Employee updated successfully',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Employee not found',
            ], Response::HTTP_NOT_FOUND);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the employee',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified employee from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Employee deleted successfully',
        ]);
    }
}
