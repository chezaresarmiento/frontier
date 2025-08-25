<?php

namespace App\Http\Controllers\Api\V1_1;

use App\Http\Requests\WishlistRequest;
use App\Services\WishlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct(
        private WishlistService $service
    ) {
    }

    /**
 * @OA\Get(
 *     path="/api/v1.1/wishlist",
 *     tags={"Wishlist"},
 *     summary="Retrieve the current user's wishlist",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Wishlist items",
 *         @OA\JsonContent(type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer"),
 *                 @OA\Property(property="user_id", type="integer"),
 *                 @OA\Property(property="product_id", type="integer")
 *             )
 *         )
 *     )
 * )
 */

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $wishlist = $this->service->getUserWishlist($user->id);

        return response()->json($wishlist);
    }

    /**
 * @OA\Post(
 *     path="/api/v1.1/wishlist",
 *     tags={"Wishlist"},
 *     summary="Add a product to the user's wishlist",
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"product_id"},
 *             @OA\Property(property="product_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Product added to wishlist",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="user_id", type="integer"),
 *             @OA\Property(property="product_id", type="integer")
 *         )
 *     ),
 *     @OA\Response(response=400, description="Product already in wishlist")
 * )
 */
    public function store(WishlistRequest $request): JsonResponse
    {
        $user     = $request->user();
        try{
            $wishlist = $this->service->addProduct($user->id, $request->product_id);
            return response()->json($wishlist, 201);
        }catch (\Exception $e){
            return response()->json(['error' => 'Product already in wishlist'], 402);
        }
    
        

        
    }

    /**
     * @OA\Delete(
     *     path="/api/v1.1/wishlist/{product_id}",
     *     tags={"Wishlist"},
     *     summary="Remove a product from the user's wishlist",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="product_id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to remove",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Product removed successfully"),
     *     @OA\Response(response=404, description="Product not found in wishlist")
     * )
     */
    public function destroy(Request $request, int $productId): JsonResponse
    {
        // dd($productId);
        $user     = $request->user();
        $response = $this->service->removeProduct($user->id, $productId);

        if (!$response) {
            return response()->json(['error' => 'Product not found in wishlist'], 404);
        }

        return response()->json(['message' => 'Product removed from wishlist'], 200);
    }
}
