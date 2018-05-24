<?php

namespace App\Http\Controllers\Dadao;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function test(Request $request){

        $res = $request->all();
        dump($res);

    }
}
