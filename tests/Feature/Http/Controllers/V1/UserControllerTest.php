<?php

namespace Tests\Feature\Http\Controllers\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     *  Prueba para mostrar todos los usuarios.
     * @return void
     */
    public function test_all_users(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->getJson('/api/v1/users');
        $response->assertStatus(200);
    }

    /**
     * Prueba para almacenar un usuario.
     * @return void
     */
    public function test_store_user(): void
    {
        $user = User::factory()->make()->toArray();
        $response = $this->postJson('/api/v1/users', $user);
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => $user['password'],
        ]);
    }


    /**
     * Prueba para validar errores de 
     * datos incorrectos al almacenar un usuario.
     * @return void
     */
    public function test_store_user_with_invalid_data(): void
    {
        $user = User::factory()->make(['email' => 'invalid_email'])->toArray();
        $response = $this->postJson('/api/v1/users', $user);
        $response->assertStatus(422);
    }


    /**
     * Prueba para mostrar un usuario.
     * @return void
     */
    public function test_show_user(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        
        $response = $this->getJson("/api/v1/users/{$user->id}");
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }


    /**
     * Prueba para mostrar un usuario que no existe.
     * @return void
     */
    public function test_show_user_not_found(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->getJson('/api/v1/users/2');
        $response->assertStatus(404);
    }


    /**
     * Prueba para actualizar un usuario.
     * @return void
     */
    public function test_update_user(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->putJson("/api/v1/users/{$user->id}", $user->toArray());
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }


    /**
     * Prueba para validar errores al actualizar un usuario que no existe.
     * @return void
     */
    public function test_update_user_not_found(): void
    {
        $user = User::factory()->make();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->putJson('/api/v1/users/1', $user->toArray());
        $response->assertStatus(404);
    }


    /**
     * Prueba para validar errores al actualizar un usuario.
     * @return void
     */
    public function test_update_user_with_invalid_data(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->putJson("/api/v1/users/{$user->id}", ['email' => 'invalid_email']);
        $response->assertStatus(422);
    }


    /**
     * Prueba para eliminar un usuario.
     * @return void
     */
    public function test_delete_user(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->deleteJson("/api/v1/users/{$user->id}");
        $response->assertStatus(204);
    }


    /**
     * Prueba para validar errores al eliminar un usuario que no existe.
     * @return void
     */
    public function test_delete_user_not_found(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->deleteJson('/api/v1/users/2');
        $response->assertStatus(404);
    }
}
