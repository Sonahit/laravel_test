<?php

namespace App\Utils\Helpers;

use Illuminate\Http\Request;

class RequestHelper
{
    public const PARAMS = ['paginate', 'asc', 'page', 'searchParam', 'sortParam'];

    public static function getParamsAsArray(Request $request, array $params)
    {
        $query = [];
        foreach ($params as $param) {
            $query[$param] = $request->query($param);
        }

        return $query;
    }
}
