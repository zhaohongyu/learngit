<?php
/**
 * @Description of CommonAction  公共Action
 * @author Zhao-Hong-Yu <ZhaoHongYu, zhao_hong_yu@sina.cn> 
 * @copyright Copyright (C) 2014 赵洪禹 
 * @datetime 2014年6月24日 20:51:51
 * @version 1.0
 */
class CommonAction extends Action
{
	protected $header; //客户端信息
	protected $body; //接口内容信息

	//模型类方法映射
	protected $config = array(
		'login'                     => array('class' => 'User', 'verify' => false), //登录
		'logout'                    => array('class' => 'User', 'verify' => true), //登出
		'getUserInfo'               => array('class' => 'User', 'verify' => true), //获取用户个人信息
		'getMyTask'                 => array('class' => 'User', 'verify' => true), //获取我的任务列表
		'getTaskListBySpecialty'    => array('class' => 'User', 'verify' => true), // 根据专业id获取任务列表
                'receiveTask'               => array('class' => 'User', 'verify' => true), // 领取任务
                'getMyTaskList'             => array('class' => 'User', 'verify' => true), //获取我的任务列表,已经领取
                'count'                     => array('class' => 'User', 'verify' => false), //获取指定时间内的流量
		'getNotice'                 => array('class' => 'Notice', 'verify' => true), //获取通知
		'getNoticeDetail'           => array('class' => 'Notice', 'verify' => true), //获取通知详情
		'getPositionAndDevice'      => array('class' => 'Position', 'verify' => false), //获取点位和点位下设备信息
		'addPosition'               => array('class' => 'Position', 'verify' => false), //添加点位
		'addRecord'                 => array('class' => 'Position', 'verify' => true), // 添加异常/正常巡检记录
		'addHiddenTrouble'          => array('class' => 'Position', 'verify' => false), // 添加隐患信息
		'updateHiddenTrouble'       => array('class' => 'Position', 'verify' => false), // 更新隐患信息主要更新图片路径
		'submitRecord'              => array('class' => 'Position', 'verify' => false), // 提交任务记录
		'submitRecordMultiple'      => array('class' => 'Position', 'verify' => false), // 提交任务记录--批量提交
		'checkVersion'              => array('class' => 'Update', 'verify' => false), // 检查软件版本
	);
	//错误代码
	public $error;


	public function _initialize() 
	{
		$this->error = C('ERROR_CODE'); //配置的错误代码组
		$input = file_get_contents("php://input");
		if (!$input) {
			if ($_POST && is_array($_POST)) {
				foreach ($_POST as $k => $v) {
					$input = $v;
					break;
				}
			}
		}
		$request = json_decode($input, true);
		if (!$request) $this->_error(1001);
		$this->header = isset($request['Header']) ? $request['Header'] : $this->_error(1002);
		$this->body = isset($request['Body']) ? $request['Body'] : $this->_error(1003);
	}

	//输出返回串
	protected function _export($data)
	{
		if (!$data) $this->_error(1004);
                //如果$data里面包含UserId&FlowStatisticsId就存储数据库操作.....下行流量
                $uid = intval($data['UserId']) ? intval($data['UserId']) : 0;
                $flow_statistics_id = intval($data['FlowStatisticsId']) ? intval($data['FlowStatisticsId']) : 0;
                if($uid!=0&&$flow_statistics_id!=0){
                    //计算下行流量大小
                    $json_data=json_encode(toString($data, 'UTF-8'));
                    $size = mb_strlen($json_data, 'UTF-8');
                    //因为size本身的值也会计算进去
                    $size=10+$size+mb_strlen($size, 'UTF-8');
                    $data["size"]=$size;
                    $map["id"]=$flow_statistics_id;
                    $map["uid"]=$uid;
                    $map["downstream_flow"]=$size;//下行流量大小
                    $map["time"]=time();//记录的时间
                    M("FlowStatistics")->save($map);
                }
		die(json_encode(toString($data, 'UTF-8')));
	}

	//返回错误码
	protected function _error($code)
	{
		$error['ResultRespond'] = array(
			'Result'	 	=> 0,
			'ErrorCode' 	=> $code ? $code : 0,
			'ErrorMessage' 	=> isset($this->error[$code]) ? $this->error[$code] : '严重错误！',
		);
		$this->_export($error);
	}

	/**
	 * 空操作
	 */
	public function _empty() 
	{
		//header("Content-Type: text/html;charset=utf-8");
		redirectUrl(PHP_FILE . getServerAddrAndPort());
	}
}