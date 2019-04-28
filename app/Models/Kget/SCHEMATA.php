<?php

namespace App\Models\Kget;

use Illuminate\Database\Eloquent\Model;

class SCHEMATA extends Model
{
    protected $connection = 'mongs';
    protected $table = 'information_schema.SCHEMATA';
    public $timestamps = false;
}