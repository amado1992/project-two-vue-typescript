<?php

namespace Modules\Brands\Tests\Feature;

use Modules\Brands\Application\CreateBrandUseCase;
use Modules\Brands\Http\Requests\StoreBrandRequest;
use Modules\Users\Entities\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @author Abel David.
 */
class CreateBrandUseCaseTest extends TestCase
{
    /**
     * @var CreateBrandUseCase
     */
    private CreateBrandUseCase $useCase;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->useCase = app(CreateBrandUseCase::class);

        $this->actingAs(User::first());
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testInvoke(): void
    {
        $brand = ($this->useCase)([
            'name' => 'A some brand',
            'active' => true
        ]);

        $this->assertNotNull($brand);
    }
}
