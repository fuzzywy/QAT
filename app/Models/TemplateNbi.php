<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateNbi extends Model
{
   	public $timestamps = false;
    protected $connection = 'Qat';
    protected $table = 'templateNbi';
}
