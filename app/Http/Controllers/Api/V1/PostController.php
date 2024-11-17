<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $posts = Post::all();
            return response()->json($posts);
        } catch (\Exception $e) {
            Log::error("An error occurred: {$e->getMessage()}");
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        try {
            $post = Post::create($request->all());
            return response()->json($post, 201);
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
            $post = Post::findOrFail($id);
            return $post;
        } catch (ModelNotFoundException $e) {
            Log::error("Post not found: {$e->getMessage()}");
            return response()->json(['message' => 'Post not found'], 404);
        } catch (\Exception $e) {
            Log::error("An error occurred: {$e->getMessage()}");
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->update($request->all());
            return response()->json($post);
        } catch (ModelNotFoundException $e) {
            Log::error("Post not found: {$e->getMessage()}");
            return response()->json(['message' => 'Post not found'], 404);
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
            $post = Post::findOrFail($id);
            $post->delete();
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            Log::error("Post not found: {$e->getMessage()}");
            return response()->json(['message' => 'Post not found'], 404);
        } catch (\Exception $e) {
            Log::error("An error occurred: {$e->getMessage()}");
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }
}
