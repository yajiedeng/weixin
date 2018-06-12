<?php

/*
 * This file is part of the overtrue/laravel-wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

return [

    /*
     *  测试配置
     * */

    //注册平台链接
//    'splicing_reg_url' => 'https://invite-dev.mydadao.com/incar/dadao.html?channel_from=mydadao&channel_product=h5&scene_method=scan_code&scene_type=bd&scene_str=%s',
//    //点击自定义菜单进入跳转页面
//    'reg_url'          => 'https://invite-dev.mydadao.com/incar/dadao.html?channel_from=mydadao&channel_product=h5',
//    //添加渠道二维码 token
//    'channel_submit_code' => 'zhangyang',
//    //芝麻信用页面链接
//    'zhima_url' => 'https://wechat-oa-dev.mydadao.com/zhima',
//    //百度云访问图片链接
//    'bcebos_url' => 'http://online-incar.bj.bcebos.com/',
//    // 小程序 appid
//    'mini_appid' => 'wxfb0337c363785641',
//    // 小程序卡片图片素材 media_id
//    'thumb_media_id' => "eWnk4bItVHd62OUsRgPBoxYoFVkLLDfzI308wJ0hqS4",
//    // 小程序卡片名称
//    'mini_title' => '大道用车'


    /*
     *  正式配置
     * */
    //注册平台链接
    'splicing_reg_url' => 'http://invite.mydadao.com/dadao.html?channel_from=mydadao&channel_product=h5&scene_method=scan_code&scene_type=bd&scene_str=%s',
    //点击自定义菜单进入跳转页面
    'reg_url'          => 'http://invite.mydadao.com/dadao.html?channel_from=mydadao&channel_product=h5',
    //添加渠道二维码 token
    'channel_submit_code' => 'zhangyang',
    //芝麻信用页面链接
    'zhima_url' => 'https://wechat-oa-dev.mydadao.com/zhima',
    //百度云访问图片链接
    'bcebos_url' => 'http://online-incar.bj.bcebos.com/',
    // 小程序 appid
    'mini_appid' => 'wxfb0337c363785641',
    // 小程序卡片图片素材 media_id
    'thumb_media_id' => "eWnk4bItVHd62OUsRgPBoxYoFVkLLDfzI308wJ0hqS4",
    // 小程序卡片名称
    'mini_title' => '大道用车'
];
