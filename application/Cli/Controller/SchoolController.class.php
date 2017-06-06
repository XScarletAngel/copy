<?php
namespace Cli\Controller;

/**
 * 采集 中国教育网 学校信息
 * @uasage php cli.php module/controller/action/key1/val1
 * @author lixiuran <lixiuran@staredu.com>
 */

use Common\Controller\ClibaseController;

class SchoolController extends ClibaseController
{

	public function _initialize()
	{
		parent::_initialize();
	}

	public function index()
	{
		for ($i=1;$i<100;$i++)
		{
			$url='http://souky.eol.cn/school_search_v2.php?&province=%E5%85%A8%E5%9B%BD&class=%E4%B8%8D%E9%99%90&allschool=%E4%B8%8D%E9%99%90&searchword=&order=down&flag985=0&flag211=0&flagscore=0&flagmaster=0&flaglab=0&page='.$i;
			$this->parseHtml($url);
		}
	}

	public function updIntro()
	{
		$list = M("school_tmp")->limit(1000)->select();
		foreach ($list as $k=>$v) {
			$school_id = $v['school_id'];
			$url = "http://souky.eol.cn/HomePage/school_des/{$school_id}/{$school_id}.html";

			$updRow = $this->intro($url);
			$updRow = array_map('trim', $updRow);

			$ret = M("school_tmp")->where("school_id = {$school_id}")->save($updRow);
			var_dump($ret);
		}
	}

	//获取高校编码
	public function getcode()
	{
		$url = 'http://kf.gxou.com.cn/temp/collegecode.htm';

		$c = file_get_contents($url);
		$c = $this->gbk2utf8($c);
		preg_match_all('/<UL class=tableCase[B|T]>.*?<\/UL>/is', $c, $res);
		$arr = $res[0];

		foreach ($arr as $k=>$v) {
			preg_match_all('/<LI.*>(.*)<\/LI>/isU',$v, $res2);

			$addRow = [
				'code'=>$res2[1][1],
				'name'=>$res2[1][2],
				'info'=>$res2[1][3],
			];
			$addRow = array_map('trim', $addRow);
			M("school_code_tmp")->add($addRow);
			//echo M("school_code_tmp")->getLastSql()."\n";
		}

	}

	public function intro($url)
	{
		if (empty($url)) {
			die('1111');
		}

		$c = file_get_contents($url);
		$c = $this->gbk2utf8($c);
		preg_match('/<div class="font_14 mar_t_20 page_txt">(.*)<\/div>/isU',$c, $res);
		$school_intro = strip_tags($res[1]);

		preg_match('/<div class="border overhidden pad_l_10 pad_r_10  pad_b_10 mar_t_20 font_14 line_24">(.*)<\/div>/isU',$c, $res2);
		$school_extra = trim(strip_tags($res2[1]));
		$school_extra = explode("\n", $school_extra);

		$school_url = $school_extra[1];
		$school_yz_url = $school_extra[4];
		$school_tel = str_replace('联系电话：','',$school_extra[6]);
		$school_email = str_replace('邮箱：','',$school_extra[8]);
		$school_addr = str_replace('通讯地址：','',$school_extra[10]);

		preg_match('/<h3 class="left mar_l_20">院校代码：(\d+)<\/h3>/isU',$c, $res3);
		$code = $res3[1];

		preg_match_all('/<div class="w_34 left vertical text-align">(.*?)<\/div>/is',$c, $res4);
		$school_flag = '';
		if (!empty($res4[1])) {
			$school_flag = json_encode($res4[1]);
		}

		return [
			'school_intro'=>$school_intro,
			'school_extra'=>json_encode($school_intro),
			'school_url'=>$school_url,
			'school_yz_url'=>$school_yz_url,
			'school_tel'=>$school_tel,
			'school_email'=>$school_email,
			'school_addr'=>$school_addr,
			'code'=>$code,
			'school_flag'=>$school_flag,
		];

	}

	public function parseHtml($url)
	{
		$c = file_get_contents($url);

		preg_match_all('/<div class="border s_h_180 pos_r">.*?<div\s+class="mar_t_20 souky_main">/is',$c,$res);
		$arr = $res[0];

		foreach ($arr as $k=>$v) {

			preg_match('/<h3><a href="\/HomePage\/index_(\d+).html">(.*)<\/a><\/h3>/is', $v, $res2);
			$school_id = $res2[1];
			$school_name = $this->gbk2utf8($res2[2]);

			//　　　所在城市：江苏　　　院校类型：师范类
			preg_match('/<p>(.*?)<\/p>/is', $v, $res3);
			$remark = $this->gbk2utf8($res3[1]);

			$addRow = ['school_id'=>$school_id,'school_name'=>$school_name,'remark'=>$remark];

			$res = M('school_tmp')->add($addRow);
			echo M('school_tmp')->getLastSql().";<br/>";

		}
	}

	public function gbk2utf8($str)
	{
		$charset = mb_detect_encoding($str,array('UTF-8','GBK','GB2312'));
		$charset = strtolower($charset);
		if('cp936' == $charset){
			$charset='GBK';
		}
		if("utf-8" != $charset){
			$str = iconv($charset,"UTF-8//IGNORE",$str);
		}
		return $str;
	}

	public function http_request($url, $post = '', $timeout = 5)
	{
		if( empty( $url ) ){
			return ;
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		if( $post != '' && !empty( $post ) ){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($post)));
		}
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
}
