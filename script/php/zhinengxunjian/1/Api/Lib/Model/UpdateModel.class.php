<?php

/**
 * Description of UpdateModel
 *
 * @author zhaohyg
 * @date 2014-7-14 18:25:24
 */
class UpdateModel extends Model {
    /*
     * 检查版本
     */

    public function checkVersion($data) {
        //服务端版本文件路径
        $version_file_path = parse_ini_file(C("VERSION_FILE_PATH"));
        $server_version = $version_file_path["version"]; //服务端版本号
        $client_version = $data["ClientVersion"]; //客户端版本号
        $message = '';
        $hasNewVersion = '';
        $update_file_path = '';
        if ($server_version == $client_version) {
            $message = "已经是最新版本";
            $hasNewVersion = 0;
        } else {
            $message = "有新版本了,是否要更新";
            $hasNewVersion = 1;
            $update_file_path = SITE_URL . C("UPDATE_FILE_PATH");
        }
        $result["version"] = array(
            'hasNewVersion' => $hasNewVersion,
            'serverVersion' => $server_version,
            'clientVersion' => $client_version,
            'message' => $message,
            'updateFilePath' => $update_file_path,
        );
        return $result;
    }

}
