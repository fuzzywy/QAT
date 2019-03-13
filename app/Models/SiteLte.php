<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteLte extends Model
{
    public 	  $timestamps = false;
    protected $connection = "mongs";
    protected $table      = "siteLte";
}
