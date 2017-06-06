<?php
/**
 * 入口文件
 * Some rights reserved：www.thinkcmf.com
 */
require './vendor/autoload.php';

if (ini_get('magic_quotes_gpc')) {
	function stripslashesRecursive(array $array){
		foreach ($array as $k => $v) {
			if (is_string($v)){
				$array[$k] = stripslashes($v);
			} else if (is_array($v)){
				$array[$k] = stripslashesRecursive($v);
			}
		}
		return $array;
	}
	$_GET = stripslashesRecursive($_GET);
	$_POST = stripslashesRecursive($_POST);
}
// 检测环境变量
$env = isset($_SERVER['APPLICATION_ENV']) ? $_SERVER['APPLICATION_ENV'] : 'development';
if (!in_array($env, array('development', 'testing', 'production'))){
	exit('can not identify environment');
}
// 分环境定义
switch ($env) {
	// 开发环境
	case 'development':
		define('IM_PREFIX', "xkht_test_");
		define('APP_DEBUG', true);
		break;
	// 测试环境
	case 'testing':
		define('IM_PREFIX', "xkht_test_");
		define('APP_DEBUG', true);
		break;
	// 生产环境
	case 'production':
		define('IM_PREFIX', "xkht_online_");
		define('APP_DEBUG', false);
		break;
}

//网站当前路径
define('SITE_PATH', dirname(__FILE__)."/");
//项目路径，不可更改
define('APP_PATH', SITE_PATH . 'application/');
//项目相对路径，不可更改
define('SPAPP_PATH',   SITE_PATH.'framework/');
//应用目录
define('SPAPP',   './application/');
//项目资源目录，不可更改
define('SPSTATIC',   SITE_PATH.'statics/');
//定义缓存存放路径
define("RUNTIME_PATH", SITE_PATH . "data/runtime/");
//静态缓存目录
define("HTML_PATH", SITE_PATH . "data/runtime/Html/");

define("THINKCMF_CORE_TAGLIBS", 'cx,Common\Lib\Taglib\TagLibSpadmin,Common\Lib\Taglib\TagLibHome');

//uc client root
define("UC_CLIENT_ROOT", './api/uc_client/');

if(file_exists(UC_CLIENT_ROOT."config.inc.php")){
	include UC_CLIENT_ROOT."config.inc.php";
}

//开启性能调试
if(isset($_GET) && isset($_GET['xhprof_debug'])){
	xhprof_enable(XHPROF_FLAGS_NO_BUILTINS | XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);
}


define('API_URL', 'http://api-test.xkhtedu.com/api'); //api 加密key
define('API_AES_KEY', 'E82ZU7K9BO&kUYZAE82ZU7K9BO&kUYZA');  //api 加密key
define('PASSWORD_KEY', 'E82ZU7K9BO&kUYZAE82ZU7K9BO&kUYZA'); //密码加密key
define('API_SECURITY_KEY', 'NTk5MWNiM2VmNTEyZGI3NWZiYzFlZTgxZWI0NWUxYWY='); //api header security

//载入框架核心文件
require SPAPP_PATH.'Core/ThinkPHP.php';

