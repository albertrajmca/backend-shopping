<?php

namespace Tests\Unit;

use App\Services\CategoryServiceInterface;
use Mockery\MockInterface;
use Tests\TestCase;

class CategoryServiceTest extends TestCase
{
    /**
     * Mock category service.
     *
     * @return void
     */
    public function test_mock_category_servie()
    {
        $this->mock(CategoryServiceInterface::class, function(MockInterface $mock){
            $mock
            ->shouldReceive('listAllCategories')
            ->once()
            ->andReturn("somedata");
        });
       $this->getJson(route('categories.list'));
    }
}
