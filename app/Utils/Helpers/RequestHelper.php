<?php
namespace App\Utils\Helpers;

use Illuminate\Http\Request;

class RequestHelper
{
  public const params = ["paginate", "asc", "page", "searchParam", "sortParam"];  
  public static function get_params_as_array(Request $request, array $params){
      $query = [];
      foreach ($params as $param) {
          $query[$param] = $request->query($param);
      }       
      return $query;
  }
}


?>