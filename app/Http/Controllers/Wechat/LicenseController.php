<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;

class LicenseController extends Controller
{
    public function log(Request $request)
    {
        Log::info("导入车牌二维码信息".json_encode($request,JSON_UNESCAPED_UNICODE));
    }
}
