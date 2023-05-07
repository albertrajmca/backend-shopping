<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Services\CategoryServiceInterface;

/**
 * CategoryController class
 */
class CategoryController extends Controller
{

    public function __construct(public CategoryServiceInterface $categoryService){}
   
    /**
     * Method used to get all categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $categories = $this->categoryService->listAllCategories();
        return CategoryResource::collection($categories);
    }
}
