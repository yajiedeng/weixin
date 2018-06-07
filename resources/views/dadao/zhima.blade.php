<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>认证信息</title>
    <link href="{{URL::asset('/dadao/css/zhima.css')}}" rel="stylesheet" type="text/css">
</head>

<body>
<div id="boy">
    <header>认证信息</header>
    <section>
        <p>
            <span>姓名:</span>
            <input type="text" id="username" placeholder="请输入真实姓名">
        </p>
    </section>
    <section>
        <p>
            <span>身份证:</span>
            <input type="text" id="identityCardNum" placeholder="请输入身份证号">
        </p>
    </section>
    <section>
        <p>
            <span>手机号:</span>
            <input id="phoneNum" type="text" placeholder="请输入手机号">
        </p>
    </section>
    <section>
        <p class="ppp">
            <span>验证码:</span>
            <input type="text" id="userCode" placeholder="请输入验证码">
            <span id="code" onclick='aa()' class="green"> 获取验证码</span>
        </p>
    </section>
    <section class="sen">
        <button id="but" class="butOff">提交</button>
    </section>
</div>

<div class="footer">
    <p>此信息仅用于认证
        <br> 大道用车将保护您的个人隐私泄漏
    </p>

</div>
<div class="foo">
    <span class="let"></span>
    <span id="l"></span>
    <span class="rig"></span>
</div>
</body>
<script src="{{URL::asset('/dadao/js/jquery-1.8.3.min.js')}}"></script>
<script src="{{URL::asset('/dadao/js/md5.js')}}"></script>
<script>
    (function (win, doc) {
        //浏览器缩放时
        win.onresize = function () {
            change();
        };
        change();
        function change() {
            var oFs = doc.documentElement.clientWidth / (375 / 100);
            doc.documentElement.style.fontSize = oFs + 'px';
        }
    })(window, document);

    var count = 60;
    var flag = true;
    var onOff = true;
    var  url = 'https://incar-dev2.mydadao.com'
    var timestamp = (new Date()).valueOf();
    var platform = '3'
    var sign = timestamp + "incar" + platform
    var entrysign = MD5(sign)

    var appVersion = '1.2.8'
    var phoneSysVersion = 10;
    var device_brand = 10;
    var phoneModel = 10;
    function aa() {
        if (/1[2|3|4|5|6|7|8][0-9]{9}/.test($('#phoneNum').val())) {
            if (flag) {
                flag = false;
                timer = setInterval(setTime, 1000);
                setPhone()
            } else {
                alert('请勿重复点击')
            }
        } else {
            alert('请输入正确的手机号')
        }




    }
    //发送验证码
    function setPhone() {

        $.ajax({
            type: "post",
            url: url + "/message/send-message.json",
            data: {
                phoneNum: $('#phoneNum').val(),
                timestamp: timestamp,
                sign: entrysign,
                platform: platform,
                appVersion: appVersion,
            },
            success: function (data) {
                if (data.status == 200) {
                } else {
                    alert(data.message)
                }
            },
            error: function () {
                alert("网络错误请稍后重试！")

            }
        });
    }
    //倒计时
    function setTime() {

        if (count == 0) {
            window.clearInterval(timer);//停止计时器
            $("#code").removeClass("gray").addClass('green');//启用按钮
            $("#code").html("重新发送");
            count = 60;
            flag = true;
        }
        else {
            count--;
            $("#code").removeClass("green").addClass('gray')
            $("#code").html(count + "s");
        }
    }



    $('#username').bind('input propertychange', function () {

        if (/\d{4}/.test($('#userCode').val()) && /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/.test($('#identityCardNum').val()) && /1[2|3|4|5|6|7|8][0-9]{9}/.test($('#phoneNum').val()) && /^[\u4e00-\u9fa5]{2,6}$/.test($('#username').val())) {
            $('#but').removeClass('butOff').addClass('butOn')

        } else {
            $('#but').removeClass('butOn').addClass('butOff')
        }
    });
    $('#phoneNum').bind('input propertychange', function () {
        if (/\d{4}/.test($('#userCode').val()) && /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/.test($('#identityCardNum').val()) && /1[2|3|4|5|6|7|8][0-9]{9}/.test($('#phoneNum').val()) && /^[\u4e00-\u9fa5]{2,6}$/.test($('#username').val())) {
            $('#but').removeClass('butOff').addClass('butOn')

        } else {
            $('#but').removeClass('butOn').addClass('butOff')
        }

    });
    $('#identityCardNum').bind('input propertychange', function () {
        if (/\d{4}/.test($('#userCode').val()) && /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/.test($('#identityCardNum').val()) && /1[2|3|4|5|6|7|8][0-9]{9}/.test($('#phoneNum').val()) && /^[\u4e00-\u9fa5]{2,6}$/.test($('#username').val())) {
            $('#but').removeClass('butOff').addClass('butOn')

        } else {
            $('#but').removeClass('butOn').addClass('butOff')
        }
    });
    $('#userCode').bind('input propertychange', function () {

        if (/\d{4}/.test($('#userCode').val()) && /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/.test($('#identityCardNum').val()) && /1[2|3|4|5|6|7|8][0-9]{9}/.test($('#phoneNum').val()) && /^[\u4e00-\u9fa5]{2,6}$/.test($('#username').val())) {
            $('#but').removeClass('butOff').addClass('butOn')

        } else {
            $('#but').removeClass('butOn').addClass('butOff')
        }
    })
    $('#but').click(function () {
        if (onOff) {
            if (/\d{4}/.test($('#userCode').val()) && /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/.test($('#identityCardNum').val()) && /1[2|3|4|5|6|7|8][0-9]{9}/.test($('#phoneNum').val()) && /^[\u4e00-\u9fa5]{2,6}$/.test($('#username').val())) {
                onOff = false;

                $.ajax({
                    type: 'POST',
                    url: url + '/user/userSesameAuthenticationWeChat',
                    data: {
                        userName: $("#username").val(),
                        identityCardNum: $('#identityCardNum').val(),
                        simCode: $('#userCode').val(),
                        phoneNum: $('#phoneNum').val()

                    },
                    success: function (data) {
                        if (data.status == 200) {
                            var tbUrl = data.attachment.url;
                            location.href = tbUrl;
                        } else {
                            alert(data.message)
                        }
                    }
                })



            } else {
                alert('请填写正确的信息')
            }
            onOff = true;
        } else {
            alert('请勿重复点击')
        }
    })
</script>

</html>