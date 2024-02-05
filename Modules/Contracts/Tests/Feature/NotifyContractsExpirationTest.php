<?php

namespace Modules\Contracts\Tests\Feature;

use Modules\Contracts\Console\NotifyContractsExpiration;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotifyContractsExpirationTest extends TestCase
{
    /**
     * @var NotifyContractsExpiration
     */
    private NotifyContractsExpiration $command;

    protected function setUp(): void
    {
        parent::setUp();
        $this->command = app(NotifyContractsExpiration::class);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @throws \Exception
     */
    public function testHandle(): void
    {
        $this->command->handle();

        $this->assertTrue(true);
    }
}
