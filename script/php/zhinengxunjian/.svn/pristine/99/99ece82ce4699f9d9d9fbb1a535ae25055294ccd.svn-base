<?php

/**
 * 使用curl方法用来获取接口返回值
 * 返回的信息中可能存在bom头信息，所以使用trim($str, chr(239).chr(187).chr(191))解决
 * @author hushangming
 */
class CurlUtil {

    /**
     * 通过get方法获取$url对应的返回值
     * @param string $url
     * @param string $jsonstr
     * @param boolean $isjson 传递的参数是否为json格式
     * @return ArrayIterator
     */
    public static function getInfoByGet($url, $jsonstr = null, $isjson = false, $params = array()) {
        // 初始化CURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        if ($isjson === true) {
            // 设置请求格式为json
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($jsonstr)
            ));
        }
        if ($jsonstr) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonstr);
        }
        // 头部信息不获取
        curl_setopt($ch, CURLOPT_HEADER, 0);
        // 返回原生的（Raw）输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        //如果需要向平台post信息则设置超时时间
        if (isset($params['timeout']) && $params['timeout'] > 0) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $params['timeout']);
        } else {
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        }
        // 执行并获取返回结果
        $content = curl_exec($ch);
        // 关闭CURL
        curl_close($ch);

        return self::dealBom($content);
    }

    /**
     * 通过post方法获取$url对应的返回值，$postArr为提交的数据
     * @param string $url
     * @param array|string $postArr
     * @param boolean $isjson 传递的参数是否为json格式
     * @return ArrayIterator
     */
    public static function getInfoByPost($url, $postArr, $isjson = false) {
        // 初始化CURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        if ($isjson === true) {
            // 设置请求格式为json
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($postArr)
            ));
        } else {
            // 设置提交方式
            curl_setopt($ch, CURLOPT_POST, count($postArr));
        }
        // 传递信息
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postArr);
        // 头部信息不获取
        curl_setopt($ch, CURLOPT_HEADER, 0);
        // 返回原生的（Raw）输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        // 执行并获取返回结果
        $content = curl_exec($ch);
        // 关闭CURL
        curl_close($ch);

        return self::dealBom($content);
    }

    /**
     * 将$content中可能出现的bom信息清除，否则使用json_decode解析时会出现空白情况
     * @param string $content
     * @return string
     */
    protected static function dealBom($content) {
        return json_decode(trim($content, chr(239) . chr(187) . chr(191)), true); // 0xEF 0xBB 0xBF
    }

}
