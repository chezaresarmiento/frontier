<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\WishlistService;
use App\Repositories\WishlistRepository;
use PHPUnit\Framework\MockObject\MockObject;
use  \App\Models\Product;
use \App\Models\User;
use \App\Models\Wishlist;

class WishlistServiceTest extends TestCase
{
    private WishlistRepository|MockObject $repository;
    private WishlistService $service;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the repository
        $this->repository = $this->createMock(WishlistRepository::class);
        $this->service = new WishlistService($this->repository);
    }

    public function test_add_product_when_not_in_wishlist()
    {
        // Arrange
        $product = Product::factory()->create();
        $user = User::factory()->create();

        $wishlist = Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        // Assert
        $this->assertInstanceOf(Wishlist::class, $wishlist);
        $this->assertEquals($user->id, $wishlist->user_id);
        $this->assertEquals($product->id, $wishlist->product_id);
        
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