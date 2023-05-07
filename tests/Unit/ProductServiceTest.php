<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Services\ProductServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;
    /**
     * Mock product service.
     *
     * @return void
     */
    public function test_mock_product_service_list_method()
    {
        $this->mock(ProductServiceInterface::class, function(MockInterface $mock){
            $mock
            ->shouldReceive('listAllProducts')
            ->once()
            ->andReturn("somedata");
        });
       $this->getJson(route('products.list'));
    }

    /**
     * Mock product service.
     *
     * @return void
     */
    public function test_mock_product_service_show_method()
    {
        Product::factory()->count(10)->create();
        $this->mock(ProductServiceInterface::class, function(MockInterface $mock){
            $mock
            ->shouldReceive('showProduct')
            ->once()
            ->andReturn("somedata");
        });
        $this->getJson(route('products.show', Product::pluck('id')->random()));
    }
}
