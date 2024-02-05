<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Roles\Application\ReadModulesUseCase;
use Modules\Roles\Entities\Role;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ReadPermissionsUseCaseTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_read(): void
    {
        $useCase = new ReadModulesUseCase();

        $p = $useCase(Role::first());

        $this->assertNotEmpty($p);
    }
}
