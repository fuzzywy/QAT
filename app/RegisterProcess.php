<?php
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class RegisterProcess extends Authenticatable
{
    use Notifiable;


    public 	  $timestamps = false;
    protected $connection = "Qat";
    protected $table      = "registerProcess";
    protected $fillable = [
        'name', 'email', 'password','time','type','status'
    ];
}
