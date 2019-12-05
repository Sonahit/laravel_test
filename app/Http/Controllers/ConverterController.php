<?php

namespace App\Http\Controllers;

use App\Models\FlightLoad;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PDF;

class ConverterController extends Controller
{
    /**
     * Generate view as PDW by raw data.
     *
     * @param \App\Models\FlightLoad $flightLoad
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function index(FlightLoad $flightLoad, Request $request)
    {
        $body = $this->getTable($flightLoad, $request);
        $pdf = PDF::loadView('templates.table', ['table_data' => $body]);
        $output = $pdf->output();

        return new Response(['data' => base64_encode($output)], 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Get PDF representation of data.
     *
     * @param \App\Models\FlightLoad $flightLoad
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function pdf(FlightLoad $flightLoad, Request $request)
    {
        $body = $this->getTable($flightLoad, $request);
        $pdf = PDF::loadView('templates.table', ['table_data' => $body]);
        $output = $pdf->output();

        return new Response(
            json_encode(['data' => base64_encode($output)]),
            200,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * Get raw data for CSV converting.
     *
     * @param \App\Models\FlightLoad $flightLoad
     * @param \Illuminate\Http\Request $request
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
     * @param \App\Models\FlightLoad $flightLoad
     * @param \Illuminate\Http\Request $request
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
