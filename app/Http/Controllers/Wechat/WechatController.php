<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WechatController extends Controller
{
    public $service_app;
    public $mini_app;
    public function __construct(Request $request)
    {
        //获取请求方式
        $this->method = $request->method();
        //初始化
        $this->service_app = app('wechat.official_account');
        $this->mini_app = app('wechat.mini_program');
    }
}
