<?php

namespace App\Repositories;

use App\DataTransferObjects\RatingDataDTO;
use App\Models\Review;
use Illuminate\Http\Request;

class ProductReviewRepository
{
    /**
     * Store the product review
     *
     * @param Request $data
     * @return void
     */
    public function postReviewForProduct(RatingDataDTO $ratingDataDTO): Review
    {
        $review = new Review();
        $review->user_id = request()->user()->id;
        $review->product_id = $ratingDataDTO->productId;
        $review->title = $ratingDataDTO->title;
        $review->rating = $ratingDataDTO->rating;
        $review->comment = $ratingDataDTO->comment;
        $review->save();
        return $review;
    }

}