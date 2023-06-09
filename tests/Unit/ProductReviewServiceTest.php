<?php

namespace Tests\Unit;

use App\DataTransferObjects\RatingDataDTO;
use App\Exceptions\ModelNotCreatedException;
use App\Models\Product;
use App\Models\User;
use App\Repositories\ProductReviewRepository;
use App\Services\ProductReviewServiceInterface;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use PDOException;
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
        $randomUser = User::first();

        $this->mock(ProductReviewServiceInterface::class, function (MockInterface $mock) use ($testData) {
            $mock
                ->shouldReceive('postReviewForProduct')
                ->once()
                ->andReturn($testData);
        });

        $faker = Faker::create();
        $this->actingAs($randomUser)
            ->postJson(route('products.review', Product::pluck('id')->random()),
                [
                    'title' => $faker->realText(10),
                    'rating' => $faker->numberBetween(1, 5),
                    'comment' => $faker->sentence,
                ]);
    }

    /**
     * Mock the model not created exception
     *
     * @return void
     */
    public function test_mock_product_review_service_exception()
    {
        Product::factory()->count(10)->create();
        $exception = new ModelNotCreatedException();
        User::factory()->count(4)->create();
        $randomUser = User::first();

        $this->mock(ProductReviewServiceInterface::class, function (MockInterface $mock) use ($exception) {
            $mock
                ->shouldReceive('postReviewForProduct')
                ->once()
                ->andThrow($exception);
        });

        $faker = Faker::create();
        $response = $this->actingAs($randomUser)
            ->postJson(route('products.review', Product::pluck('id')->random()),
                [
                    'title' => $faker->realText(10),
                    'rating' => $faker->numberBetween(1, 5),
                    'comment' => $faker->sentence,
                ]);

        $response->assertStatus(409);
        $msg = $response->json('message');
        $this->assertIsString($msg);
        $response->assertSee("Product review not created");
    }
}
