<?php

namespace App\Repositories;

use App\Models\Wishlist;
use Illuminate\Support\Collection;

class WishlistRepository
{
    public function add(int $userId, int $productId): Wishlist
    {
        $response = Wishlist::create([
            'user_id'    => $userId,
            'product_id' => $productId,
        ]);

        return $response;
    }

    public function remove(int $userId, int $productId): bool
    {
        return (bool) Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->delete();
    }

    public function getByUser(int $userId): Collection
    {
        return Wishlist::with('product')
            ->where('user_id', $userId)
            ->get();
    }

    public function exists(int $userId, int $productId): bool
    {
        return Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();
    }
}
