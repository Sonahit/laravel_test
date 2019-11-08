<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebTest extends TestCase
{
    private $rootPath = "/";
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testShouldGetRootPath()
    {
        $resp = $this->get("/");
        $resp->assertOk();
    }
}
