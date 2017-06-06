<?php
namespace Cli\Controller;

use Common\Controller\ClibaseController;

class TestController extends ClibaseController
{

	public $area_code;
	public $school_name;

	public function _initialize()
	{
		parent::_initialize();
		import("phpQuery");
	}

	public function index()
	{
		$url_format = "http://liuxue.xdf.cn/search/xdfsearch/ssushs_search.jsp?accommodation=&ap_count=&sPage=%d&city=美国&degree=中学&major=&property=&zonghe=zonghe";

		for ($i=1;$i<=74;$i++) {
			$url = sprintf($url_format,$i);
            echo $url."\n\n";

            \phpQuery::newDocumentFile($url);
            $item_list = pq(".gu_school_con");
            $ret = [];
            foreach($item_list as $k=>$div){
                $ret[$k]['logo'] = pq($div)->find(".gu_schoolhz img")->attr("src");
                $tmp_url = pq($div)->find(".gu_school_name h3 a")->attr('onclick');
                preg_match("#http:.*html#",$tmp_url,$res);

                $ret[$k]['follow_url'] = $follow_url = $res[0];
                $ret[$k]['zh_name'] = pq($div)->find(".gu_school_name h3 a")->text();
                $ret[$k]['location'] = pq($div)->find(".gu_school_name p")->text();
                $ret[$k]['en_name'] = pq($div)->find(".gu_school_xx p:eq(0) span")->text();
                $ret[$k]['school_type'] = pq($div)->find(".gu_school_xx p:eq(1) span")->text();
                $ret[$k]['school_nature'] = pq($div)->find(".gu_school_xx p:eq(2) span")->text();
                $ret[$k]['total_fee'] = pq($div)->find(".gu_school_xx p:eq(3) span")->text();

                $cnt = M("high_school")->where(['follow_url'=>$follow_url])->count();
                if (!$cnt) {
                    $int = M("high_school")->add($ret[$k]);
                    if ($int) echo M("high_school")->getLastSql()."<br/><br/>\n\n";
                } else {
                    echo "已采集。。。。<br/>\n";
                }
                //var_dump($ret);die;
            }
		}
	}

	public function parseOneSchool()
	{
		$maxPage = $this->getMaxPage($this->area_code, $this->school_name);
		//var_dump($maxPage);die;
		//$total_arr = [];
		for ($i=1; $i<=$maxPage; $i++) {
			$page_url = $this->formatUrl($this->area_code, $this->school_name, $i);
			$page_arr = $this->parseOnePage($page_url);
			//$total_arr = array_merge($total_arr,$page_arr);
		}
	}

	private function formatUrl($area_code, $school_name, $page = 1)
	{
		$url_format = "http://yz.chsi.com.cn/zsml/querySchAction.do?ssdm=%s&dwmc=%s&mldm=&mlmc=--选择门类--&yjxkdm=&zymc=&pageno=%d";
		$url = sprintf($url_format, $area_code, $school_name,$page);
		return ($url);
	}

	private function getMaxPage($area_code, $school_name, $page = 1)
	{
		$url = $this->formatUrl($area_code, $school_name, $page);
		\phpQuery::newDocumentFile($url);

		//获取最大页码
		$page_info = pq("#page_total")->text();
		//$this->debug($page_info);
		list ($begin, $page_total) = explode("/", $page_info);
		return $page_total;
	}

	public function parseOnePage($url)
	{

		\phpQuery::newDocumentFile($url);
		echo $url."\n";
		//echo pq("title")->html();

		$tr_list = pq("div#sch_list table tbody tr");
		$arr = [];
		foreach ($tr_list as $k=>$tr) {
			$dept_name = pq($tr)->find("td:eq(0)")->text();
			$major_name = pq($tr)->find("td:eq(1)")->text();
			$research_name = pq($tr)->find("td:eq(2)")->text();
			$exam = pq($tr)->find("td:eq(5) a")->attr("href");

			preg_match('/\(([0-9A-Za-z]+)\)(.*)/',$dept_name,$res1);
			preg_match('/\(([0-9A-Za-z]+)\)(.*)/',$major_name,$res2);
			preg_match('/\(([0-9A-Za-z]+)\)(.*)/',$research_name,$res3);

			$arr[$k]['area_code'] = $this->area_code;
			$arr[$k]['school_name'] = $school_name = urldecode($this->school_name);

			$arr[$k]['dept_code'] = $dept_code = $res1['1'];
			$arr[$k]['dept_name'] = $res1['2'];

			$arr[$k]['major_code'] = $major_code = $res2['1'];
			$arr[$k]['major_name'] = $res2['2'];

			$arr[$k]['research_code'] = $research_code = $res3['1'];
			$arr[$k]['research_name'] = $res3['2'];
			$arr[$k]['exam'] = $exam;

			$arr[$k]['remark'] = json_encode([$res1[0],$res2[0],$res3[0]]);

			$is_exist = M("school_research_tmp2")
				->where(['school_name'=>$school_name,'major_code'=>$major_code,
					'research_code'=>$research_code,'dept_code'=>$dept_code])
				->find();
			if (!$is_exist) {
				M("school_research_tmp2")->add($arr[$k]);
				echo M("school_research_tmp2")->getLastSql()."\n\n";
				//echo "DO NOTHING ...\n";
			} else {
				echo $arr[$k]['school_name']."=".$arr[$k]['research_name']."已采集\n\n";
			}
		}
		return $arr;
	}

}
