<?php
namespace Common\Service;

use Common\Model\StuModel;

class StudentService 
{
	/**
	 * @param $ 条件(数组)
	 * @return array
	 */
	public function getAll($usertype, $user_con, $zone_id, $sub_zone_id, $teacher, $start_time, $end_time)
	{
		//user_type 0:注册用户1:学生 2:老师 3:学生＋老师
		$sql = "user_type in(1,3) ";
		if($usertype != '' && $user_con != ''){
			$sql .= " and ".C('DB_PREFIX')."users.".$usertype." = '".trim($user_con)."'";
		}
		if($zone_id != ''){
			$sql .= " and zone_id = ".$zone_id;
		}
		if($sub_zone_id != ''){
			$sql .= " and sub_zone_id = ".$sub_zone_id;
		}
		if($teacher != ''){
			$sql .= " and tch_name = ".$teacher;
		}
		if ($start_time != ''){
			$sql .= " and enter_time >= '".$start_time."'";
		}
		if ($end_time != ''){
			$sql .= " and enter_time <= '".$end_time."'";
		}
		$where = array('usertype' => $usertype, 'user_con' => $user_con, 'zone_id' => $zone_id, 'sub_zone_id' => $sub_zone_id, 'teacher' => $teacher, 'start_time' => $start_time, 'end_time' => $end_time);
		$stu = new StuModel();
		$result = $stu->getAll($sql, $where);
		return $result;
	}

	/*
	 *新增学员数据处理
	 */
	public function add_post($data)
	{
		$stu    = new StuModel();
		$result = $stu->add_post($data);
		return $result;
	}

	/*
	 *编辑学员
	 */
	public function edit($id)
	{
		$stu    = new StuModel();
		$result = $stu->edit($id);
		return $result;
	}

	/*
	 *编辑学员数据处理
	 */
	public function edit_post($data)
	{
		$stu    = new StuModel();
		$result = $stu->edit_post($data);
		return $result;
	}

	/*
	 *添加学员时，查询添加人
	 */
	public function getone()
	{
		$stu    = new StuModel();
		$result = $stu->getone();
		return $result;
	}

	/*
	 *验证是否是潜在客户，或者是学员
	 */
	public function varification($value)
	{
		$stu    = new StuModel();
		$result = $stu->varification($value);
		return $result;
	}

	/*
	 *添加回访记录页面，展示回访数据
	 */
	public function visit($id)
	{
		$stu    = new StuModel();
		$result = $stu->visit($id);
		return $result;
	}

	/*
	 *添加学员回访数据处理
	 */
	public function visit_post($data)
	{
		$stu    = new StuModel();
		$result = $stu->visit_post($data);
		return $result;
	}

	/**
     * 添加结业信息
     */
    public function graduation($id)
    {
    	$stu    = new StuModel();
		$result = $stu->graduation($id);
		return $result;
    }

    /*
	 * 学员结业信息数据处理
     */
    public function gra_post()
    {
    	$data   = $_POST;
    	$stu    = new StuModel();
	    $result = $stu->gra_post($data);
	    return $result;
    }

    /**
     * 学员配置班级页面数据
     */
    public function distribution($id)
    {
    	$stu    = new StuModel();
		$result = $stu->distribution($id);
		return $result;
    }

    /*
	 * 分配班级时点击选择按钮触发
     */
    public function choice($class_id, $user_id)
    {
    	$stu    = new StuModel();
		$result = $stu->choice($class_id, $user_id);
		return $result;
    }

    /*
	 * 分配班级时点击选择按钮触发
     */
    public function service_post($data)
    {
    	$id = $data['user_id'];
    	for($i=0;$i<count($data['service']);$i++){
    		$str .= $data['service'][$i].',';
    	}
    	$str = rtrim($str, ',');
    	$stu    = new StuModel();
		$result = $stu->service_post($id, $str);
		return $result;
    }

    /*
	 * 学员详情页面
     */
    public function info($id){    // 学员id
    	$stu  = new StuModel();
		$data = $stu->info($id);
		return $data;
    }
	
    /**
     * User: maChuang
     * @param $id
     * @return mixed
     * id查询
     */
    public static function find($where)
    {
        $st = M('stu');
        return $st->where($where)->select();
    }
}