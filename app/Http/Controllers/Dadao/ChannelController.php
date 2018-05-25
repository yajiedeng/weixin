<?php

namespace App\Http\Controllers\Dadao;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChannelController extends Controller
{
    public function create(Request $request)
    {
        $method = $request->method();
        if($method == "GET"){
            return view('channel.add');
        }else{
            
        }
    }
}
