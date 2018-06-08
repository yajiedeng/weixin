<?php

namespace App\Http\Controllers\Wechat;

use App\Models\User;
use Illuminate\Http\Request;
use Log;

class UserController extends BaseController
{
    /*
     *  获取用户个人信息
     * */
    public function getUserInfo($openId)
    {
        $userInfo = $this->app->user->get($openId);
        Log::info("user info".json_encode($userInfo));
        // 查询该用户信息是否已经入库
        $user = User::where('wechat_openid',$openId)->first();
        if($user){
            Log::info('微信公众号用户信息已经存在');
           return true;
        }
        // 存储公众号用户信息
        $users['wechat_openid'] = $userInfo['openid'];
        if(array_key_exists('unionid',$userInfo) && $userInfo['unionid']){
            $users['unionid'] = $userInfo['unionid'];
        }
        $users['nickname'] = $userInfo['nickname'];
        if($userInfo['province']){
            $users['city'] = $userInfo['province'].$userInfo['city'];
        }else{
            $users['city'] = $userInfo['country'];
        }
        $users['gender'] = $userInfo['sex'];
        $users['headimgurl'] = $userInfo['headimgurl'];
        $users['subscribe_time'] = $userInfo['subscribe_time'];
        $user = User::create($users);
        if($user){
            Log::info('微信公众号用户信息存储成功'.$user);
        }else{
            Log::info('微信公众号用户信息存储失败');
            return false;
        }
    }

    /*
     *  获取用户列表
     * */

    public function getUserList()
    {
        $userList = $this->app->user->list();
        Log::info("user info".json_encode($userList));
        dump($userList);
    }

    /*
     *  老用户个人信息入库
     * */

    public function oldUserInfo()
    {
        $userList = $this->app->user->list();
        foreach ($userList['data']['openid'] as $openid){
            $res = $this->getUserInfo($openid);
            echo $res."<br/>";
        }
    }
}
