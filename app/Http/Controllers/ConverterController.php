<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Billed_Meals;

class ConverterController extends Controller
{
    public function index()
    {
        $status = 406;
        $raw = [
            'status' => $status, 
            'body' => '',
        ];
        $json = json_encode($raw);
        return new Response($json, $status);
    }

    
    public function pdf(Billed_Meals $billed_Meals, Request $request)
    {
        $controller = new BilledMealsController();
        $resp = $controller->index($billed_Meals, $request);
        $data = json_decode($resp->content());
        if(!is_array($data->pages)){
            $body = $data->pages->data;
        } else {
            $body = $data->pages;
        }
        $tableHTML = $this->get_html_table($body);
        $pdf = \App::make('dompdf.wrapper');
        //TODO: FIXME font or encoding problem
        $pdf->setOptions(['default_font' => 'arialuni', 'font_dir' => storage_path('fonts')]);
        $pdf->loadHTML($tableHTML, 'utf-8');
        return $pdf->stream();
    }

    private function get_html_table(array $body){
        return "<table>
                    <thead>
                    {$this->get_thead()}
                    </thead>
                    <tbody>
                    {$this->get_tbody($body)}
                    </tbody>
                </table>";
    }

    private function get_thead(){
        return '
            <tr>
                <th rowSpan="2">Номер полёта</th>
                <th rowSpan="2">Дата полёта</th>
                <th rowSpan="2">Класс</th>
                <th rowSpan="2">Тип номенклатуры</th>
                <th colSpan="2">Код</th>
                <th colSpan="2">Количество</th>
                <th colSpan="2">Цена</th>
                <th rowSpan="2">Дельта</th>
            </tr>
            <tr>
                <th>План</th
                <th>Факт</th>
                <th>План</th>
                <th>Факт</th>
                <th>План</th>
                <th>Факт</th>
            </tr>';
    }

    private function get_tbody($rows){
        $body = "";
        foreach ($rows as $row) {
            $id = $row->id;
            $date = $row->date;
            $class = $row->class;
            $type = $row->type;
            $fact_attributes = $row->fact_attributes;
            $plan_attributes = $row->plan_attributes;
            $fact_codes = implode(",",$fact_attributes->codes);
            $fact_qty = $fact_attributes->qty;
            $fact_price = $fact_attributes->price;
            $plan_codes = implode(",",$plan_attributes->codes);
            if(!$plan_codes) $plan_codes = 'NO DATA';
            $plan_qty = $plan_attributes->qty;
            if(!$plan_qty) $plan_qty = 0;
            $plan_price = $plan_attributes->price;
            if(!$plan_price) $plan_price = 0;
            $delta = $plan_price - $fact_price;
            $body .= "
            <tr>
                <td>{$id}</td>
                <td>{$date}</td>
                <td>{$class}</td>
                <td>{$type}</td>
                <td>{$plan_codes}</td>
                <td>{$fact_codes}</td>
                <td>{$plan_qty}</td>
                <td>{$fact_qty}</td>
                <td>{$plan_price}</td>
                <td>{$fact_price}</td>
                <td>{$delta}</td>
            </tr>
            ";
        }
        return $body;
    }
}



?>
