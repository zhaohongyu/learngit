<?php

/**
 * 抓取http://global.beibei.com/p/1.html数据返回品牌地址数据
 * @param type $target_url http://global.beibei.com/p/1.html
 * @return array
 */
function fetch_band($target_url) {
    include_once('simple_html_dom.php');
    $html = new simple_html_dom();
    $html->load_file($target_url);
    // 查找list
    $list = array();
    // Find all <div> which attribute id=ocenBody
    /*
      foreach($html->find('a.ocen-t-item') as $post)
      {
      if($post->title){
      $item['goods_name']   = $post->title;
      $item['href']    = $post->href;
      if($post->find('div.ocen-tim-title', 0)){
      $item['country'] = $post->find('p.ocen-timtp-top', 0)->innertext.'-'.$post->find('p.ocen-timtp-bottom', 0)->innertext;
      }else{
      $item['country'] = '';
      }
      if($post->find('span.ocen-price-poso', 0)){
      $item['goods_price'] = $post->find('span.ocen-price-poso', 0)->innertext;
      }else{
      $item['goods_price'] = '';
      }
      $list[] = $item;
      }

      }
     */
    foreach ($html->find('a.ocen-y-item') as $post) {
        if ($post->title) {
            $item['band_id'] = $post->getAttribute('data-id');
            $item['goods_name'] = $post->title;
            $item['href'] = $post->href;
            if ($post->find('div.ocen-yi-address', 0)) {
                $item['country'] = $post->find('p.ocen-yia-text', 0)->innertext;
            } else {
                $item['country'] = '';
            }
            if ($post->find('span.ocen-price-v', 0)) {
                $item['goods_price'] = $post->find('span.ocen-price-v', 0)->innertext;
            } else {
                $item['goods_price'] = '';
            }
            $list[] = $item;
        }
    }
    $html->clear();
    unset($html);
    return $list;
}

/**
 * 抓取商品数据
 * @param type $target_url
 * @param type $band_id 品牌id
 * @return array
 */
function fetch_sub($target_url, $band_id) {
    include_once('simple_html_dom.php');
    $html = new simple_html_dom();
    $html->load_file($target_url);
    // 查找list
    $list = array();
    foreach ($html->find('li.view-ItemListItem') as $post) {
        $item['band_id'] = $band_id;
        $item['goods_name'] = $post->find('div.title', 0)->getAttribute('title');
        $item['href'] = $post->find('a', 0)->getAttribute('href');
        $item['country'] = $post->find('div.title span', 1)->innertext;
        $item['goods_price'] = trim($post->find('span.price-int', 0)->innertext);
        $list[] = $item;
    }
    $html->clear();
    unset($html);
    return $list;
}

/**
 * 抓取http://you.beibei.com/p/1.html页面商品数据
 * @param type $target_url http://you.beibei.com/p/1.html
 * @return array
 */
function fetch_sub2($target_url) {
    include_once('simple_html_dom.php');
    $html = new simple_html_dom();
    $html->load_file($target_url);
    // 查找list
    $list = array();
    foreach ($html->find('ul.view-ItemList li') as $post) {
        $item['goods_name'] = $post->find('div.title', 0)->innertext;
        $item['href'] = $post->find('a', 0)->getAttribute('href');
        $item['country'] = '中国';
        $item['goods_price'] = trim($post->find('em', 0)->innertext);
        $list[] = $item;
    }
    $html->clear();
    unset($html);
    return $list;
}

/**
 * 抓取蜜桃网数据
 * @param type $target_url
 * @return type
 */
function fetch_sub3($target_url) {
    $tmp = file_get_contents($target_url);
    $arr = json_decode($tmp, true);
    // 查找list
    $list = array();
    foreach ($arr['data'] as $post) {
        $item['goods_name'] = addslashes($post['name']);
        $item['href'] = '';
        $item['country'] = $post['country'];
        $item['goods_price'] = $post['totalPrice'];
        $list[] = $item;
    }
    return $list;
}

/**
 * 抓取http://www.amazon.cn/s/ref=sr_pg_1?srs=1494169071&rh=n%3A42692071&page=1&bbn=42692071&ie=UTF8&qid=1440403108页面商品数据
 * @param type $target_url 
 * @return array
 */
function fetch_amazon($target_url) {
    include_once('simple_html_dom.php');
    $html = new simple_html_dom();
    $html->load_file($target_url);
    // 查找list
    $list = array();
    foreach ($html->find('.s-result-item') as $post) {
        $item['goods_name'] = trim($post->find('h2', 0)->innertext);
        $item['href'] = $post->find('a', 0)->getAttribute('href');
        $item['country'] = '';
        $item['band'] = trim($post->find('span[class=a-size-small a-color-secondary]', 1)->innertext);
        $item['goods_price'] = trim($post->find('span[class=a-size-base a-color-price s-price a-text-bold]', 1)->innertext);
        $item['goods_price_usd'] = trim($post->find('span[class=a-size-base a-color-price s-price a-text-bold]', 0)->innertext);
        $list[] = $item;
    }
    $html->clear();
    unset($html);
    return $list;
}

/**
 * 制作输出函数 打印并退出
 */
function show_msg($msg, $color = 'green') {
    header("Content-type:text/html;charset=utf-8;");
    echo "<pre style='color:{$color};'>";
    print_r($msg);
    echo "</pre>";
    //exit("数据打印完毕");
    exit();
}

/**
 * 简化 文件写入函数,主要用来显示数据值
 * @param type $data 记录的数据
 */
function file_log($data) {
    file_put_contents("D:/MyLog.log", $data);
}

/**
 * 制作输出函数 打印并退出
 */
function show_msg_not_exit($msg, $color = 'green') {
    header("Content-type:text/html;charset=utf-8;");
    echo "<pre style='color:{$color};'>";
    print_r($msg);
    echo "</pre>";
}
