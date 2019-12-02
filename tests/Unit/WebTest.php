<?php

namespace Tests\Unit;

use Tests\TestCase;

class WebTest extends TestCase
{
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
