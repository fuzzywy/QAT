<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RedisHandler extends Controller
{
    //
    public function fromRedis($key)
    {
        return Redis::get($key);
    }
}
