<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WechatController extends Controller
{
    public function serve()
    {
        $app = app('wechat.official_account');

        $message = $app->server->getMessage();
        $openId = $message['FromUserName'];
        $text = new Text('hello');
        $app->customer_service->message($text)->to($openId)->send();
        return $app->server->serve();
    }
}
