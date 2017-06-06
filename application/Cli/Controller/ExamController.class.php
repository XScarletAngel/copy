<?php
namespace Cli\Controller;

/**
 * 采集 专业的研究方向的考试科目 [pcntl 多线程分业处理]
 * @uasage php cli.php module/controller/action/key1/val1
 * @author lixiuran <lixiuran@staredu.com>
 */

use Common\Controller\ClibaseController;

class ExamController extends ClibaseController
{

	public $area_code;
	public $school_name;

	public function _initialize()
	{
		parent::_initialize();
		import("phpQuery");
	}

	function test()
	{

		set_time_limit(0);

		//如果找不到pcntl_fork函数，直接退出
		if (! function_exists('pcntl_fork')) echo "PCNTL functions not available on this PHP installation\n";

		//脚本运行开始
		$start =  time();
		echo "\nSCRIT RUN AT: ", date('Y-m-d H:i:s', $start), "\n";

		//从CLI取参数
		//默认跑10个进程
		$pmax = empty($argv[1]) ? 50 : $argv[1];
		//父进程pid
		$ppid = getmypid();
		for ($i = 1; $i <= $pmax; ++$i) {
			//开始产生子进程
			$pid = pcntl_fork();
			switch ($pid) {
				case -1:
					// fork失败
					echo "Fork failed!\n";
					break;
				case 0:
					// fork成功，并且子进程会进入到这里
					sleep(1);
					$cpid = getmypid(); //用getmypid()函数获取当前进程的PID
					echo "FORK: Child #{$i} #{$cpid} is running...\n";
					$page_size = 100;
					$this->parseOnePage($i*$page_size, $page_size);
					//子进程要exit否则会进行递归多进程，父进程不要exit否则终止多进程
					exit($i);
					break;
				default:
					// fork成功，并且父进程会进入到这里
					if ($i == 1) {
						echo "Parent #{$ppid} is running...\n";
					}
					break;
			}
		}

		//父进程利用while循环，并且通过pcntl_waitpid函数来等待所有子进程完成后才继续向下进行
		while (pcntl_waitpid(0, $status) != -1) {
			//pcntl_wexitstatus返回一个中断的子进程的返回代码，由此可判断是哪一个子进程完成了
			$status = pcntl_wexitstatus($status);
			echo "Child $status has completed!\n";
		}

		echo "Parent #{$ppid} has completed!\n";

		echo "\nSCRIT END AT: ", date('Y-m-d H:i:s', $start), "\n";
		echo "TOTAL TIMEEEE: " . (time() - $start)/60;
		echo "\n++++++++++++++++++++++++++++++++++++++++++++OK++++++++++++++++++++++++++++++++++++++++++++++++++\n";
	}

	public function index()
	{
		for ($i=0; $i<=1000*1000; $i+=1000) {

			$offset = $i;

			$rch_list = M("research")->field("id,exam_url")->where("is_crawl=1")
				->order("id asc")->limit("$offset,1000")->select();
			foreach ($rch_list as $k=>$v) {
				$exam_url = "http://yz.chsi.com.cn".$v["exam_url"];
				$this->parseExam($exam_url, $v['id']);
			}
		}
	}

	public function parseOnePage($offset, $limit)
	{
		if (empty($offset) || empty($limit)) return false;

		$rch_list = M("research")->field("id,exam_url")->where("is_crawl=1")
			->order("id asc")->limit("$offset,$limit")->select();

		foreach ($rch_list as $k=>$v) {
			$exam_url = "http://yz.chsi.com.cn".$v["exam_url"];
			$this->parseExam($exam_url, $v['id']);
		}
	}

	public function parseExam($exam_url, $sch_id)
	{
		\phpQuery::newDocumentFile($exam_url);
		//echo pq("title")->html();

		$exam_arr = [];
		$list = pq("div#result_list table tbody tr");
		if (!empty($list)) {
			$is_crawl = false;
			foreach ($list as $k=>$tr) {
				$sch_no   = pq($tr)->find("td:eq(0)")->text();
				$politics = $this->parseText(pq($tr)->find("td:eq(1)")->text());
				$lang	  = $this->parseText(pq($tr)->find("td:eq(2)")->text());
				$bus_one  = $this->parseText(pq($tr)->find("td:eq(3)")->text());
				$bus_two  = $this->parseText(pq($tr)->find("td:eq(4)")->text());

				$exam_arr[$k]['sch_id'] = $sch_id;
				$exam_arr[$k]['exam_url'] = $exam_url;
				$exam_arr[$k]['sch_no'] = $sch_no;

				$exam_arr[$k]['pol_code'] = $pol_code = $politics[1];
				$exam_arr[$k]['politics'] = $politics[2];

				$exam_arr[$k]['lang_code'] = $lang[1];
				$exam_arr[$k]['lang'] = $lang[2];

				$exam_arr[$k]['one_code'] = $bus_one[1] ? $bus_one[1]  : "";
				$exam_arr[$k]['bus_one'] = $bus_one[2] ? $bus_one[2] : "";

				$exam_arr[$k]['two_code'] = $bus_two[1] ? $bus_two[1] : '';
				$exam_arr[$k]['bus_two'] = $bus_two[2] ? $bus_two[2] : '';

				$is_exist = M("exam_subjects")->where("sch_id={$sch_id} and sch_no={$sch_no} and pol_code='{$pol_code}'")->count();

				if ($is_exist) {
					$is_crawl = true;
					echo "sch_id:{$sch_id} 已采集<br/>\n\n";
				} else {
					$int = M("exam_subjects")->add($exam_arr[$k]);
					if ($int) $is_crawl = true;
					echo M("exam_subjects")->getLastSql().";<br/>\n\n";
				}
			}

			if ($is_crawl) {
				M("research")->where(['id'=>$sch_id])->save(['is_crawl'=>2]);
			}

			unset($list,$exam_arr);
		}
	}

	public function parseText($text)
	{
		preg_match('/\(([0-9a-zA-Z]+)\)(.*)/',$text, $res);
		return $res;
	}

}
