<?php

namespace App;

/**
 * @OA\Info(
 *     title="Frontier Swagger v1.1",
 *     version="1.0.0",
 *     description="A dedicated documentation for Frontier Dental simple Laravel API for Wishlist management (register, login, products, wishlist)."
 * )
 * @OA\Server(
 *     url="http://localhost:9000",
 *     description="Local development server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="Custome Token",
 *     description="Paste your API token here (without 'Bearer ')"
 * )
 */
class SwaggerDoc
{
    // empty class for Swagger annotations
}
