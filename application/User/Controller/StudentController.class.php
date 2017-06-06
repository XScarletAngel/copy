<?php
/**
 * 参    数：
 * 作    者：lhr
 * 功    能：用户管理
 * 修改日期：2017-04-13
 */
namespace User\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\ClassNoticeService;
use Common\Service\StudentService;
use Common\Service\ResearchService;

class StudentController extends AdminbaseController {

    const USER_REG      = 0; //注册用户
    const USER_STU      = 1; //学生
    const USER_TCH      = 2; //老师
    const USER_SUT_TCH  = 3; //学生+老师

	/*
	 *用户信息查询
	 */
	public function index()
	{
		$usertype    = I("post.usertype");
		$user_con    = I("post.user_con");
		$zone_id     = I("post.zone_id");
		$sub_zone_id = I("post.sub_zone_id");
		$teacher     = I("post.teacher");
		$start_time  = I("post.start_time");
		$end_time    = I("post.end_time");

		$class       = new ClassNoticeService();
    	$school      = $class->zone();
    	$stu         = new StudentService();
    	$result      = $stu->getAll($usertype, $user_con, $zone_id, $sub_zone_id, $teacher, $start_time, $end_time);
    	$this->assign("school", $school);
    	$this->assign("data", $result['data']);
    	$this->assign("page", $result['page']);
		$this->display();
	}

    /*
     *编辑学员
     */
    public function edit()
    {
        $id     = I("request.id");  // 接受的用户表的ID
        $class  = new ClassNoticeService();
        $school = $class->zone();
        $res    = new ResearchService;
        $sch    = $res->getschool();
        $stu    = new StudentService();
        $data   = $stu->edit($id);

        $this->assign('sch', $sch);
        $this->assign("school", $school);
        $this->assign("data", $data['data']);   // users表信息
        $this->assign("sarr", $data['sarr']);   // stu表信息
        $this->display();
    }

    /*
     *新增学员数据处理
     */
    public function edit_post()
    {
        if(IS_POST){
            $data   = $_POST;
            unset($data['school_id']);
            unset($data['dept_id']);
            unset($data['major_id']);
            unset($data['research_id']);
            $data['dst_school'] = $data['school_name'];unset($data['school_name']);
            $data['dst_dept'] = $data['dept_name'];unset($data['dept_name']);
            $data['dst_major'] = $data['major_name'];unset($data['major_name']);
            $data['dst_research'] = $data['research_name'];unset($data['research_name']);

            if( $data['user_login'] == '' ||
                $data['user_name'] == '' ||
                $data['zone_name'] == '' ||
                $data['sub_zone_name'] == '' ||
                $data['tch_name'] == '' ||
                $data['enter_time'] == '' ||
                $data['parent_phone'] == ''
            ){
                $this->error("添加失败,请填写完整数据");
            } else {
                // echo "<pre>";print_r($data);die; || $data['serial'] == '' || $data['sub_serial'] == ''
                $stu    = new StudentService();
                $result = $stu->edit_post($data);
                if(false !== $result){
                    $this->success("编辑成功", U('Student/index'));
                } else {
                    $this->error("编辑失败");
                }
            }
        } else {
            $this->error("提交信息错误，请重新提交");
        }
    }

	/*
	 *新增学员
	 */
	public function add()
	{
		$class  = new ClassNoticeService();
    	$school = $class->zone();
		$day    = date('Y-m-d',time());
    	$stu    = new StudentService();
    	$u_arr  = $stu->getone();
        $res    = new ResearchService;
        $sch    = $res->getschool();

        $this->assign('sch', $sch);
    	$this->assign('username', $u_arr['user_nicename']);
    	$this->assign('day', $day);
    	$this->assign("school", $school);
		$this->display();
	}

	/*
	 *新增学员数据处理
	 */
	public function add_post()
	{
		if(IS_POST){
			$data   = $_POST;
            unset($data['school_id']);
            unset($data['dept_id']);
            unset($data['major_id']);
            unset($data['research_id']);
            $data['dst_school'] = $data['school_name'];unset($data['school_name']);
            $data['dst_dept'] = $data['dept_name'];unset($data['dept_name']);
            $data['dst_major'] = $data['major_name'];unset($data['major_name']);
            $data['dst_research'] = $data['research_name'];unset($data['research_name']);
			if( $data['user_login'] == '' ||
                $data['user_name'] == '' ||
                $data['zone_name'] == '' ||
                $data['sub_zone_name'] == '' ||
                $data['sub_serial'] == '' ||
                $data['tch_name'] == '' ||
                $data['enter_time'] == '' ||
                $data['parent_phone'] == ''
            ){
				$this->error("添加失败,请填写完整数据");
			} else {
				// echo "<pre>";print_r($data);die;
				$stu    = new StudentService();
		    	$result = $stu->add_post($data);
		    	if($result != 2){
			    	if($result == 1){
			    		$this->success("添加成功", U('Student/index'));
			    	} else {
			    		$this->error("添加失败");
			    	}
		    	} else {
		    		$this->error("该学员已存在，请重新添加");
		    	}
			}
		} else {
			$this->error("提交信息错误，请重新提交");
		}
	}

