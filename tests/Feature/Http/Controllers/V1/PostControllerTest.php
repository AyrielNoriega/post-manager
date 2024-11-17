<?php

namespace Tests\Feature\Http\Controllers\V1;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prueba para mostrar todos los posts.
     * @return void
     */
    public function test_all_posts(): void
    {
        User::factory()->create();
        Post::factory()->count(5)->create();
        $response = $this->getJson('/api/v1/posts');
        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonCount(5);
    }


    /**
     * Prueba para crear un post.
     * @return void
     */
    public function test_store_post(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $post = Post::factory()->create();
        $response = $this->postJson('/api/v1/posts', $post->toArray());
        $response->assertStatus(201);
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonFragment([
            'title' => $post['title'],
            'content' => $post['content'],
            'user_id' => $post['user_id'],
        ]);
    }

    /**
     * Prueba para validar errores al crear un post.
     * @return void
     *
     */
    public function test_store_post_validation_error(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->postJson('/api/v1/posts', []);
        $response->assertStatus(422);
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonValidationErrors(['title', 'content', 'user_id']);
    }


    /**
     * Prueba para mostrar un post.
     * @return void
     */
    public function test_show_post(): void
    {
        User::factory()->create();
        $post = Post::factory()->create();
        $response = $this->getJson("/api/v1/posts/{$post->id}");
        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
        $response->assertJson($post->toArray());
    }


    /**
     * Prueba para validar errores al mostrar un post que no existe.
     * @return void
     */
    public function test_show_post_not_found(): void
    {
        User::factory()->create();
        $response = $this->getJson('/api/v1/posts/1');
        $response->assertStatus(404);
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonFragment([
            'message' => 'Post not found',
        ]);
    }


    /**
     * Prueba para actualizar un post.
     * @return void
     */
    public function test_update_post(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $post = Post::factory()->create();
        $response = $this->putJson("/api/v1/posts/{$post->id}", $post->toArray());
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonFragment([
            'title' => $post['title'],
            'content' => $post['content'],
            'user_id' => $post['user_id'],
        ]);
    }


    /**
     * Prueba para validar errores al actualizar un post que no existe.
     * @return void
     */
    public function test_update_post_not_found(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->putJson('/api/v1/posts/1', []);
        $response->assertStatus(404);
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonFragment([
            'message' => 'Post not found',
        ]);
    }


    /**
     * Prueba para validar errores al actualizar un post.
     * @return void
     */
    // public function test_update_post_validation_error(): void
    // {
    //     User::factory()->create();
    //     $post = Post::factory()->create();
    //     $response = $this->putJson("/api/v1/posts/{$post->id}", ['title_error' => '', 'content' => '', 'user_id' => '']);
    //     $response->assertStatus(422);
    //     $response->assertHeader('content-type', 'application/json');
    //     $response->assertJsonValidationErrors(['title', 'content', 'user_id']);
    // }


    /**
     * Prueba para eliminar un post.
     * @return void
     */
    public function test_delete_post(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $post = Post::factory()->create();
        $response = $this->deleteJson("/api/v1/posts/{$post->id}");
        $response->assertStatus(204);
    }


    /**
     * Prueba para validar errores al eliminar un post que no existe.
     * @return void
     */
    public function test_delete_post_not_found(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->deleteJson('/api/v1/posts/1');
        $response->assertStatus(404);
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonFragment([
            'message' => 'Post not found',
        ]);
    }
}


