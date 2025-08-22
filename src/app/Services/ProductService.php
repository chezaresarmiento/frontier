<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Support\Collection;

class ProductService
{
    public function __construct(
        private ProductRepository $repository
    ) {
    }

    public function getAll(): Collection
    {
        return $this->repository->all();
    }

    public function addProduct(string $name, float $price, ?string $description = null): Product
    {
        return $this->repository->create([
            'name'        => $name,
            'price'       => $price,
            'description' => $description,
        ]);
    }


}
