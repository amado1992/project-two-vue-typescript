<?php

namespace Modules\Settings\Tests\Feature;

use Modules\Settings\Entities\ClausulesSettings;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClausulesSettingsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSave(): void
    {
        $settings = new ClausulesSettings();

        $clausules = 'csnjcwoneownowibovwbovbwobwobvw';

        $settings->clausules = $clausules;

        $settings->save();

        $this->assertEquals(setting('clausules.clausules'), $clausules);
    }
}