	/**
     * 验证是否是潜在客户，或者是学员
     */
    public function varification() 
    {   
     	$value = I('request.value');     // 校区id
        $stu   = new StudentService();
        $data  = $stu->varification($value);
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($data));
        $this->ajaxReturn(array_values($data));
    }

    /**
     * 添加回访记录,展示回访记录列表
     */
    public function visit()
    {
    	$id   = I('request.id');     // 用户
    	if(!empty($id) && $id != 'undefined'){
    		$stu  = new StudentService();
	        $data = $stu->visit($id);
	        $this->assign("time", date('Y-m-d', time()));  // 回访信息
	        $this->assign("data", $data['data']);  // 回访信息
	        $this->assign("page", $data['page']);
	        $this->assign("user", $data['user']);  // 顶部用户信息
	        $this->assign("id", $id);  // 用户id
	        $this->assign("admin_id", get_current_admin_id());  // 用户id
	        $this->assign("admin_ar", $data['admin_ar']);  // 显示默认的回访人
	    	$this->display();
    	} else {
    		$this->error("请先选择学员");
    	}
    }

    /*
	 * 学员回访记录数据处理
     */
    public function visit_post()
    {
        if( empty($_POST['visit_date']) ||
            empty($_POST['visit_type']) ||
            empty($_POST['objcet']) ||
            empty($_POST['subject']) ||
            empty($_POST['object_feedback'])
        ){
            $this->error("请填写完整数据");
        }
    	$data   = $_POST;
    	$stu    = new StudentService();
	    $result = $stu->visit_post($data);
	    if($result){
	    	$this->success("添加成功", U('Student/visit?id='.$data['user_id']));
	    } else {
	    	$this->error("添加失败，请重新添加");
	    }
    }

    /**
     * 添加结业信息
     */
    public function graduation()
    {
    	$id   = I('request.id');     // 用户
    	if(!empty($id) && $id != 'undefined'){
    		$stu  = new StudentService();
	        $data = $stu->graduation($id);

            // 查询学校信息
            $research = new ResearchService;
            $school   = $research->getschool();
            $this->assign('school', $school);
	        $this->assign("user", $data['user_ar']);  // 顶部用户信息
            $this->assign("gra", $data['gra_arr']);  // 已经填写的学院 结业信息
	        $this->assign("id", $id);  // 用户id
	        $this->assign("admin_id", get_current_admin_id());  // 后台添加用户id
	    	$this->display();
    	} else {
    		$this->error("请先选择学员");
    	}
    }

    /*
	 * 学员结业信息数据处理
     */
    public function gra_post()
    {
        if( empty($_POST['politics_score']) ||
            empty($_POST['lang_score']) ||
            empty($_POST['bus_one_score']) ||
            empty($_POST['bus_two_score'])
        ){
            $this->error("请填写完整信息");
        }
    	$data   = $_POST;
    	$stu    = new StudentService();
	    $result = $stu->gra_post($data);
	    if($result){
	    	$this->success("添加成功", U('Student/index'));
	    } else {
	    	$this->error("添加失败，请重新添加");
	    }
    }

    /*
	 * 学员配置班级页面数据
     */
    public function distribution()
    {
    	$id   = I('request.id');     // 用户
    	if(!empty($id) && $id != 'undefined'){
    		$stu  = new StudentService();
	        $data = $stu->distribution($id);
	        $this->assign("user", $data['user_ar']);  // 顶部用户信息
	        $this->assign("data", $data['data']);  // 顶部用户信息
	        $this->assign("id", $id);  // 用户(学员)id
	        $this->assign("service", $data['service']);  // 顶部用户信息
	    	$this->display();
    	} else {
    		$this->error("请先选择学员");
    	}
    }

    /*
	 * 分配班级时点击选择按钮触发
     */
    public function choice()
    {
    	$class_id = I('request.class_id');     // 校区id
    	$user_id  = I('request.user_id');     // 校区id
        $stu      = new StudentService();
        $data     = $stu->choice($class_id, $user_id);
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($data));
        $this->ajaxReturn(array_values($data));
    }

    /*
	 * 对学员配置服务进行数据处理
     */
    public function service_post()
    {
    	$data = $_POST;
    	$stu  = new StudentService();
        $res  = $stu->service_post($data);
        if($res){
        	$this->success("服务设置成功", U('Student/distribution?id='.$data['user_id']));
        } else {
        	$this->error("服务设置失败");
        }

    }

    /*
	 * 学员详情页面
     */
    public function info(){
    	$id   = I('request.id');     // 学员id
    	$stu  = new StudentService();
		$data = $stu->info($id);

        $this->assign("user", $data['user_arr']);              // 学员的个人信息
        $this->assign("class", $data['class_user_arr']);       // 学员的配班情况
        $this->assign("service", $data['class_service_arr']);  // 学员配置的服务
        $this->assign("visit", $data['stu_visit_arr']);        // 学员的回访记录
        $this->assign("graduation", $data['stu_graduation_arr']);        // 学员的回访记录
        $this->display();
    }
}