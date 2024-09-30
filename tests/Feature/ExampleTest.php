<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    // test if environment is testing
    public function test_environment_is_testing()
    {
        $this->assertEquals('testing', $this->app->environment());
    }
}
