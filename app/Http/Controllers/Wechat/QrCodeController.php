<?php

namespace App\Http\Controllers\Wechat;

use CodeItNow\BarcodeBundle\Utils\QrCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class QrCodeController extends Controller
{
    // 汽车二维码批量生成
    public function createCarQrCodes(){
        $wechat = new WechatController();
        $start = 31330001;



        $secen_id = $start;
        $res = $wechat->createQrCode($secen_id);
        $qrCode = new QrCode();
        $qrCode
            ->setText($res['url'])
            ->setSize(200)
            ->setPadding(10)
            ->setErrorCorrection('high')
            ->setForegroundColor(array('r' => 0, 'g' => 160, 'b' => 255, 'a' => 0))
            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
            ->setLabel($secen_id)
            ->setLabelFontSize(16)
            ->setImageType(QrCode::IMAGE_TYPE_PNG)
        ;
        dump($qrCode);

        $con = base64_decode($qrCode->generate());

        dump($con);

        die;



        for ($i = 0;$i< 2;$i++){
            $secen_id = $start + $i;
            $res = $wechat->createQrCode($secen_id);
            $qrCode = new QrCode();
            $qrCode
                ->setText($res['url'])
                ->setSize(200)
                ->setPadding(10)
                ->setErrorCorrection('high')
                ->setForegroundColor(array('r' => 0, 'g' => 160, 'b' => 255, 'a' => 0))
                ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
                ->setLabel($secen_id)
                ->setLabelFontSize(16)
                ->setImageType(QrCode::IMAGE_TYPE_PNG)
            ;
            dump($qrCode);

            $con = base64_decode($qrCode->generate());

            dump($con);

            die;

            // 存储图片
            $fileName = public_path("qrcodes/".$secen_id.".png");
            $re = writeImg($con,$fileName);

//            if($res && $re){
//                // 入库
//                $data['url'] = $res['url'];
//                $data['ticket'] = $res['ticket'];
//                $data['secen_id'] = $secen_id;
////                $data['type'] = 'car';
//                $result[] = DB::table('car_qrcode')->insert($data);
//            }
        }

//        dump($result);

    }
}
