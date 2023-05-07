<?php

namespace App\Services;

use App\Repositories\CategoryRepository;

class CategoryService implements CategoryServiceInterface
{
    public function __construct(public CategoryRepository $categoryRepository){}

    public function listAllCategories()
    {
        return $this->categoryRepository->getAllCategories();
    }



}