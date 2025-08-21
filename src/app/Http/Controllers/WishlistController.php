<?php

namespace App\Http\Controllers;

use App\Http\Requests\WishlistRequest;
use App\Services\WishlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct(
        private WishlistService $service
    ) {}

    /**
     * Get the authenticated user’s wishlist
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $wishlist = $this->service->getUserWishlist($user->id);

        return response()->json($wishlist);
    }

    /**
     * Add a product to the wishlist
     */
    public function store(WishlistRequest $request): JsonResponse
    {
         $user = $request->user();   
        $wishlist = $this->service->addProduct($user->id, $request->product_id);

        return response()->json($wishlist, 201);
    }

    /**
     * Remove a product from the wishlist
     */
    public function destroy(Request $request,int $productId): JsonResponse
    {
        // dd($productId);
        $user = $request->user();   
        $response=$this->service->removeProduct($user->id, $productId);

        if (!$response) {
            return response()->json(['error' => 'Product not found in wishlist'], 404);
        }

        return response()->json(['message' => 'Product removed from wishlist'], 200);
    }
}