<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfigRequest;
use App\Models\Configuration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ConfigurationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    protected function nullable(array $attrs, array $rows)
    {
        return collect($rows)->reduce(function ($acc, $row) use ($attrs) {
            if (array_key_exists($row, $attrs)) {
                $acc[$row] = $attrs[$row];
                return $acc;
            }
            $acc[$row] = null;
            return $acc;
        }, []);
    }

    public function update(ConfigRequest $request, $configName)
    {
        $attrs = ['INT_VAL', 'BOOL_VAL', 'DATE_VAL', 'STRING_VAL'];
        $configuration = $this->nullable($request->only($attrs), $attrs);
        $date = is_null($configuration['DATE_VAL'])
            ? $configuration['DATE_VAL']
            : Carbon::parse($configuration['DATE_VAL'])->toDateString();
        $config = Configuration::firstOrNew(['name' => $configName]);
        $config->INT_VAL = $configuration['INT_VAL'];
        $config->DATE_VAL = $date;
        $config->BOOL_VAL = boolval($configuration['BOOL_VAL']);
        $config->STRING_VAL = $configuration['STRING_VAL'];
        $config->save();
        return redirect()->back()->with('success', "Successfully changed configration {$configName}");
    }
}
