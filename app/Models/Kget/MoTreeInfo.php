<?php

namespace App\Models\Kget;

use Illuminate\Database\Eloquent\Model;

class MoTreeInfo extends Model
{
    public 	  $timestamps = false;
    protected $connection = "kget";
    protected $table      = "moTreeInfo";
}
