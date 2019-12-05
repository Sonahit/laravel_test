<?php

namespace App\Http\Controllers;

use App\Models\FlightLoad;
use Illuminate\Http\Request;
use PDF;

class ConverterController extends Controller
{
    /**
     * Generate view as PDW by raw data.
     *
     * @return string
     */
    public function index(FlightLoad $flightLoad, Request $request)
    {
        $body = $this->getTable($flightLoad, $request);
        $pdf = PDF::loadView('templates.table', ['table_data' => $body]);
        $output = $pdf->output();

        return base64_encode($output);
    }

    /**
     * Get PDF representation of data.
     *
     * @return string
     */
    public function pdf(FlightLoad $flightLoad, Request $request)
    {
        $body = $this->getTable($flightLoad, $request);
        $pdf = PDF::loadView('templates.table', ['table_data' => $body]);
        $output = $pdf->output();

        return base64_encode($output);
    }

    /**
     * Get raw data for CSV converting.
     *
     * @return array
     */
    public function csv(FlightLoad $flightLoad, Request $request)
    {
        $body = $this->getTable($flightLoad, $request);

        return ['table' => $body];
    }

    /**
     * Get raw data.
     *
     * @return array
     */
    protected function getTable(FlightLoad $flightLoad, Request $request)
    {
        $c = new ReportsController();
        $resp = $c->index($flightLoad, $request);
        $data = json_decode($resp->content());
        if (!is_array($data->pages)) {
            $body = $data->pages->data;
        } else {
            $body = $data->pages;
        }

        return $body;
    }
}
