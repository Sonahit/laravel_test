<?php

namespace Tests\Unit;

use Tests\TestCase;

class ApiTest extends TestCase
{
    private $apiPath = "/api/v1";
    
    public function testShouldGetBilledMeals()
    {
      $this->get("{$this->apiPath}/billed_meals")->assertOk();
    }

    public function testShouldNotGetPDF(){
      $this->post("{$this->apiPath}/pdf")->assertStatus(200);
    }

    public function testShouldGetPDF(){
      $this->get("{$this->apiPath}/pdf")->assertOk();
    }

}
