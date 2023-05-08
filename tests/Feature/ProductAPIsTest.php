<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductAPIsTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    /**
     * Test the product list api structure.
     */
    public function test_products_list_api()
    {
        Product::factory()->count(6)->create();

        // 50 records were added while build the app
        $this->assertDatabaseCount('products', 56);

        $response = $this->getJson(route('products.list'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    "id",
                    "name",
                    "description",
                    "units",
                    "price",
                    "image",
                    "avg_rating",
                    "category" => [
                        "id",
                        "name",
                    ],
                ],
            ],
        ]);

        $data = $response->json('data');
        foreach ($data as $product) {
            $this->assertIsInt($product['id']);
            $this->assertIsString($product['name']);
            $this->assertIsString($product['description']);
            $this->assertIsInt($product['units']);
            $this->assertIsInt($product['price']);
            $this->assertIsString($product['image']);
            $this->assertIsString($product['avg_rating']);
            $this->assertIsInt($product['category']['id']);
            $this->assertIsString($product['category']['name']);
        }

    }

    /**
     * Product show api test
     *
     */
    public function test_products_show_api()
    {
        Product::factory()->count(10)->create();
        $this->assertDatabaseCount('products', 60);

        $response = $this->getJson(route('products.show', Product::pluck('id')->random()));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                "id",
                "name",
                "description",
                "units",
                "price",
                "image",
                "avg_rating",
                "reviews" => [
                    "*" => [
                        "id",
                        "comment",
                        "rating",
                        "title",
                        "user" => [
                            "id",
                            "name",
                        ],
                    ],
                ],
            ],
        ]);

        $data = $response->json('data');
        $this->assertIsInt($data['id']);
        $this->assertIsString($data['name']);
        $this->assertIsString($data['description']);
        $this->assertIsInt($data['units']);
        $this->assertIsInt($data['price']);
        $this->assertIsString($data['image']);
        $this->assertIsString($data['avg_rating']);

        foreach ($data['reviews'] as $review) {
            $this->assertIsInt($review['id']);
            $this->assertIsString($review['comment']);
            $this->assertIsInt($review['rating']);
            $this->assertIsString($review['title']);
            $this->assertIsInt($review['user']['id']);
            $this->assertIsString($review['user']['name']);
        }

    }

    /**
     * Product review api without auth token test
     *
     */
    public function test_product_review_api_without_auth_token()
    {
        Product::factory()->count(10)->create();
        $response = $this->postJson(route('products.review', Product::pluck('id')->random()), [], []);
        $response->assertStatus(401);
    }

    /**
     * Product review api without input data
     *
     */
    public function test_product_review_api_without_data()
    {
        User::factory()->count(4)->create();

        $response = $this->postJson(route('token.store'),
            [
                'email' => User::pluck('email')->random(),
                'password' => 'password', // intentionally kept it in Users factory
            ]
        );
        $decodeResponse = json_decode($response->content(), true);

        Product::factory()->count(10)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $decodeResponse['token'],
        ])->post(route('products.review', Product::pluck('id')->random()));
        $response->assertStatus(422);
    }

    /**
     * Product review api with invalid input data
     *
     */
    public function test_product_review_api_with_invalid_data()
    {
        User::factory()->count(4)->create();
        $response = $this->postJson(route('token.store'),
            [
                'email' => User::pluck('email')->random(),
                'password' => 'password', // intentionally kept it in Users factory
            ]
        );
        $decodeResponse = json_decode($response->content(), true);

        Product::factory()->count(10)->create();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $decodeResponse['token'],
        ])
            ->postJson(route('products.review', Product::pluck('id')->random()),
                [
                    'first_name' => 'Albert',
                ],
                []);
        $response->assertStatus(422);
    }

    /**
     * Product review api with proper input data
     *
     */
    public function test_product_review_api_valid_data()
    {
        User::factory()->count(4)->create();
        $randomUser = User::first();

        $response = $this->postJson(route('token.store'),
            [
                'email' => $randomUser->email,
                'password' => 'password', // intentionally kept it in Users factory
            ]
        );
        $decodeResponse = json_decode($response->content(), true);

        Product::factory()->count(10)->create();
        $randomProductId = Product::pluck('id')->random();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $decodeResponse['token'],
        ])
            ->postJson(route('products.review', $randomProductId),
                [
                    'title' => 'test title',
                    'rating' => 3,
                    'comment' => 'test comment',
                ]);
        $response->assertStatus(201);
        $response->assertJsonStructure(['msg']);
        $msg = $response->json('msg');
        $this->assertIsString($msg);
    }

}
