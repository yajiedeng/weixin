<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;

class WechatController extends Controller
{
    public function serve()
    {
        $app = app('wechat.official_account');
        $service = $app->customer_service;
        Log::info('miniapp. start . data .'.$service);
        $app->server->push(function($message){
            return "666";
        });
        return $app->server->serve();
        Log::info('miniapp. over');
    }
}
