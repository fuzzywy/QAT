<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;

class TestController extends Controller
{
    public function test() {
    	sleep(3);
    	// $test = input::get('test');
        return "test
        " . 'Controller';
    }
}
