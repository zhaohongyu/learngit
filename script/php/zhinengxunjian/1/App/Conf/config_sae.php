<?php

return array(
    'TMPL_PARSE_STRING' => array(
        // __PUBLIC__/upload  -->  /Public/upload -->http://appname-public.stor.sinaapp.com/upload
        './Public/upload/' => sae_storage_root('Public') . '/upload/',
    ),
    /*
     * DUBUG调试模式
     */
	 /*
    'TMPL_CACHE_ON' => false, // 默认开启模板编译缓存 false 的话每次都重新编译模板
    'ACTION_CACHE_ON' => false, // 默认关闭Action 缓存
    'HTML_CACHE_ON' => false, // 默认关闭静态缓存
    'SHOW_PAGE_TRACE' => false,
    'SHOW_ADV_TIME' => false, // 关闭详细的运行时间
    'SHOW_RUN_TIME'=> false,// 运行时间显示
    'SHOW_DB_TIMES'=> false,// 显示数据库查询和写入次数
    'SHOW_CACHE_TIMES'=> false,// 显示缓存操作次数
    'SHOW_USE_MEM'=> false,// 显示内存开销
    */
    /*
     * 数据库常用配置
     *
     *
     */
    'DB_TYPE' => 'mysql', // 数据库类型
    'DB_HOST' => 'w.rdc.sae.sina.com.cn', // 数据库服务器地址
    'DB_NAME' => 'app_zhinengxunjian', // 数据库名
    'DB_USER' => 'zj00nlxk0w', // 数据库用户名
    'DB_PWD' => '415xy534kx1kkh1yl45z3mx0k4kwhx5ihjy4hx1l', // 数据库密码
    'DB_PORT' => 3307, // 数据库端口
    'DB_PREFIX' => 'in_', // 数据库表前缀
    'DB_CHARSET' => 'utf8', // 数据库编码
    'DB_FIELDS_CACHE' => false, // 启用字段缓存

    /*
     * URL路由模式
     *
     * URL_MODEL:0 普通模式 1 PATHINFO 2 REWRITE 3 兼容模式 当URL_DISPATCH_ON开启后有效
     * URL_PATHINFO_MODEL:普通模式1 参数没有顺序;智能模式2 自动识别模块和操作
     *
     * 开启二级域名
      'APP_SUB_DOMAIN_DEPLOY' => true,
      'APP_SUB_DOMAIN_RULES'  => array(
      'home'  => array('home/'),
      'admin' => array('admin/'),
      'user'  => array('user/'),
      'pay'   => array('pay/'),
      ),
     *
     */
    'URL_MODEL' => 2,
    'URL_PATHINFO_MODEL' => 2,
    'URL_ROUTER_ON' => true, //是否开启路由
    'URL_PATHINFO_DEPR' => '/', //连接符
    'URL_CASE_INSENSITIVE' => true, //URL智能区分大小写配置
    //'COOKIE_DOMAIN'	=>	'.zhinengxunjian.sinaapp.com',			//cookie域,请替换成你自己的域名 以.开头

    /*
     * 模板文件后缀
     */
    'TMPL_TEMPLATE_SUFFIX'	=> '.html',
    'TMPL_ACTION_ERROR' => 'Public:success',
    'TMPL_ACTION_SUCCESS' => 'Public:success',
    
    /*
     * 设置语言包
     */
    'LANG_SWITCH_ON' => true,
    'DEFAULT_LANG' => 'zh-cn', // 默认语言
    'LANG_AUTO_DETECT' => true, // 自动侦测语言
    
    /**
     * 修改定界符
     */
    "TMPL_L_DELIM" => "<{", //模板引擎普通标签开始标记 
    "TMPL_R_DELIM" => "}>", //模板引擎普通标签结束标记
    
    /*
     * 令牌验证
     */
    'TOKEN_ON'=>false,// 是否开启令牌验证
    'TOKEN_NAME'=>'__hash__',// 令牌验证的表单隐藏字段名称
    'TOKEN_TYPE'=>'md5',  //令牌哈希验证规则 默认为MD5
    
    /*
     * 缓存配置
     */
    //'DATA_CACHE_TYPE' => 'Memcache',  				//默认是file方式进行缓存的，修改为memcache
    //'MEMCACHE_HOST'   => 'tcp://127.0.0.1:11211',                 //memcache服务器地址和端口，这里为本机。
    //'DATA_CACHE_TIME' => '10',  					//过期的秒数。
    'DATA_CACHE_TYPE' => 'file', //数据缓存方式
    'DATA_CACHE_SUBDIR' => false, //启用哈希子目录缓存的方式
    'DATA_PATH_LEVEL' => 2, //目录层级
    'LOG_RECORD_LEVEL' => array('SQL'),
    
    /* 
     * 分页设置 
     */
    'PAGE_ROLLPAGE' => 5, // 分页显示页数
    'PAGE_LISTROWS' => 15, // 分页每页显示记录数
    
    /*
     * 日志级别
     */
    'LOG_RECORD_LEVEL' => array('EMERG', 'ALERT', 'CRIT', 'ERR'), // 允许记录的日志级别
    
    /**
     * RBAC
     */
    'USER_AUTH_ON' => true,
    'USER_AUTH_TYPE' => 2, // 默认认证类型 1 登录认证 2 实时认证
    'USER_AUTH_KEY' => 'authId', // 用户认证SESSION标记
    'ADMIN_AUTH_KEY' => 'administrator',
    'USER_AUTH_MODEL' => 'User', // 默认验证数据表模型
    'AUTH_PWD_ENCODER' => 'md5', // 用户认证密码加密方式
    'USER_AUTH_GATEWAY' => '/Public/login', // 默认认证网关
    'NOT_AUTH_MODULE' => 'Public', // 默认无需认证模块
    'REQUIRE_AUTH_MODULE' => '', // 默认需要认证模块
    'NOT_AUTH_ACTION' => '', // 默认无需认证操作
    'REQUIRE_AUTH_ACTION' => '', // 默认需要认证操作
    'GUEST_AUTH_ON' => false, // 是否开启游客授权访问
    'GUEST_AUTH_ID' => 0, // 游客的用户ID
    'RBAC_ROLE_TABLE' => 'in_role',
    'RBAC_USER_TABLE' => 'in_role_user',
    'RBAC_ACCESS_TABLE' => 'in_access',
    'RBAC_NODE_TABLE' => 'in_node',
    //'RBAC_ERROR_PAGE'=>  '/Public/loginout',//定义权限错误页面
);
