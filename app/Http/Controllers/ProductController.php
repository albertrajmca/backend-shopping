<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Services\ProductServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * ProductController class
 */
class ProductController extends Controller
{
    public function __construct(public ProductServiceInterface $productService){}

    /**
     * Method used to list out all the products
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $products = $this->productService->listAllProducts($request);
        return ProductResource::collection($products);
    }

    /**
     * Method used to show the individual product
     *
     * @param integer $id
     * @return ProductResource
     */
    public function show(int $id): ProductResource
    {
        $product = $this->productService->showProduct($id);
        return ProductResource::make($product);
    }
}
