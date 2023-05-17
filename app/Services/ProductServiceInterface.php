<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductServiceInterface
{
    public function getAllProducts(Request $request): Collection;
    public function getSingleProduct(int $id): Product;
}
