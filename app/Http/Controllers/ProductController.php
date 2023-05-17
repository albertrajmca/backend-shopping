<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Services\ProductServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    /**
     * Constructor 
     *
     * @param ProductServiceInterface $productService
     */
    public function __construct(public ProductServiceInterface $productService){}

    /**
     * Method used to list out all the products
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function getAllProducts(Request $request): AnonymousResourceCollection
    {
        $products = $this->productService->getAllProducts($request);
        return ProductResource::collection($products);
    }

    /**
     * Method used to show the individual product
     *
     * @param int $id
     * @return ProductResource
     */
    public function getSingleProduct(int $id): ProductResource
    {
        $product = $this->productService->getSingleProduct($id);
        return ProductResource::make($product);
    }
}
