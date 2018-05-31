<?php

namespace App\Http\Controllers\Dadao;


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
    public function test(Request $request){

        $user_id = 45678;
        $file = $request->file('name');
        // 文件是否上传成功
        if($file->isValid()){
            // 临时绝对路径
            $realPath = $file->getRealPath();

            $re = Storage::disk('bos')->put($user_id.'name',file_get_contents($realPath));
            return $re;
        }else{
            Log::error("文件上传失败");
        }

        dump($file);
        die;

        //判断文件是否存在
        $exists = Storage::disk('bos')->exists('1.jpg');
        dump($exists);
        //获取文件内容
        $content = Storage::disk('bos')->get('path/to/file');
        dump($content);
        die;

//        $imgUrl = "http://bos.bj.baidubce.com/v1/testincar/11.png?authorization=bce-auth-v1%2Fb62d1ebc5fdf4cc6869a6b8e8fe09e30%2F2018-05-30T03%3A49%3A27Z%2F36666666%2F%2F77f56586c5cb0a9bedeb1ddcaca4bc3220ac83b32eac059bc308325031c9293e";
//        $imgUrl = "http://online-incar.bj.bcebos.com/23427227653161";
//
//        //读取图片内容
//        $imgContent = curl_get($imgUrl);
//
//        //写入文件
//        $filePath = public_path().'/upload/user/certificates/';
//        $fileName = time().".jpg";
//        $path = $filePath.$fileName;
//        $ifp = fopen( $path, "wb" );
//        fwrite( $ifp, $imgContent );
//        fclose( $ifp );
//
//        dump($imgContent);
//        die;

        $appId = config('ai.appId');
        $appKey = config('ai.apiKey');
        $appSecret = config('ai.apiSecret');
        $client = new AipFace($appId, $appKey, $appSecret);
        $images = [
            file_get_contents('http://online-incar.bj.bcebos.com/23427227653161'),
            file_get_contents('http://online-incar.bj.bcebos.com/23427227653206'),
        ];
//        $image = file_get_contents(public_path().'/images/a.jpg');

        // 调用人脸检测
        $data = $client->match($images);
        dump($data);

        die;

        $result = Ai::Nlp()->topic('标题','这里是测试文本');
        dump($result);


        die;

        $face = new Face();
        dd($face);

        die;

        $user = User::all();
        $user = User::find(28);
        dd($user);

        die;

        $re = $this->validate($request,[
            'name' => 'required|string|max:6',
        ]);

        dump($re);die;


        $res = $this->validate($request, [
            'name' => 'required'
        ]);
        dump($res);
        die;

        dump($request);

        die;

        $user = User::create(['name'=>'User 456']);
        $id = $user->id;
        echo $id;
        dump($user);
        die;

        $str = 'qrscene_db_42';
        $str = findNum($str);
        dump($str);
        die;

        // 身份证识别
        $img = "http://wechat.qihuapp.com/images/aaa.png";
        $filePath = $img;//"http://img2.imgtn.bdimg.com/it/u=3987992400,3053657058&fm=27&gp=0.jpg";//public_path().'/images/id_card.jpg';
        $re = OCR::baidu()->idcard($filePath,[
            'detect_direction'      => false,      //是否检测图像朝向
            'id_card_side'          => 'front',    //front：身份证正面；back：身份证背面 （注意，该参数必选）
            'detect_risk'           => false,      //是否开启身份证风险类型功能，默认false
        ]);
        return $re;

        die;

        $demo = new Demo();
        die;

        $wechat = new WechatController();
        dump($wechat);
        $result = $wechat->createQrCode('bd_15212');
        dump($result);
        die;

        $id = false;
        if($id){
            Log::info('this is id = '.$id);
        }else{
            Log::error('发生了错误信息');
        }
        die;

        $app = app('wechat.official_account');
        $list = $app->menu->list();
        dump($list);
        die;

        $content = DB::table('wx_message')->where('keyword',1)->first();
        $content = json_decode($content->content);
        dump($content);
        echo $content->title;
        die;

        $arr = [
            'title' => '新用户注册立即送',
            'description' => '现在新用户注册就有大礼包相送，机会不等人，还不赶快来~',
            'url' => 'http://mp.weixin.qq.com/s?__biz=MzUxMzQ2OTkzMw==&mid=100000006&idx=1&sn=1f42ba0c2e686475ef607ce034d75d57&chksm=7955fd844e2274926b5a541ebf4aa50912d16a22ae34cbdc8d975ae964a0ed37fb9dc20debd1&scene=18#wechat_redirect',
            'picurl' => 'https://mmbiz.qpic.cn/mmbiz_jpg/Fj8OO58Avd3p1NttnXIhoRuZHYFDicpS7O7ntgsTaVfYGZpOdolSWGvRth3UTWmbM54d16s3TLRYEM9VlmXnmtg/640?wx_fmt=jpeg&tp=webp&wxfrom=5&wx_lazy=1',
        ];

        $json = json_encode($arr);

        $re = DB::table('wx_message')->insert(['keyword'=>'subscribe','content'=>$json,'type'=>2]);
        die;


        $std = new \stdClass();
        $std->name = 'jack';
        $std->age = 18;
        $std->sex = '男';

        dump($std);
        die;

        $data = DB::table('wx_message')->where('keyword',16)->first();
        dump($data);
        die;
        if($data){
            echo $data->title;
        }else{
            echo 'not objec';
        }

        die;
        if($data){
            dump($data);
        }else{
            return "error";
        }



        die;
        $re = $request->method();
        if($re == "POST"){
            return "this is post method";
        }elseif ($re == "GET") {
            return "this is get method";
        }
    }
}
