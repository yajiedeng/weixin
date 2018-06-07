<?php

namespace App\Http\Controllers\Dadao;

use App\Http\Controllers\Wechat\WechatController;
use App\Models\Channel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

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
            // 参数验证
            $error = Validator::make(request()->all(), [
                'username' => 'required', // 姓名
                'vcode' => 'required', // 验证码
            ], [
                    'username.required' => '请输入渠道人员姓名',
                    'vcode.required' => '验证码不正确',
                ]
            );

            // 参数有错误 直接返回错误信息
            if($error->fails()){
                return responce(400,$error->errors()->first());
            }

            $code = config("wechat_parameter.channel_submit_code");
            //接收页面参数
            $data['username'] = $request->input('username');
            $data['vcode'] = $request->input('vcode');
            if ($data['vcode'] != $code){
                return responce(-1,"验证码不正确");
            }
            $one = Channel::where('username',$data['username'])->first();
            if($one){
                return responce(-1,"用户名已存在");
            }
            unset($data['vcode']);
            //存入数据库并获取 id 字段
            $res = Channel::create($data);
            $lastId = $res->id;
            //生成二维码
            $wechat = new WechatController;
            $result = $wechat->createQrCode("bd_".$lastId);
            //组装图片链接
            $imgUrl = $wechat->getQrCodeUrl($result['ticket']);
            //将二维码链接和 ticket 存入数据库
            $res->url = $result['url'];
            $res->ticket = $result['ticket'];
            $res->imgUrl = $imgUrl;
            $res->save();
//            DB::table('channel')->where('id',$lastId)->update(['url' => $result['url'],'ticket'=>$result['ticket'],'imgUrl'=>$imgUrl]);
            // 返回图片链接
            return responce(1,"获取成功",$imgUrl);
        }
    }
}
