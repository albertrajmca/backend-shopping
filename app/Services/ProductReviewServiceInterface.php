<?php

namespace App\Services;

use App\DataTransferObjects\RatingDataDTO;

interface ProductReviewServiceInterface
{
    public function postReviewForProduct(RatingDataDTO $ratingDataDTO): void;
}