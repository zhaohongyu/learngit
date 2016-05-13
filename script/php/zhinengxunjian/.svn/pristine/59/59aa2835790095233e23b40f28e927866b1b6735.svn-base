<?php

/**
 * @Description of UploadAction
 * @author Zhao-Hong-Yu <ZhaoHongYu, zhao_hong_yu@sina.cn> 
 * @copyright Copyright (C) 2014 赵洪禹 
 * @datetime 2014-7-8  11:07:13
 * @version 1.0
 */
class UploadAction extends Action {
    /*
     * 上传图片
     * @datetime 2014-7-8  11:07:13
     */

    public function uploadImage() {
        if (!empty($_FILES)) {
            $result = $this->_upload(C("UPLOADS_PATH_IMAGES"));
            if (is_array($result)) {
                foreach ($result as $k => $v) {
                    if (!IS_SAE) {
                        //获得图片存储路径返回给客户端 取出缩略图
                        //获取文件名
                        $temp = explode("/", $v['savename']);
                        $findme = ".";
                        //去掉./Public/upload/images/中的点 ===>/Public/upload/images/
                        $v['savepath'] = substr($v['savepath'], strpos($v['savepath'], $findme) + 1);
                        $image_path = $v['savepath'] . $temp[0] . "/" . "m_" . $temp[1];
                    } else {
                        //兼容sae图片地址
                        $temp = C("TMPL_PARSE_STRING");
                        $image_path = $temp["./Public/upload/"] . "images/" . $v['savename'];
                    }
                }
                $data['imagePath'] = $image_path;
                $image = getimagesize($image_path);
                $data['width'] = $image[0];
                $data['height'] = $image[1];
                $result['status'] = array(
                    'result' => 1,
                    'errorCode' => '',
                    'errorMessage' => '',
                );
                $result['imageInfo'] = $data;
            } else {
                $result['status'] = array(
                    'result' => 0,
                    'errorCode' => '',
                    'errorMessage' => $result,
                );
            }
        } else {
            $result['status'] = array(
                'result' => 0,
                'errorCode' => '',
                'errorMessage' => "没有要上传的数据!!",
            );
        }
        die(json_encode(toString($result, 'UTF-8')));
    }

    /*
     * //上传视频 20M最大
     * @datetime 2014-7-8 16:55:48
     */

    public function uploadVideo() {
        if (!empty($_FILES)) {
            $result = $this->_upload(C("UPLOADS_PATH_VIDEOS"));
            if (is_array($result)) {
                foreach ($result as $k => $v) {
                    if (!IS_SAE) {
                        //获得视频存储路径返回给客户端 取出视频
                        //获取文件名
                        $temp = explode("/", $v['savename']);
                        $findme = ".";
                        //去掉./Public/upload/images/中的点 ===>/Public/upload/images/
                        $v['savepath'] = substr($v['savepath'], strpos($v['savepath'], $findme) + 1);
                        $video_path = $v['savepath'] . $temp[0] . "/" . $temp[1];
                    } else {
                        //兼容sae视频地址
                        $temp = C("TMPL_PARSE_STRING");
                        $video_path = $temp["./Public/upload/"] . "videos/" . $v['savename'];
                    }
                }
                $data['videoPath'] = $video_path;
                $result['status'] = array(
                    'result' => 1,
                    'errorCode' => '',
                    'errorMessage' => '',
                );
                $result['videoInfo'] = $data;
            } else {
                $result['status'] = array(
                    'result' => 0,
                    'errorCode' => '',
                    'errorMessage' => $result,
                );
            }
        } else {
            $result['status'] = array(
                'result' => 0,
                'errorCode' => '',
                'errorMessage' => "没有要上传的数据!!",
            );
        }
        die(json_encode(toString($result, 'UTF-8')));
    }

    /**
     * 上传文件
     * @param type $savePath 上传路径 视频和图片请区分放置
     * @return type
     */
    protected function _upload($savePath) {
        //监测文件夹是否存在,不存在则创建
        if (!is_dir($savePath)) {
            mkdir($savePath);
        }
        import('ORG.Net.UploadFile');
        $config['savePath'] = $savePath; // 文件路径;
        $config['maxSize'] = 20971520; //20M最大
        $config['thumb'] = true;
        $config['thumbPrefix'] = 'm_';
        $config['thumbMaxWidth'] = '300';
        $config['thumbMaxHeight'] = '300';
        $config['allowExts'] = array('jpg', 'gif', 'png', 'jpeg', "mov", "mp4", "flv", 'JPG', 'GIF', 'PNG', 'JPEG', "MOV", "MP4", "FLV");
        $config['autoSub'] = true; // 开启子目录保存
        $config['subType'] = 'date'; //// 时间保存
        $config['dateFormat'] = 'Y-m-d';
        $upload = new UploadFile($config); // 实例化上传类并传入参数
        if ($upload->upload()) {
            return $upload->getUploadFileInfo();
        } else {
            // 捕获上传异常
            return $upload->getErrorMsg();
        }
    }

    /*
      private function uploadImage2() {
      //获取用户图片流
      $input = file_get_contents("php://input");
      //获取文件上传路径
      $savePath = C("UPLOADS_PATH_IMAGES"); //./Public/upload/images/
      //生成图片名称
      $filename = date("Ymd", time()) . "-" . md5(time()) . ".jpg";
      if (!IS_SAE) {
      //生成文件夹
      $dir = date("Y-m-d", time()) . '/';
      if (!is_dir($savePath . $dir)) {
      mkdir($savePath . $dir, 0777, true);
      }
      $dest = $savePath . $dir . $filename;
      } else {
      $dest = $savePath . $filename;
      }
      //写入到指定文件夹
      $length = file_put_contents($dest, $input);
      if ($length > 0) {
      $result['status'] = array(
      'result' => 1,
      'errorCode' => '',
      'errorMessage' => '',
      );
      $result['imageInfo'] = array(
      'length' => $length, //图片大小
      'imagePath' => $dest, //图片的存储路径
      );
      } else {
      $result['status'] = array(
      'result' => 0,
      'errorCode' => '',
      'errorMessage' => '上传失败!',
      );
      }
      die(json_encode(toString($result, 'UTF-8')));
      }
     */
}
