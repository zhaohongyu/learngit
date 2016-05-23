<?php
require 'vendor/autoload.php';
define('HOST_4493', 'http://www.4493.com');
$app = new Slim\App();
$app->get('/imgCategoryList', 'imgCategoryList');
$app->get('/imgSubList/{category_en}/{page}', 'imgSubList');
$app->post('/imgDetailList', 'imgDetailList');
$app->run();

function response_format($code, $msg = '', $data = array()) {
    if (!is_numeric($code)) {
        return '';
    }
    $result = array(
        'errorno' => $code,
        'msg'     => $msg,
        'data'    => $data,
    );
    return $result;
}

/**
 * 获取详情
 */
function imgDetailList($request, $response, $args) {
    require 'Fetch.php';
    $fetch   = new Fetch();
    $sub_url = $_REQUEST['href'];
    if (empty($sub_url)) {
        $res_data = response_format(-1, 'href不能为空');
    } else {
        $n = 0;
        while ($n < 50) {
            $res = $fetch->fetch_4493_single($sub_url);
            if (empty($res)) {
                break;
            }
            $tmp[$n]['href']   = $sub_url;
            $tmp[$n]['imgUrl'] = $res;
            $sub_url           = $fetch->get_4493_sub_next_page($sub_url);
            $n++;
        }
        if (empty($tmp)) {
            $res_data = response_format(-1, '获取数据失败');
        } else {
            $res_data = response_format(0, '获取数据成功', $tmp);
        }
    }
    $response->withJson($res_data);
    return $response;
}

/**
 * 获取指定分类下的图片列表
 */
function imgSubList($request, $response, $args) {
    require 'Fetch.php';
    $fetch       = new Fetch();
    $category_en = $args['category_en'] ? $args['category_en'] : 'siwameitui';
    $page        = $args['page'] ? $args['page'] : 1;
    $target_url  = sprintf('%s/%s/index-%d.htm', HOST_4493, $category_en, $page);
    $imgSubList  = $fetch->fetch_4493($target_url);
    if (empty($imgSubList)) {
        $res_data = response_format(-1, '获取数据失败');
    } else {
        $res_data = response_format(0, '获取数据成功', $imgSubList);
    }
    $response->withJson($res_data);
    return $response;
}

/**
 * 获取图片分类
 */
function imgCategoryList($request, $response, $args) {
    $category = array(
        array(
            'categoryEn'    => 'siwameitui',
            'categoryTitle' => '丝袜美腿',
        ),
        array(
            'categoryEn'    => 'xingganmote',
            'categoryTitle' => '性感美女',
        ),
        array(
            'categoryEn'    => 'weimeixiezhen',
            'categoryTitle' => '唯美写真',
        ),
        array(
            'categoryEn'    => 'wangluomeinv',
            'categoryTitle' => '网络美女',
        ),
        array(
            'categoryEn'    => 'gaoqingmeinv',
            'categoryTitle' => '高清美女',
        ),
        array(
            'categoryEn'    => 'motemeinv',
            'categoryTitle' => '模特美女',
        ),
        array(
            'categoryEn'    => 'tiyumeinv',
            'categoryTitle' => '体育美女',
        ),
        array(
            'categoryEn'    => 'dongmanmeinv',
            'categoryTitle' => '动漫美女',
        ),
    );
    $res_data = response_format(0, '获取数据成功', $category);
    $response->withJson($res_data);
    return $response;
}

