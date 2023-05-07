<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Eloquent\Collection;
use DB;

class ProductReviewRepository
{

    public function store($data)
    {
        $review = new Review();
        $review->user_id = $data->user()->id;
        $review->product_id = $data->id;
        $review->title = $data->title;
        $review->rating = $data->rating;
        $review->comment = $data->comment;
        $review->save();
    }

}