<?php

/*
 * @description   文件上传方法
 * @author widuu  http://www.widuu.com
 * @mktime 08/01/2014
 */
$root_path = dirname(dirname(dirname(__DIR__)));
$third_config = include($root_path . '/data/conf/third.php');

global $QINIU_ACCESS_KEY;
global $QINIU_SECRET_KEY;

$QINIU_UP_HOST	= $third_config['QINIU']['QINIU_UP_HOST'];
$QINIU_RS_HOST	= $third_config['QINIU']['QINIU_RS_HOST'];
$QINIU_RSF_HOST	= $third_config['QINIU']['QINIU_RSF_HOST'];

//配置bucket为你的bucket
$BUCKET             = $third_config['QINIU']['PAPER_BUCKET'];

$QINIU_ACCESS_KEY	=  $third_config['QINIU']['ACCESS_KEY'];
$QINIU_SECRET_KEY	=  $third_config['QINIU']['SECRET_KEY'];
//配置你的域名访问地址
$HOST               =  $third_config['QINIU']['DOMAIN'][$BUCKET];

//上传超时时间
$TIMEOUT = "3600";

//保存规则
$SAVETYPE = "date";

//开启水印
//$USEWATER = false;
//$WATERIMAGEURL = "http://gitwiduu.u.qiniudn.com/ueditor-bg.png"; //七牛上的图片地址
////水印透明度
//$DISSOLVE = 50;
////水印位置
//$GRAVITY = "SouthEast";
//边距横向位置
$DX  = 10;
//边距纵向位置
$DY  = 10;

function urlsafe_base64_encode($data){
	$find = array('+', '/');
	$replace = array('-', '_');
	return str_replace($find, $replace, base64_encode($data));
}


