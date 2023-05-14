<?php

namespace App\Services;

use App\DataTransferObjects\RatingDataDTO;
use App\Exceptions\ModelNotCreatedException;
use App\Repositories\ProductReviewRepository;
use Exception;
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
    public function postReviewForProduct(RatingDataDTO $ratingDataDTO): void
    {
        // try {
            $this->reviewRepository->postReviewForProduct($ratingDataDTO);
        // } catch (Exception $e) {
        //     throw new ModelNotCreatedException($e->getCode(), $e->getMessage());
        // }
    }
}
