<?php

namespace App\Services;

use App\Repositories\ProductRepository;

class ProductService implements ProductServiceInterface
{
    public function __construct(public ProductRepository $productRepository){}

    public function listAllProducts($request)
    {
        return $this->productRepository->getAllProducts($request);
    }

    public function showProduct($id)
    {
        return $this->productRepository->findById($id);
    }

}