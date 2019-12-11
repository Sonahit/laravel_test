<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    protected $table = 'configurations';
    protected $primateKey = 'id';
    public const CONFIGURATIONS = [
        [
            'name' => 'REGISTRATION_IS_OPEN',
            'BOOL_VAL' => true
        ]
    ];

    protected $fillable = [
        'name',
        'INT_VAL',
        'BOOL_VAL',
        'DATE_VAL',
        'STRING_VAL'
    ];

    public static function getConfigs(){
        $configs = self::all();
        collect(self::CONFIGURATIONS)->each(function($defaultConfig) use($configs){
            if(is_null($configs->firstWhere('name', $defaultConfig['name']))){
                $configs->push($defaultConfig);
            }
        });
        return $configs;
    }

    public static function IS_REGISTRATION_OPEN()
    {
        return boolval(self::where('name', 'REGISTRATION_IS_OPEN')->first()->BOOL_VAL);
    }

    public function set(array $keys, array $values)
    {
        return $this->update($keys, $values);
    }
}
