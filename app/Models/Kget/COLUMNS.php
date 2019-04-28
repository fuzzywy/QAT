<?php

namespace App\Models\Kget;

use Illuminate\Database\Eloquent\Model;

class COLUMNS extends Model
{
    protected $connection = 'mongs';
    protected $table = 'information_schema.COLUMNS';
    public $timestamps = false;
}