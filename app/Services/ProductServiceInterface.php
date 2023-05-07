<?php

namespace App\Services;

interface ProductServiceInterface
{
    public function listAllProducts($request);
    public function showProduct($id);
}