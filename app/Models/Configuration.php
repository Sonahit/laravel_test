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

    public static function getConfigs()
    {
        $configs = self::all();
        collect(self::CONFIGURATIONS)->each(function ($defaultConfig) use ($configs) {
            if (is_null($configs->firstWhere('name', $defaultConfig['name']))) {
                $configs->push($defaultConfig);
            }
        });
        return $configs;
    }

    private static function getDefaultConfig(string $configName)
    {
        return collect(self::CONFIGURATIONS)->firstWhere('name', $configName);
    }

    public static function isRegistrationOpen()
    {
        $IS_REGISTRATION_OPEN = self::where('name', 'REGISTRATION_IS_OPEN')->first();
        if (is_null($IS_REGISTRATION_OPEN)) {
            return self::getDefaultConfig('REGISTRATION_IS_OPEN');
        }
        return boolval($IS_REGISTRATION_OPEN->BOOL_VAL);
    }

    public function set(array $keys, array $values)
    {
        return $this->update($keys, $values);
    }
}
