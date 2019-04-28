<?php

namespace App\Models\Kget;

use Illuminate\Database\Eloquent\Model;

class KgetCrontabTask extends Model
{
    public 	  $timestamps = false;
    protected $connection = "Qat";
    protected $table      = "kgetCrontabTask";
}
