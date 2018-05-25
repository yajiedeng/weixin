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
        return $list = $this->app->menu->list();
    }

    /*
     * 创建自定义菜单
     * */
    public function addMenu()
    {
        $reg_url = config('wechat_parameter.reg_url');
        $buttons = [
            [
                "type" => "view",
                "name" => "APP下载",
                "url"  => $reg_url
            ],
            [
                "name"       => "马上用车",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "领取礼包",
                        "url"  => $reg_url
                    ],
                    [
                        "type" => "view",
                        "name" => "免押金及费用",
                        "url"  => "http://mp.weixin.qq.com/s?__biz=MzUxMzQ2OTkzMw==&mid=100000136&idx=1&sn=c7a68f5383ba9eb3cc3fceb501d8df51&chksm=7955fd0a4e22741c146c89ec094ccf53254a45dfee6e043eee55f54bd3194456b80e59c0218d#rd"
                    ],
                    [
                        "type" => "view",
                        "name" => "Q&A",
                        "url" => "http://mp.weixin.qq.com/s?__biz=MzUxMzQ2OTkzMw==&mid=100000010&idx=1&sn=554a66a3b743fbd133ca8e4ca7d65f50&chksm=7955fd884e22749e9b373e12b023a61b78a18747771aafa44d5072b61e8f566bd614bd48fd57&scene=18#wechat_redirect"
                    ],
                ],
            ],
            [
                "name"       => "为您服务",
                "sub_button" => [
                    [
                        "type" => "click",
                        "name" => "客服热线",
                        "key"  => "kefurexian"
                    ],
                    [
                        "type" => "view",
                        "name" => "违章处理",
                        "url"  => "http://invite.mydadao.com/Illegal.html"
                    ],
                ],
            ],
        ];
        return $this->app->menu->create($buttons);
    }

    /*
     * 删除菜单
     * */
    public function delete()
    {
        return $this->app->menu->delete(); // 全部
    }
}
