<?php
/**
 * 考试成绩管理
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\ClassNoticeService;
use Common\Service\StuscoreService;

class StuscoreController extends AdminbaseController 
{
	/**
	 * 考试成绩列表
	 */
	public function index()
	{
		$zone_id        = I('request.zone_id');
		$sub_zone_id    = I('request.sub_zone_id');
		$class_type     = I('request.class_type');
		$class_con      = I('request.class_con');
		$user_type      = I('request.user_type');
		$user_con       = I('request.user_con');
		$subject_name   = I('request.subject_name');
		$course_type    = I('request.course_type');
		$course_con     = I('request.course_con');
		$paper          = I('request.paper');
		$paper_con      = I('request.paper_con');
		$paper_type     = I('request.paper_type');
		$paper_way      = I('request.paper_way');
		$start_time     = I('request.start_time');
		$end_time       = I('request.end_time');

		$class          = new ClassNoticeService();
    	$school         = $class->zone();
    	$score          = new StuscoreService();
    	$result         = $score->getall($zone_id, $sub_zone_id, $class_type, $class_con, $user_type, $user_con, $subject_name, $course_type, $course_con, $paper, $paper_con, $paper_type, $paper_way, $start_time, $end_time);
    	$this->assign('school', $school);
    	$this->assign('data', $result['data']);
    	$this->assign('page', $result['page']);
		$this->display();
	}

	/**
     * 修改分数
     */
    public function score()
    {
        $id     = I('request.id');     // 公告id
        $value  = I('request.value');     // 公告id
        $score  = new StuscoreService();
        $result = $score->score($id, $value);
        if ($result) {
            $this->ajaxReturn(['msg'=>'修改成功～','code'=>1]);
        } else {
            $this->ajaxReturn(['msg'=>'修改失败～','code'=>2]);
        }
    }

    /**
     * 添加单条数据
     */
    public function add()
    {
    	$class  = new ClassNoticeService();
    	$school = $class->zone();
    	$this->assign('school', $school);
        $this->display();
    }

    /**
     * 添加单条数据处理
     */
    public function add_post()
    {
        $zone_id     = I("post.zone_id");
        $sub_zone_id = I("post.sub_zone_id");
        $classid     = I("post.class");
        $paperid     = I("post.paper");
        $user        = I("post.user");
        $sco         = I("post.score");

        $score       = new StuscoreService();
        $result      = $score->add_post($zone_id, $sub_zone_id, $classid, $paperid, $user, $sco);
        if($paperid != '' && $user != '' && $score != ''){
            if($result != 2){
                if($result){
                    $this->success("添加成功", U('Stuscore/index'));
                } else {
                    $this->error("发布失败");
                }
            } else {
                $this->error("填写的学员信息错误，查无此人，请重新添加");
            }
        } else {
            $this->error("添加失败，请填写完整信息");
        }
    }

    /**
     * 获取试卷信息（一张试卷只属于一个课程，一个课程有多张试卷，一个课程可以组成多个班级，一个班级只能有一个课程）
     */
    public function getpaper()
    {
        $class_id = I('request.class_id');     // 班级id
        $score    = new StuscoreService();
        $paper    = $score->getpaper($class_id);
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($paper));
        $this->ajaxReturn(array_values($paper));
    }

    /**
     * 批量添加数据
     */
    public function batchadd()
    {
        $class  = new ClassNoticeService();
        $school = $class->zone();
        $this->assign('school', $school);
        $this->display();
    }

    /**
     * 批量添加获取这个班级的所有用户信息
     */
    public function getuser()
    {
        $class_id = I('request.class_id');     // 班级id
        $score    = new StuscoreService();
        $paper    = $score->getuser($class_id);
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($paper));
        $this->ajaxReturn(array_values($paper));
    }

    /**
     * 批量数据处理
     */
    public function batchadd_post()
    {
        $zone_id     = I("post.zone_id");
        $sub_zone_id = I("post.sub_zone_id");
        $classid     = I("post.class");
        $paperid     = I("post.paper");
        $answer_time = I("post.answer_time");
        $sco         = I("post.score");
        $account     = I("post.user_account");
        $user_no     = I("post.user_no");
        $user_name   = I("post.user_name");

        $score       = new StuscoreService();
        $result      = $score->batchadd_post($zone_id, $sub_zone_id, $classid, $paperid, $answer_time, $sco, $account, $user_no, $user_name);
        if($paperid != ''){
            if($result){
                $this->success("添加成功", U('Stuscore/index'));
            } else {
                $this->error("添加失败");
            }
        } else {
            $this->error("添加失败，请填写完整信息");
        }
    }
}