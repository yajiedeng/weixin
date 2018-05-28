<?php
/**
 * @param string $url get请求地址
 * @param int $httpCode 返回状态码
 * @return mixed 结果集
 */
function curl_get($url, &$httpCode = 0)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //不做证书校验,部署在linux环境下请改为true
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $file_contents = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $file_contents;
}

/**
 * @param string $url post请求地址
 * @param array $params 请求参数
 * @return mixed 结果集
 */
function curl_post($url, $params = array())
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt(
        $ch, CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json'
        )
    );
    $data = curl_exec($ch);
    curl_close($ch);
    return ($data);
}

/*
 * 定义接口返回格式
 * */

function responce($code='200',$msg='ok',$result='')
{
    $data = [
        'code' => (string)$code,
        'msg'  => (string)$msg,
        'data' => (string)$result
    ];
    if($result == null){
        unset($data['data']);
    }
    return $data;
}


/*
 * 获取当前域名
 * */

function getUrl()
{
    $url = url()->previous();
    return $url;
}

/*
 * 提取字符串中的数字
 * */

function findNum($str=''){
    $str=trim($str);
    if(empty($str)){return '';}
    $reg='/(\d{3}(\.\d+)?)/is';//匹配数字的正则表达式
    preg_match_all($reg,$str,$result);
    if(is_array($result)&&!empty($result)&&!empty($result[1])&&!empty($result[1][0])){
        return $result[1][0];
    }
    return '';
}