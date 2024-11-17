<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['store']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::all();
            return response()->json($users);
        } catch (\Exception $e) {
            Log::error("An error occurred: {$e->getMessage()}");
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $user = User::create($request->all());
            return response()->json($user, 201);
        } catch (\Exception $e) {
            Log::error("An error occurred: {$e->getMessage()}");
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = User::findOrFail($id);
            return $user;

        } catch (ModelNotFoundException $e) {
            Log::error("User not found: {$e->getMessage()}");
            return response()->json(['message' => 'User not found'], 404);
        } catch (\Exception $e) {
            Log::error("An error occurred: {$e->getMessage()}");
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update($request->all());
            return response()->json($user);
        } catch (ModelNotFoundException $e) {
            Log::error("User not found: {$e->getMessage()}");
            return response()->json(['message' => 'User not found'], 404);
        } catch (\Exception $e) {
            Log::error("An error occurred: {$e->getMessage()}");
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            Log::error("User not found: {$e->getMessage()}");
            return response()->json(['message' => 'User not found'], 404);
        } catch (\Exception $e) {
            Log::error("An error occurred: {$e->getMessage()}");
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }
}
