<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    public 	  $timestamps = false;
    protected $connection = "Qat";
    protected $table      = "Template";
}
