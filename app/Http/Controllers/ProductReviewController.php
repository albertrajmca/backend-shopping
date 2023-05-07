<?php

namespace App\Http\Controllers;

use App\Http\Requests\RatingStoreRequest;
use App\Services\ProductReviewServiceInterface;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{

    public function __construct(public ProductReviewServiceInterface $reviewService){}

    public function store(RatingStoreRequest $request)
    {
         $this->reviewService->store($request);
         return response(['msg' => 'Review is submitted successfully'], 201);
    }
}
