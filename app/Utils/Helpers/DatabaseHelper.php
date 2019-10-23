<?php

namespace App\Utils\Helpers; 

use Illuminate\Support\Facades\DB;

class DatabaseHelper{

  public static function updateOrInsert($tableName, $columns, $rows){
    foreach($columns as $column){
      $insert = [];
      foreach ($rows as $row) {
        $insert[$row] = $column->$row;
      }
      //TODO: Test me
      //if(!DB::table($tableName)->whereExists($insert)){
        DB::table($tableName)->updateOrInsert($insert);
      //}
    }
  }

  public static function insert($tableName, $columns, $rows){
    foreach($columns as $column){
      $insert = [];
      foreach ($rows as $row) {
        $insert[$row] = $column->$row;
      }
      DB::table($tableName)->insert($insert);
    }
  }


}

?>