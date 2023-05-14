<?php

namespace App\Http\Controllers;

use App\Http\Requests\RatingDataValidation;
use App\Services\ProductReviewServiceInterface;
use Illuminate\Http\Response;

class ProductReviewController extends Controller
{

    /**
     * Constructor
     *
     * @param ProductReviewServiceInterface $reviewService
     */
    public function __construct(public ProductReviewServiceInterface $reviewService)
    {}

     /**
      * Store the Review
      *
      * @param RatingDataValidation $request
      * @return Response
      */
    public function postReviewForProduct(RatingDataValidation $request): Response
    {
        $this->reviewService->postReviewForProduct($request->data());
        return response(['msg' => 'Review is submitted successfully'], 201);
    }
}
