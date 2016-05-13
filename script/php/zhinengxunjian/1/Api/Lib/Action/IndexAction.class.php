<?php
/**
 * @Description of IndexAction  公共Action
 * @author Zhao-Hong-Yu <ZhaoHongYu, zhao_hong_yu@sina.cn> 
 * @copyright Copyright (C) 2014 赵洪禹 
 * @datetime 2014年6月24日 20:51:51
 * @version 1.0
 */

class IndexAction extends CommonAction
{
    public function index() 
    {
        //获取方法名
        $action = $this->body['Action'];
        //获取类名
        $class = isset($this->config[$action]['class']) ? $this->config[$action]['class'] : $this->_error(1005);
        //获取是否需要验证
        $verify = isset($this->config[$action]['verify']) ? $this->config[$action]['verify'] : false;
        //获取具体验证类型
        $item = isset($this->config[$action]['item']) ? $this->config[$action]['item'] : '';

        //判断方法和映射的类是否存在
        if ($action && $class) {
            $param = array_merge(array('Client' => $this->header['ClientType']),$this->header,$this->body['RequestParam']);

            //判断是否有特定的项需要验证
            if (is_array($item)) {
                foreach ($item as $k => $v) {
                    $value = isset($param[$k]) ? $param[$k] : '';
                    if ($value && $value == $v) {
                        $verify = true;
                        break;
                    }
                }
            }
            //判断是否需要登录，及登录验证
            if ($verify) {
                if ($param['UserName'] && $param['UserToken'] && $param['UserId']) {
                    $map['uid'] = $param['UserId'];
                    $map['username'] = $param['UserName'];
                    $map['token'] = $param['UserToken'];
                    $free = M('UserActionLog')->where($map)->find();
                    if (!$free) {
                        if (!$_SESSION['UserToken'] || !$_SESSION['UserId'] || !$_SESSION['UserName']) {
                            //您还没有登录
                            $this->_error(4205);
                        }
                        if ($param['UserId'] != $_SESSION['UserId'] || $param['UserName'] != $_SESSION['UserName'] || $param['UserToken'] != $_SESSION['UserToken']) {
                            //非法操作
                            $this->_error(4207);
                        }
                    } else {
                        $_SESSION['UserId']     = $free['uid'];
                        $_SESSION['UserName']   = $free['username'];
                        $_SESSION['UserToken']  = $free['token'];
                    }
                } else {
                    //1006
                    $this->_error(1006);
                }
            }
            
            //控制短时间刷新
            if ($_SESSION['time']) {
                if ((time() - $_SESSION['time']) < 0) {
                    //您的操作过于频繁，请稍后再次尝试！
                    $this->_error(200);
                } else {
                    $_SESSION['time'] = time();
                }
            } else {
                $_SESSION['time'] = time();
            }
            //计算上行流量大小
            $uid = intval($param['UserId']) ? intval($param['UserId']) : 0;
            if($uid!=0){
                $size = intval($param['Size']) ? intval($param['Size']) : 0;
                if($size==0){
                    $request_json_data=json_encode(toString($param, 'UTF-8'));
                    $size = mb_strlen($request_json_data, 'UTF-8');
                }
                $map["uid"]=$uid;
                $map["upstream_flow"]=$size;//上行流量大小
                $flow_statistics_id=M("FlowStatistics")->add($map);
                //传递给下面的接口转发到计算下行流量时使用
                $param['FlowStatisticsId']=$flow_statistics_id;
            }
            //实例化映射类取数据
            $array = D($class)->$action($param);
            //输出
            $this->_export($array);
        } else {
            //请求数据错误
            $this->_error(100);
        }
    }
}
?>