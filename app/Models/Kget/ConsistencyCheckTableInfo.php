<?php

namespace App\Models\Kget;

use Illuminate\Database\Eloquent\Model;

class ConsistencyCheckTableInfo extends Model
{
    public 	  $timestamps = false;
    protected $connection = "consistencyCheck";
    protected $table      = "ConsistencyCheckTableInfo";
}
