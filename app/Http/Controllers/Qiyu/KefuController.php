<?php

namespace App\Http\Controllers\Qiyu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;

class KefuController extends Controller
{
    private $config = [
        'appKey' => '6206d6a7abc4dfb447035896d3d2953b',
        'appSecret' => 'F9CD4E3F5C7745B795180F7262430044',
    ];
    // 发消息给七鱼
    private $sendMsgUrl = "https://qiyukf.com/openapi/message/send?appKey=%s&time=%s&checksum=%s";
    // 推送给七鱼客服
    private $distributionUrl = "https://qiyukf.com/openapi/event/applyStaff?appKey=%s&time=%s&checksum=%s";
    public function serve()
    {
        Log::info("收到七鱼回复".$_POST);
        $res = $this->sendMessage('wx66',"你好啊，七鱼");
        dump($res);
        // 分配客服
        $res = $this->fenpei('wx66');
        dump($res);
    }

    // 发消息给七鱼
    public function sendMessage($uid,$content)
    {
        // 消息组装
        $arrData = [
            'uid' => $uid,
            "msgType" => "TEXT",
            "content" => $content
        ];
        $postJson = json_encode($arrData);
        $time = time();
        $checksum = $this->check($postJson,$time);
        $url = sprintf($this->sendMsgUrl,$this->config['appKey'],$time,$checksum);
        $res = curl_post($url,$postJson);
        return $res;
    }

    // 分配客服
    public function fenpei($uid)
    {
        $arrData = [
            'uid' => $uid,
        ];
        $postJson = json_encode($arrData);
        $time = time();
        $checksum = $this->check($postJson,$time);
        $url = sprintf($this->distributionUrl,$this->config['appKey'],$time,$checksum);
        $res = curl_post($url,$postJson);
        return $res;
    }

    // 数据校验
    public function check($postJson,$time)
    {
        $none = md5($postJson);
        $checksum = sha1($this->config['appSecret'].$none.$time);
        return $checksum;
    }
}
