<?php

namespace App\Utils\Helpers;

use Illuminate\Http\Request;

class RequestHelper{
    public static function queryToArray(Request $request, array $params = [])
    {
        return collect($params)->reduce(function($param, $key) use($request){
            if(array_key_exists($key, $request->all())){
                $param[$key] = $request->query($key);
                return $param;
            }
            $param[$key] = null;
            return $param;
        });
    }
}