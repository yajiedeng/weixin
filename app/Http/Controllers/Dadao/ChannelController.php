<?php

namespace App\Http\Controllers\Dadao;

use App\Http\Controllers\Wechat\WechatController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ChannelController extends Controller
{
    public function create(Request $request)
    {
        $method = $request->method();
        if($method == "GET"){
            return view('channel.add');
        }
    }

    public function doCreate(Request $request)
    {
        if($request->method() == "POST"){
            //验证
            
            $code = config("wechat_parameter.channel_submit_code");
            //接收页面参数
            $data['username'] = $request->input('userName');
            $data['vcode'] = $request->input('vcode');
            if(empty($data['username'])){
                return responce(-1,"请输入渠道人员姓名");
                exit();
            }elseif ($data['vcode'] != $code){
                return responce(-1,"验证码不正确");
                exit();
            }
            $one = DB::table('channel')->where('username',$data['username'])->first();
            if($one){
                return responce(-1,"用户名已存在");
                exit();
            }
            unset($data['vcode']);
            //存入数据库并获取 id 字段
            $lastId = DB::table('channel')->insertGetId($data);
            //生成二维码
            $wechat = new WechatController;
            $result = $wechat->createQrCode("bd_".$lastId);
            //组装图片链接
            $imgUrl = $wechat->getQrCodeUrl($result['ticket']);
            //将二维码链接和 ticket 存入数据库
            DB::table('channel')->where('id',$lastId)->update(['url' => $result['url'],'ticket'=>$result['ticket'],'imgUrl'=>$imgUrl]);
            // 返回图片链接
            return responce(1,"获取成功",$imgUrl);
        }
    }
}
