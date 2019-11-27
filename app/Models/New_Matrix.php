<?php

namespace App\Models;

use App\Collections\New_Matrix_Collection;
use Illuminate\Database\Eloquent\Model;

class New_Matrix extends Model
{
    protected $table = 'new_matrix';
    protected $primaryKey = 'id';
    protected $perPage = 40;

    public function newCollection(array $models = [])
    {   
        return new New_Matrix_Collection($models);
    }
}
