<?php

namespace App\Http\Controllers\Dadao;


use App\Http\Controllers\Lib\FaceController;
use App\Http\Controllers\Wechat\WechatController;
use App\Libs\Face;
use Godruoyi\LaravelOCR\OCR;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Log;
use App\Models\User;
use BaiduFace\Api\AipFace;

class TestController extends Controller
{
    public function test(Request $request)
    {
        $user = User::where('unionid', 'orHxx0_H3kv66M-HSqN_hXKjd8IE')->get();
        dump($user);
    }
}
