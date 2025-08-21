<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Support\Collection;
use App\Models\Product;

class ProductService
{
    public function __construct(
        private ProductRepository $repository
    ) {}

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