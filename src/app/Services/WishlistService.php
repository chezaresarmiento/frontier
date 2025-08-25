<?php

namespace App\Services;

use App\Models\Wishlist;
use App\Repositories\WishlistRepository;
use Illuminate\Database\Eloquent\Collection;

class WishlistService
{
    public function __construct(private WishlistRepository $repository)
    {
    }

    public function addProduct(int $userId, int $productId): Wishlist|string
    {
        if ($this->repository->exists($userId, $productId)) {
            throw new \App\Exceptions\DuplicateWishlistItemException('Product already in wishlist');
        }

        return $this->repository->add($userId, $productId);
    }

    /** @return Collection<int, Wishlist> */
    public function getUserWishlist(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repository->getByUser($userId);
    }

    public function removeProduct(int $userId, int $productId): bool
    {
        return $this->repository->remove($userId, $productId);
    }
}
