<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use App\Repositories\WishlistRepository;
use App\Services\WishlistService;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class WishlistServiceTest extends TestCase
{
    private WishlistRepository|MockObject $repository;
    private WishlistService $service;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the repository
        $this->repository = $this->createMock(WishlistRepository::class);
        $this->service    = new WishlistService($this->repository);
    }

    public function test_add_product_when_not_in_wishlist()
    {
        // properties added to fix PHPStan error

        /** @var \App\Models\Product $product */
        $product = Product::factory()->create();


        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        /** @var \App\Models\Wishlist $wishlist */
        $wishlist = Wishlist::create([
            'user_id'    => $user->id,
            'product_id' => $product->id,
        ]);

        // Assert
        $this->assertInstanceOf(Wishlist::class, $wishlist);
        $this->assertEquals($user->id, $wishlist->user_id);
        $this->assertEquals($product->id, $wishlist->product_id);

        // remove product from wishlist
        $deleted = $wishlist->delete();

        // Assert deleted
        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('wishlists', [
            'id'         => $wishlist->id,
            'user_id'    => $user->id,
            'product_id' => $product->id,
        ]);

    }

    public function test_add_product_throws_exception_if_already_exists()
    {
        // Arrange
        $this->repository->method('exists')->willReturn(true);

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Product already in wishlist');

        // Act
        $this->service->addProduct(1, 10);
    }
}
