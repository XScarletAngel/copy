<?php
namespace Common\Service;
use Think\Exception;

/**
 * 第三方渠道 创建,更新
 * http://dev.netease.im/docs/product/直播/服务端API文档
 *
 * Class ChannelsService
 * @package Common\Service
 */
class ChannelsService
{
	const API_DOMAIN 	   = 'https://vcloud.163.com/';

	const CONTET_TYPE_FORM = 'Content-Type: application/x-www-form-urlencoded';

	const CONTET_TYPE_JSON = 'Content-Type: application/json';

	public static $header 	   = [];

	public static $channelName = "";

	public static $contentType = "";

	/**
	 * 删除频道
	 *
	 * @param $cid
	 * @return bool
	 */
	public static function deleteChannel($cid)
	{
		if (empty($cid)) return false;

		$url = self::API_DOMAIN . 'app/channel/delete';

		$params = ['cid'=>$cid];

		self::setContentType(self::CONTET_TYPE_JSON);

		$ret = self::http($url, $params, true);

		$ret = json_decode($ret, true);

		return $ret['code'] == 200 ? true : false;
	}

	/**
	 * 禁用频道
	 *
	 * @param $cid
	 * @return bool
	 */
	public static function pauseChannel($cid)
	{
		if (empty($cid)) return false;

		$url = self::API_DOMAIN . 'app/channel/pause';

		$params = ['cid'=>$cid];

		self::setContentType(self::CONTET_TYPE_JSON);

		$ret = self::http($url, $params, true);

		$ret = json_decode($ret, true);

		return $ret['code'] == 200 ? true : false;
	}

	/**
	 * 获取频道状态
	 *
	 * @param $cid
	 * @return array
	 * @throws Exception
	 */
	public static function channelStats($cid)
	{
		if (empty($cid)) return [];

		$url = self::API_DOMAIN . 'app/channelstats';

		$params = ['cid'=>$cid];

		self::setContentType(self::CONTET_TYPE_JSON);

		$ret = self::http($url, $params, true);

		return $ret;
	}

	/**
	 * 设置频道为录制状态
	 *
	 * @param  string $cid			频道ID，32位字符串
	 * @param  int 	  $needRecord   1-开启录制； 0-关闭录制
	 * @param  int    $format		1-flv； 0-mp4
	 * @param  int    $duration		录制切片时长(分钟)，5~120分钟
	 * @return array
	 */
	public static function setAlwaysRecord($cid, $needRecord, $format = 1, $duration = 60)
	{
		if (empty($cid) || !in_array($needRecord, [0,1])) return false;

		$url = self::API_DOMAIN . 'app/channel/setAlwaysRecord';

		$params = ['cid'=>$cid, 'needRecord'=>$needRecord, 'format'=>$format, 'duration'=>$duration];

		self::setContentType(self::CONTET_TYPE_JSON);

		$ret = self::http($url, json_encode($params), true);

		$ret = json_decode($ret, true);

		return (is_array($ret) && $ret['code'] == 200) ? true : false;
	}

	/**
	 * 创建云直播渠道
	 *
	 * @param string  $name  渠道名称
	 * @param int	  $type  渠道类型 0
	 * @return  bool
	 */
	public static function createChannel($name, $type = 0)
	{
		if (empty($name)) $name = self::createChannelName();

		$url = self::API_DOMAIN . 'app/channel/create';

		self::setContentType(self::CONTET_TYPE_JSON);

		$params = json_encode(['name'=>$name, 'type'=>$type]);

		$ret = self::http($url, $params, true);

		return json_decode($ret, true);
	}

    /**
     * 创建网易云通信ID
     * {"code":200,"info":{"token":"32af0ac2478607d14ef4361b18dcc4fe","accid":"lihongru","name":"李鸿儒"}}
	 *
     * @param string  $name  昵称
     * @param int	  $accid accid是users表里面的主键ID
     * @return  bool
     */
    public static function createID($accid, $name)
    {

        $url = "https://api.netease.im/nimserver/user/create.action";

		self::setContentType(self::CONTET_TYPE_FORM);

        $ret = self::http($url, ['accid'=>IM_PREFIX . $accid .'_'. rand(1000, 9999), 'name'=>$name]);

        return json_decode($ret, true);
    }

    /**
     * 创建聊天室
	 * {"chatroom":{"roomid":9100046,"valid":true,"announcement":"测试公告","muted":false,"name":"直播间名称","broadcasturl":"rtmp://v41167671.live.126.net/live/e8c3c335bfa6453299babd2b2447b4b8","ext":"","creator":"lixiuran"}}
     *
     * @param string  $title     直播间名称
     * @param string  $desc      直播间简介
     * @param string  $pull_url  拉流地址rtmp
     * @param int	  $accid     accid
     * @return  bool
     */
    public static function creatRoom($title, $desc, $pull_url, $accid)
    {

        $url = "https://api.netease.im/nimserver/chatroom/create.action";

        $params = ['name'=>$title, 'announcement'=>$desc, 'broadcasturl'=>$pull_url, 'creator'=>$accid];

		self::setContentType(self::CONTET_TYPE_FORM);

      	$ret = self::http($url, $params);

        return json_decode($ret, true);
    }

