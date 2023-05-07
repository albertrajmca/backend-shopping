<?php

namespace App\Services;

use App\Repositories\ProductReviewRepository;

class ProductReviewService implements ProductReviewServiceInterface
{
    public function __construct(public ProductReviewRepository $reviewRepository){}

    public function store($request)
    {
        $this->reviewRepository->store($request);
    }
}