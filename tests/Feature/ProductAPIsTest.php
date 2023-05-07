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
            'data' =>  [
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
                            "name"
                        ],
                    ],
            ],
        ]);
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
            'data' =>  [
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
                        ]
                    ]
                ],
            ],
        ]);
    }

    /**
     * Product review api without auth token test
     *
     */
    public function test_product_review_api_without_auth_token()
    {
        Product::factory()->count(10)->create();
        $response = $this->postJson(route('products.review', Product::pluck('id')->random()), [],[]);
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
                'password' => 'password' // intentionally kept it in Users factory
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
                'password' => 'password' // intentionally kept it in Users factory
            ]
        );
        $decodeResponse = json_decode($response->content(), true);

        Product::factory()->count(10)->create();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $decodeResponse['token'],
        ])
        ->postJson(route('products.review', Product::pluck('id')->random()), 
        [
            'first_name' => 'Albert'
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
        $response = $this->postJson(route('token.store'),
            [
                'email' => User::pluck('email')->random(),
                'password' => 'password' // intentionally kept it in Users factory
            ]
        );
        $decodeResponse = json_decode($response->content(), true);

        Product::factory()->count(10)->create();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $decodeResponse['token'],
        ])
        ->postJson(route('products.review', Product::pluck('id')->random()), 
        [
            'title' => 'title',
            'rating' => 3,
            'comment' => 'test comment'
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure(['msg']);
    }

}

