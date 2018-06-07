<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    protected $app;
    public function __construct()
    {
        //初始化
        $this->app = app('wechat.official_account');
        $this->miniapp = app('wechat.mini_program');
    }
}
