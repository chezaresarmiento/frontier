<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $service
    ) {}

    /**
 * @OA\Get(
 *     path="/api/products",
 *     tags={"Products"},
 *     summary="List all available products",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="List of products",
 *         @OA\JsonContent(type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer"),
 *                 @OA\Property(property="name", type="string"),
 *                 @OA\Property(property="price", type="number", format="float"),
 *                 @OA\Property(property="description", type="string")
 *             )
 *         )
 *     )
 * )
 */
    public function index(): JsonResponse
    {
        return response()->json(
            $this->service->getAll()
        );
    }

     /**
     * @OA\Post(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Add a new product",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","price"},
     *             @OA\Property(property="name", type="string", example="Laptop"),
     *             @OA\Property(property="price", type="number", format="float", example=999.99),
     *             @OA\Property(property="description", type="string", example="A lightweight and fast laptop")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="price", type="number"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="created_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $product = $this->service->addProduct(
            $request->name,
            $request->price,
            $request->description
        );

        return response()->json($product, 201);
    }
}