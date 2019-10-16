<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class New_Matrix extends Model
{
    protected $table = 'new_matrix';
    protected $primaryKey = 'id';

    public function meal_rules()
    {
        return $this;
    }
}
