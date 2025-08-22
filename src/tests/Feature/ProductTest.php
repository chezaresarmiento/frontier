<?php
namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\UserToken;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;




it('can fetch products with a valid token', function () {
    $user = User::factory()->create();

    $token = UserToken::create([
        'user_id' => $user->id,
        'token' => hash('sha256', Str::random(60)),
        'expires_at' => Carbon::now()->addHour(),
    ]);

    Product::factory()->count(2)->create();

    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Authorization' => 'Bearer ' . $token->token,
    ])->getJson('/api/v1.1/products');

    $response->assertOk();
    $response->assertJsonStructure([
        '*' => ['id', 'name', 'price', 'description', 'created_at', 'updated_at'],
    ]);

    expect($response->json())->toHaveCount(2);
});

it('rejects request without token', function () {
    $response = $this->getJson('/api/v1.1/products');

    $response->assertStatus(401);
    $response->assertJson([
        'error' => 'Token required',
    ]);
});