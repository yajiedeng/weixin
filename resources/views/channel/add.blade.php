<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>生成二维码</title>
    <style>
        .from{
            width:100%;
            display: flex;
            height: 60px;
            margin: 0 auto;
            padding:60px 0;
            background: #f5f5f5;
        }
        .from-item{
            width: auto;
            height: 60px;
            margin: 0 auto;
            padding: 0 20px;
            line-height: 60px;
            display: flex;
            justify-content: space-between;
            color: #222;
            font-size: 14px;
            font-family: "Courier New", Courier, mono;
        }
        .item-text{
            width: 40px;
        }
        .item-input{
            width:240px;
            height: 40px;
            margin-top: 10px;
            border:1px solid #aaa;
            text-indent: 10px;
            margin-left: 10px;
        }
        .submit{
            width:60px;
            height: 40px;
            margin-top:10px;
            border: 0;
            background: #2ab27b;
            color: #fff;
            letter-spacing: 1px;
            margin-left:15px;
            border: 1px solid #2ab27b;
        }
        .img-box{
            width: 100%;
            background: #f5f5f5;
            padding-bottom: 60px;
            display: none;
        }
        .img{
            width: 240px;
            margin: 0 auto;
        }
        .qrcode{
            width: 240px;
            height: 240px;
            margin-top: 20px;
        }
        .download{
            width:120px;
            height: 42px;
            line-height: 42px;
            background: #2ab27b;
            color: #fff;
            border:none;
            letter-spacing: 1px;
            display: block;
            margin: 20px auto;
        }
    </style>
</head>
<body>
    <div class="from">
        <div class="from-item">
            <div class="item-text">姓名</div>
            <input class="item-input" name="userName" type="text" placeholder="请输入渠道人员姓名" required>
            <input class="item-input" name="vcode" type="text" placeholder="请输入验证码" required>
            <button class="submit" type="button">添加</button>
            {{ csrf_field() }}
        </div>
    </div>
    <div class="img-box">
        <div class="img">
            <img class="qrcode" src="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=gQEd8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyTkhhc1VMNWVlWjMxMDAwMHcwN0gAAgTbOPFaAwQAAAAA" />
        </div>
        {{--<a href="" class="download">点击下载</a>--}}
    </div>
    <script src="{{URL::asset('/js/jquery.js')}}"></script>
    <script src="{{URL::asset('/layer/layer.js')}}"></script>
    <link rel="stylesheet" href="{{URL::asset('/layer/theme/default/layer.css?v=3.1.1')}}">
    <script>
        $(function () {
            var qrcode = '';
            $('.submit').click(function () {
                var userName = $("input[name=userName]").val();
                layer.load(1, {
                    shade: [0.1,'#fff'] //0.1透明度的白色背景
                });
                $.post("{{url('channel/docreate')}}",{userName:userName,'_token': '{{ csrf_token() }}'},function (data) {
                    if(data.code == 1){
                        qrcode = data.resData;
                        $('.qrcode').attr('src',data.resData);
                        $('.img-box').show();
                    }else{
                        layer.msg(data.msg);
                    }
                    layer.closeAll("loading");
                });
            });
            //下载图片
            $('.download').click(function(){
                var url = qrcode;
                var $a = $("<a></a>").attr("href", url).attr("download", "img.jpg");
                $a[0].click();
            });
        });
    </script>
</body>
</html>