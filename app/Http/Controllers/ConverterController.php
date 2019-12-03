<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Models\Flight_Load;
use PDF;

class ConverterController extends Controller
{
    /**
     * @param \App\Models\Flight_Load $fl
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function getTable(Flight_Load $fl, Request $request){
      $c = new ReportsController();
      $resp = $c->index($fl, $request);
      $data = json_decode($resp->content());
      if(!is_array($data->pages)){
          $body = $data->pages->data;
      } else {
          $body = $data->pages;
      }
      return $body;
    }

    public function index(Flight_Load $flight_load, Request $request)
    {
      $body = $this->getTable($flight_load, $request);
      $pdf = PDF::loadView("templates.table", ['table_data' => $body]);
      $output = $pdf->output();
      return base64_encode($output);
    }
    
    public function pdf(Flight_Load $flight_load, Request $request)
    {
      $body = $this->getTable($flight_load, $request);
      $pdf = PDF::loadView("templates.table", ['table_data' => $body]);
      $output = $pdf->output();
      return base64_encode($output);
    }

    public function csv(Flight_Load $flight_load, Request $request){
      $body = $this->getTable($flight_load, $request);
      return ["table" => $body];
    }
    
}



?>
