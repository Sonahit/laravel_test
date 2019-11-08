<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    private $apiPath = "/api/v1";
    
    public function testShouldGetBilledMeals()
    {
      $this->get("{$this->apiPath}/billed_meals")->assertOk();
    }

    public function testShouldNotGetPDF(){
      $this->post("{$this->apiPath}/pdf")->assertStatus(406);
    }

    public function testShouldGetPDF(){
      $this->get("{$this->apiPath}/pdf")->assertOk();
    }

}
