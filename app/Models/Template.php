<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
   	public $timestamps = false;
    protected $connection = 'Qat';
    protected $table = 'template';
}
