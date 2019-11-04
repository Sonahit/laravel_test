<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConverterController extends Controller
{
    public function index()
    {
        $raw = [
            'not_supported' => "Yes",
            'status' => 200,
        ];
        $json = json_encode($raw);
        $response = new Response($json);
        return $response;
    }

    
    public function pdf($request)
    {
        //TODO: get pdf file
        $pathToFile = storage_path() . "/" . strval(random_int(0, 1000000000)) . ".pdf";
        // $pdf = PDF::loadView('pdf.invoice', $pathToFile);
        // return $pdf->download('invoice.pdf');
    }
}



?>
