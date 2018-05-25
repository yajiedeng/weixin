<?php

namespace App\Http\Controllers\Dadao;

use App\Http\Controllers\Wechat\WechatController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChannelController extends Controller
{
    public function create(Request $request)
    {
        $method = $request->method();
        echo $method;die;
        if($method == "GET"){
            return view('channel.add');
        }elseif($method == "POST"){
            dump('fhjdksjhfjkldslfkds');

        }
    }
}
