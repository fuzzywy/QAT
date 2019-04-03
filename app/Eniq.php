<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Eniq extends Model
{
    public 	  $timestamps = false;
    protected $connection = "Qat";
    protected $table      = "eniqs";
    protected $fillable = [
        'conn',
        'type'
    ];
}
