<?php

namespace App\Http\Controllers\Dadao;

use EasyWeChat\Kernel\Messages\Raw;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Log;
use App\Models\User;
use BaiduFace\Api\AipFace;
//use SimpleSoftwareIO\QrCode\Facades\QrCode;
use CodeItNow\BarcodeBundle\Utils\QrCode;
use Maatwebsite\Excel\Facades\Excel;

class TestController extends Controller
{
    public function test(Request $request)
    {
        $secenStr = 'incar3652411';
        $secenStr = substr($secenStr,5,strlen($secenStr)-1);
        echo $secenStr;
        die;
        $file = public_path('excel/qrcode.xlsx');
        //获取数组类型的数据
        $results = Excel::load($file)->get()->toArray();
        dump($results);

        die;

        $qrCode = new QrCode();

        $qrCode
            ->setText('QR code by codeitnow.in')
            ->setSize(300)
            ->setPadding(10)
            ->setErrorCorrection('high')
            ->setForegroundColor(array('r' => 0, 'g' => 160, 'b' => 255, 'a' => 0))
            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
            ->setLabel("33645645646")
            ->setLabelFontSize(16)
            ->setImageType(QrCode::IMAGE_TYPE_PNG)
        ;
        dump($qrCode->getContentType());
        $con = base64_decode($qrCode->generate());
        dump($con);
        // 存储到文件

        $fileName = public_path("qrcodes/qrcode.png");
        $res = writeImg($con,$fileName);
        dump($res);

        echo '<img src="'.public_path('qrcodes/qrcode.png').'" />';

//        dump($qrCode);
        die;

//        QrCode::

        // 生成二维码
        $res = QrCode::format('png')->merge('/public/images/logo.png',.30)->size(300)->color(0,160,255)->backgroundColor(255,255,255)->generate('http://weixin.qq.com/q/02GBpqUD5eeZ310000w07H',public_path('qrcodes/qrcode.png'));
        dump($res);
        echo "<img src='".public_path('qrcodes/qrcode.png')."'>";
//        dump($res);
        die;

        $app = app('wechat.official_account');
//        $result = $app->material->uploadImage("./images/miniapp.png");
//
//        dump($result);die;
        $appId = config('wechat_parameter.mini_appid');
        $openid = 'ohc69wyn6neXXMUJYLHmGXlF0cys';
        $message = new Raw('{
            "touser":"'.$openid.'",
            "msgtype":"miniprogrampage",
            "miniprogrampage":
            {
                "title":"哈哈哈",
                "appid":"'.$appId.'",
                "pagepath":"/pages/index/index",
                "thumb_media_id":"eWnk4bItVHd62OUsRgPBoxYoFVkLLDfzI308wJ0hqS4"
            }
        }');

        $result = $app->customer_service->message($message)->to($openid)->send();
        $response = $app->server->serve();
        dump($response);
//        $result = $app->customer_service->message($message)->send();
        return $result;

//        echo $message;
        die;
        $user = User::where('unionid', 'orHxx0_H3kv66M-HSqN_hXKjd8IE')->get();
        dump($user);
    }
}
