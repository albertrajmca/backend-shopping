<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\User;
use App\Services\ProductReviewServiceInterface;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class ProductReviewServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;
    /**
     * Mock product review service.
     *
     * @return void
     */
    public function test_mock_product_review_service_list_method()
    {
        $testData = "Review is submitted successfully";
        Product::factory()->count(10)->create();
        User::factory()->count(4)->create();
        $response = $this->postJson(route('token.store'),
            [
                'email' => User::pluck('email')->random(),
                'password' => 'password', // intentionally kept it in Users factory
            ]
        );
        $decodeResponse = json_decode($response->content(), true);
        $this->mock(ProductReviewServiceInterface::class, function (MockInterface $mock) use ($testData) {
            $mock
                ->shouldReceive('store')
                ->once()
                ->andReturn($testData);
        });

        $faker = Faker::create();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $decodeResponse['token'],
        ])
            ->postJson(route('products.review', Product::pluck('id')->random()),
                [
                    'title' => $faker->word,
                    'rating' => $faker->numberBetween(1, 5),
                    'comment' => $faker->sentence,
                ]);
    }
}
