<?php

namespace App\Services;

use App\Repositories\WishlistRepository;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Collection;

class WishlistService
{
    public function __construct(private WishlistRepository $repository) {}

    public function addProduct(int $userId, int $productId): Wishlist|string
    {
        if ($this->repository->exists($userId, $productId)) {
            return 'Product already in wishlist';
        }

        return $this->repository->add($userId, $productId);
    }

    public function getUserWishlist(int $userId): Collection
    {
        return $this->repository->getByUser($userId);
    }

    public function removeProduct(int $userId, int $productId): bool
    {
        return $this->repository->remove($userId, $productId);
    }
}