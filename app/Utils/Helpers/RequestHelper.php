<?php
namespace App\Utils\Helpers;

use Illuminate\Http\Request;

class RequestHelper
{
    public static function get_params_as_array(Request $request, ...$params){
        $query = [];
        foreach ($params as $param) {
            $query[$param] = $request->query($param);
        }       
        return $query;
    }
}


?>