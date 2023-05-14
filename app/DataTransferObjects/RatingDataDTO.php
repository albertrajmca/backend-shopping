<?php

namespace App\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

final class RatingDataDTO extends DataTransferObject
{
    public string $title;

    public int $rating;

    public string $comment;

    public int $productId;
}