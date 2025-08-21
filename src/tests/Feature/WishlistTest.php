<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\UserToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class WishlistTest extends TestCase
{
    use RefreshDatabase; // ensures clean DB for each test

    private function actingWithToken(User $user)
    {
        $token = UserToken::create([
            'user_id'   => $user->id,
            'token'     => hash('sha256', Str::random(60)),
            'expires_at'=> Carbon::now()->addHour(),
        ]);

        return ['Authorization' => 'Bearer ' . $token->token];
    }

    public function test_add_and_delete_wishlist_item()
    {
        // Arrange
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $headers = $this->actingWithToken($user);

        // Add product to wishlist
        $addResponse = $this->withHeaders($headers)->postJson('/api/v1.1/wishlist', ['product_id' => $product->id]);

        // Assert adding
        $addResponse->assertCreated()->assertJsonFragment(['product_id' => $product->id]);

        // Assert if it appears in GET /wishlist
        $listResponse = $this->withHeaders($headers)->getJson('/api/v1.1/wishlist');
        $listResponse->assertOk();
        $this->assertCount(1, $listResponse->json());

        // Delete from wishlist
        $deleteResponse = $this->withHeaders($headers)
            ->deleteJson("/api/v1.1/wishlist/{$product->id}");

        // Assert delete
        $deleteResponse->assertStatus(200); // I changed to 200 for consistency, but we can revert to 204 if the standatd is to return no content

        // Assert if it's gone
        $listResponse = $this->withHeaders($headers)->getJson('/api/v1.1/wishlist');
        $listResponse->assertOk();
        $this->assertCount(0, $listResponse->json());
    }
}