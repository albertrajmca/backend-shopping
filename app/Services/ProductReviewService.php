<?php

namespace App\Services;

use App\Repositories\ProductReviewRepository;
use Illuminate\Http\Request;

class ProductReviewService implements ProductReviewServiceInterface
{
    /**
     * Constructor
     *
     * @param ProductReviewRepository $reviewRepository
     */
    public function __construct(public ProductReviewRepository $reviewRepository)
    {}

    /**
     * Product review store
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request): void
    {
        $this->reviewRepository->store($request);
    }
}
