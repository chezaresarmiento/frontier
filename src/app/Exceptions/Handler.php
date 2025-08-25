<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e)
    {
        if ($e instanceof DuplicateWishlistItemException) {
            return response()->json([
                'error' => [
                    'message' => 'Product is already in the wishlist',
                    'code'    => 'WISHLIST_DUPLICATED',
                ],
            ], 422);
        }

        return parent::render($request, $e);
    }
}
