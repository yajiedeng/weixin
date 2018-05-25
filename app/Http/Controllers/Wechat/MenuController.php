<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    private $app;

    public function __construct()
    {
        $this->app = app('wechat.official_account');
    }

    /*
     * 获取当前自定义菜单
     * */
    public function meunList()
    {
        $list = $this->app->menu->list();
        dump($list);
    }
}