    /**
     * 获取聊天室地址
	 * {"addr":["weblink04.netease.im:443"],"code":200}
     *
     * @param string  $roomid    房间ID
     * @param int	  $accid     accid
     * @return  bool
     */
    public static function getAddress($roomid, $accid)
    {

        $url = "https://api.netease.im/nimserver/chatroom/requestAddr.action";

        $params = ['roomid'=>$roomid, 'accid'=>$accid, 'clienttype'=>1];

		self::setContentType(self::CONTET_TYPE_FORM);

        $ret = self::http($url, $params);

        return json_decode($ret, true);
    }

	/**
	 * 获取当前时间戳
	 *
	 * @return int
	 */
	public static function getCurTime()
	{
		return time();
	}

	/**
	 * 获取随机数
	 *
	 * @return int
	 */
	public static function getNonce()
	{
		return mt_rand(10000,99999);
	}

	/**
	 * 获取签名 SHA1(秘密+随机数+时间戳)
	 *
	 * @return string
	 */
	public static function getHeader()
	{
		$nonce    = self::getNonce();
		$curTime  = self::getCurTime();

		$header[] = self::getContentType() ? self::getContentType() : self::CONTET_TYPE_JSON;
		$header[] = 'AppKey: '.C("NETEASE.APP_KEY");
		$header[] = 'Nonce: '.$nonce;
		$header[] = 'CurTime: '.$curTime;
		$header[] = 'CheckSum: '.SHA1(C("NETEASE.APP_SECRET").$nonce.$curTime);

		return $header;
	}

	/**
	 * 随机生成唯一的频道名称
	 * @param string $prefix
	 * @return string
	 */
	public static function createChannelName($prefix = 'channel_')
	{
		return $prefix . date("YmdHis", time())."_".mt_rand(1000,9999);
	}

	/**
	 * 发送HTTP请求方法
	 * @param  string $url    请求URL
	 * @param  bool   $multi  是否是参数模式还是json模式
	 * @param  array  $params 请求参数
	 * @param  string $method 请求方法GET/POST
	 * @return array  $data   响应数据
	 * @throws Exception
	 */
	public static function http($url, $params,  $multi = false, $method = 'POST')
	{
		//记录日志
		$log_param = is_array($params) ? http_build_query($params) : $params;
		$msg = "[ ".date("Y-m-d H:i:s",time())." ] |REQUEST: URL:{$url} | PARAM: {$log_param} \t";

		if (empty($header)) $header = self::getHeader();
		$msg .= '[HEADER: ' . json_encode($header) . "] \t";

		$opts = array(
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_HTTPHEADER     => $header
		);
		/* 根据请求类型设置特定参数 */
		switch(strtoupper($method)){
			case 'GET':
				$opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
				break;
			case 'POST':
				//判断是否传输文件
				$params = $multi ? $params : http_build_query($params);
				$opts[CURLOPT_URL] = $url;
				$opts[CURLOPT_POST] = 1;
				$opts[CURLOPT_POSTFIELDS] = $params;
				break;
			default:
				throw new Exception('不支持的请求方式！');
		}
		/* 初始化并执行curl请求 */
		$ch = curl_init();
		curl_setopt_array($ch, $opts);
		$data  = curl_exec($ch);
		$error = curl_error($ch);
		curl_close($ch);
		if($error) throw new Exception('请求发生错误：' . $error);

		$msg .= "RESPONSE: {$data}\n\n";
		file_put_contents("/tmp/live.log", $msg, FILE_APPEND);

		return  $data;
	}

	/**
	 * @return string
	 */
	public static function getChannelName()
	{
		return self::$channelName;
	}

	/**
	 * @param string $channelName
	 */
	public static function setChannelName($channelName)
	{
		self::$channelName = $channelName;
	}

	/**
	 * @return string
	 */
	public static function getContentType()
	{
		return self::$contentType;
	}

	/**
	 * @param string $contentType
	 */
	public static function setContentType($contentType)
	{
		self::$contentType = $contentType;
	}

}


/**

#创建频道
{
	"ret": {
		"httpPullUrl": "http://flv41167671.live.126.net/live/e8c3c335bfa6453299babd2b2447b4b8.flv?netease=flv41167671.live.126.net",
		"hlsPullUrl": "http://pullhls41167671.live.126.net/live/e8c3c335bfa6453299babd2b2447b4b8/playlist.m3u8",
		"rtmpPullUrl": "rtmp://v41167671.live.126.net/live/e8c3c335bfa6453299babd2b2447b4b8",
		"name": "channel_demo",
		"pushUrl": "rtmp://p41167671.live.126.net/live/e8c3c335bfa6453299babd2b2447b4b8?wsSecret=f0792ad621636c39965e4c2281c20365&wsTime=1494488749",
		"ctime": 1494488749969,
		"cid": "e8c3c335bfa6453299babd2b2447b4b8"
	},
	"code": 200
}

 */