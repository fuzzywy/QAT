<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CounterConfig extends Model
{
     public 	  $timestamps = false;
    protected $connection = "Qat";
    protected $table      = "CounterConfig";
}
