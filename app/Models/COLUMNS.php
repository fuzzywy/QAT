<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class COLUMNS extends Model
{
	public 	  $timestamps = false;
    protected $connection = 'Qat';
    protected $table = 'information_schema.COLUMNS';
}