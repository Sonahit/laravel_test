<?php

namespace Tests\Unit;

use Tests\TestCase;
class ApiTest extends TestCase
{
    private $apiPath = "/api/v1";
    

    public function testShouldGetBilledMeals()
    {
      $resp = $this->get("{$this->apiPath}/billed_meals");
      $resp->assertOk();
      $json = $resp->json();
      $resp->assertJson($json);
      $this->assertNotEmpty($json);
    }

    public function testShouldbeEmpty()
    {
      $resp = $this->get("{$this->apiPath}/billed_meals?page=2&paginate=-1");
      $resp->assertStatus(204);
      $data = $resp->baseResponse->getContent();
      $this->assertEmpty($data);
    }

    public function testShouldNotGetPDF(){
      $this->post("{$this->apiPath}/pdf")->assertStatus(200);
    }

    public function testShouldGetPDF(){
      $this->get("{$this->apiPath}/pdf")->assertOk();
    }

}
