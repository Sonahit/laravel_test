<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    protected function getClassName(){
        return static::class; 
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(scandir('./database/seeds') as $file){
            if(!($file == "." or $file == "..") and endsWith($file, '.php'))
                require_once $file;
                $class = basename($file, '.php');
                if(class_exists($class) and $this->getClassName() != $class){
                    $instance = new $class;
                    $instance -> run();
                }
        }
    }
}

function endsWith($string, $end)
{
    $length = strlen($end);
    if ($length == 0) {
        return true;
    }

    return (substr($string, -$length) === $end);
}
