<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
  	public 	  $timestamps = false;
    protected $connection = "Qat";
    protected $table      = "Kpi";
}
