<?php

use App\Models\User;
use App\Models\Product;
use App\Models\UserToken;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

it('can fetch products with a valid token', function () {
    // Arrange: create a user + token
    $user = User::factory()->create();
    $token = UserToken::create([
        'user_id' => $user->id,
        'token' => hash('sha256', Str::random(60)),
        'expires_at' => Carbon::now()->addHour(),
    ]);

    // Seed some products
    Product::factory()->count(2)->create([
        'price' => 123.45,
    ]);

    // Act: call the endpoint with Authorization header
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token->token,
    ])->getJson('/api/products');

    // Assert
    $response->assertOk();
    $response->assertJsonStructure([
        '*' => ['id', 'name', 'price', 'description', 'created_at', 'updated_at'],
    ]);

    expect($response->json())->toHaveCount(2);
});

it('rejects request without token', function () {
    $response = $this->getJson('/api/products');

    $response->assertStatus(401);
    $response->assertJson([
        'error' => 'Token required',
    ]);
});