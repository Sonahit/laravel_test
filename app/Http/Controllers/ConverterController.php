<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Billed_Meals;
use Dompdf\Dompdf;
use PDF;

class ConverterController extends Controller
{
    /**
     * @param App\Models\Billed_Meals $billed_Meals
     * @param Illuminate\Http\Request $request
     * @return array
     */
    protected function getTable(Billed_Meals $billed_Meals, Request $request){
      $controller = new BilledMealsController();
      $resp = $controller->index($billed_Meals, $request);
      $data = json_decode($resp->content());
      if(!is_array($data->pages)){
          $body = $data->pages->data;
      } else {
          $body = $data->pages;
      }
      return $body;
    }

    public function index(Billed_Meals $billed_Meals, Request $request)
    {
      $body = $this->getTable($billed_Meals, $request);
      return view('templates.table', ["table_data" => $body]);
    }
    
    public function pdf(Billed_Meals $billed_Meals, Request $request)
    {
      $body = $this->getTable($billed_Meals, $request);
      gc_disable();
      // It takes too long
      $pdf = PDF::loadView("templates.table", ['table_data' => $body]);
      $output = $pdf->output();
      gc_enable();
      gc_collect_cycles();
      return base64_encode($output);
    }
    public function csv(Billed_Meals $billed_Meals, Request $request){
      $body = $this->getTable($billed_Meals, $request);
      return ["table" => $body];
    }
}



?>
