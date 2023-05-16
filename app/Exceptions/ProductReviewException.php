<?php

namespace App\Exceptions;

use Exception;
use PDOException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ProductReviewException
{
    public static function handle(Exception $e): JsonResponse
    {
        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'Product review not found',
            ], Response::HTTP_BAD_REQUEST);
        } else if ($e instanceof PDOException) {
            return response()->json([
                'message' => 'Database error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } else if ($e instanceof QueryException) {
            return response()->json([
                'message' => 'Query error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } else if ($e instanceof ModelNotCreatedException) {
            return response()->json([
                'message' => 'Product review not created',
            ], Response::HTTP_CONFLICT);
        } else {
            return response()->json([
                'message' => 'Unknown error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}