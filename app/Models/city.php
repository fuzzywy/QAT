<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class city extends Model
{
    public 	  $timestamps = false;
    protected $connection = "Qat";
    protected $table      = "cities";
}
