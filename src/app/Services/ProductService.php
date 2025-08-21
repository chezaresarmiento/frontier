<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Support\Collection;

class ProductService
{
    public function __construct(
        private ProductRepository $repository
    ) {}

    public function getAll(): Collection
    {
        return $this->repository->all();
    }
}