<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CounterTables extends Model
{
    public 	  $timestamps = false;
    protected $connection = "Qat";
    protected $table      = "CounterTables";
}
