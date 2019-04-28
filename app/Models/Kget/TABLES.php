<?php

namespace App\Models\Kget;

use Illuminate\Database\Eloquent\Model;

class TABLES extends Model
{
    protected $connection = 'mongs';
    protected $table = 'information_schema.TABLES';
    public $timestamps = false;
}