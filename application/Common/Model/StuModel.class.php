<?php
namespace Common\Model;

use Common\Service\ApiService;
use Common\Service\ChannelsService;

class StuModel extends CommonModel
{

	/**
	 * @param $sql 条件
	 * @return array
	 */
	public function getAll($sql, $where)
	{
		$stu   = M("users");
		$count = $stu->join('left join '.C('DB_PREFIX').'stu on '.C('DB_PREFIX').'users.id = '.C('DB_PREFIX').'stu.user_id')->where($sql)->count();
		$Page  = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		// //分页跳转的时候保证查询条件
		if (!empty($where)){
			foreach($where as $key=>$val) {
			    $Page->parameter[$key]   =   urlencode($val);
			}
		}
		$show  = $Page->show();// 分页显示输出
		$data  = $stu->field(C('DB_PREFIX').'users.id, '.C('DB_PREFIX').'users.user_login, '.C('DB_PREFIX').'users.user_no, '.C('DB_PREFIX').'users.user_name, '.C('DB_PREFIX').'stu.zone_name, '.C('DB_PREFIX').'stu.sub_zone_name, '.C('DB_PREFIX').'stu.tch_name, '.C('DB_PREFIX').'users.user_nicename, '.C('DB_PREFIX').'stu.enter_time, '.C('DB_PREFIX').'stu.dst_school, '.C('DB_PREFIX').'stu.dst_major')->join('left join '.C('DB_PREFIX').'stu on '.C('DB_PREFIX').'users.id = '.C('DB_PREFIX').'stu.user_id')->where($sql." and user_type != 0")->order(C('DB_PREFIX').'users.id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$result  = array('data' => $data, 'page' => $show);
		return $result;
	}

	/**
	 * @param $data 数组
	 * @return array
	 */
	public function add_post($data)
	{
		$muser       = M("users");
		$mstu        = M("stu");
		$muser->startTrans();   // 启用事务
		$re          = $muser->where("user_login = ".$data['user_login']." and user_type != 0")->find();
		$re_arr      = $muser->where("user_login = ".$data['user_login'])->find();
		$stu_arr     = $mstu->where("user_login = ".$data['user_login'])->find();
		// 如果不是学员
		if(empty($re)){
			$str     = substr(date('Y', time()), 2, 2);   // 时间字符长度
//			$serial  = $data['serial'];   // 分校序号
			$sub_ser = $data['sub_serial'];   // 校区序号
			$s_str   = rand(001, 999);
			$no      = $str.$serial.$sub_ser.$s_str;
			$user    = array('user_login' => $data['user_login'], 'user_name' => $data['user_name'], 'sex' => $data['sex'], 'birthday' => $data['birthday'], 'user_type' => '1', 'user_no' => $no, 'province' => $data['cmbProvince'], 'city' => $data['cmbCity'], 'county' => $data['cmbArea'], 'mobile' => $data['user_login']);
			// 如果用户表里面没有此人信息
			if(empty($re_arr)){
				$user['user_pass'] = sp_password('88888888');
				$resuser = $muser->data($user)->add();
				$user_id = $resuser;
			} else {
			    // 如果有此人信息
				$resuser = $muser->where("user_login = ".$data['user_login'])->save($user);
				$user_id = $re_arr['id'];
			}
			$stu     = array('user_id' => $user_id, 'user_login' => $data['user_login'], 'zone_id' => $data['zone_id'], 'zone_name' => $data['zone_name'], 'sub_zone_id' => $data['sub_zone_id'], 'sub_zone_name' => $data['sub_zone_name'], 'tch_name' => $data['tch_name'], 'enter_time' => $data['enter_time'], 'stu_source' => $data['stu_source'], 'stu_type' => $data['stu_type'], 'college' => $data['college'], 'college_dept' => $data['college_dept'], 'college_major' => $data['college_major'], 'dst_school' => $data['dst_school'], 'dst_dept' => $data['dst_dept'], 'dst_major' => $data['dst_major'], 'dst_research' => $data['dst_research'], 'politics_score' => $data['politics_score'], 'lang_score' => $data['lang_score'], 'bus_one_score' => $data['bus_one_score'], 'bus_two_score' => $data['bus_two_score'], 'politics_rank' => $data['politics_rank'], 'lang_rank' => $data['lang_rank'], 'bus_one_rank' => $data['bus_one_rank'], 'leap_intention' => $data['leap_intention'], 'weak_subject' => $data['weak_subject'], 'parent_phone' => $data['parent_phone'], 'stu_wechat' => $data['stu_wechat'], 'stu_qq' => $data['stu_qq'], 'stu_email' => $data['stu_email'], 'remark' => $data['remark'], 'create_uid' => get_current_admin_id(), 'stu_no' => $no);
			// $stu['user_id'] = $result;
			if(empty($stu_arr)){
				$resstu   = $mstu->data($stu)->add();
			} else {
				$resstu   = $mstu->where("user_id = ".$user_id)->save($stu);
			}

			if(false !== $resuser && false !== $resstu){
				
				/*埋点 检测用户的im账号 begin*/
				$create_info = (new ChannelsService())->createID($user_id, hide_phone($data['user_login']));
				if (200 == $create_info['code']) {
					$muser->where(['id'=>$user_id])->save([
						'im_accid'=>$create_info['info']['accid'],
						'im_token'=>$create_info['info']['token'],
						'im_name' =>$create_info['info']['name'],
					]);
				}
				/*埋点 检测用户的im账号 end*/

				$muser->commit();   //只有$resuser 和  $resstu  都执行成功是才真正执行上面的数据库操作
                if(empty($re_arr)){
                    $ret = ApiService::call('member.notice', [
                        'mobile' => $user['user_login'],
                        'content' => "您已成功注册星空海天账号，登录账号为【".$user['user_login']."】密码为【88888888】,请点【链接地址】下载APP，或访问【网站地址】登录。"
                    ]);
                    return 1;
                }
                $u_token = M("user_token")->where(['user_id' => $user_id])->find();
                if(!empty($u_token)){
                    $result = ApiService::call('push.autosettag', [
                        'pushid' => $u_token['pushid'],
                        'user_id' => $user_id
                    ]);
                }

			}else{
				$muser->rollback();  //  条件不满足，回滚
				return 3;
			}
		} else {
			return 2;
		}
	}

	/*
	 *编辑学员
	 */
	public function edit($id)
	{
		$user = M("users");
		$stu  = M("stu");
		$data = $user->where("id = ".$id)->find();
		$sarr = $stu->where("user_id = ".$id)->find();
		$res  = array('data' => $data, 'sarr' => $sarr);
		return $res;
	}

	/**
	 * @param $data 数组
	 * @return array
	 */
	public function edit_post($data)
	{
		$muser       = M("users");
		$mstu        = M("stu");
		$muser->startTrans();   // 启用事务
		$user_ar = $muser->where("id = ".$data['id'])->find();  // 学员user表信息
		$user    = array('user_login' => $data['user_login'], 'user_name' => $data['user_name'], 'sex' => $data['sex'], 'birthday' => $data['birthday'], 'user_type' => '1', 'province' => $data['cmbProvince'], 'city' => $data['cmbCity'], 'county' => $data['cmbArea']);
		$resuser = $muser->where("id = ".$data['id'])->save($user);
		$stu     = array('user_login' => $data['user_login'], 'zone_id' => $data['zone_id'], 'zone_name' => $data['zone_name'], 'sub_zone_id' => $data['sub_zone_id'], 'sub_zone_name' => $data['sub_zone_name'], 'tch_name' => $data['tch_name'], 'enter_time' => $data['enter_time'], 'stu_source' => $data['stu_source'], 'stu_type' => $data['stu_type'], 'college' => $data['college'], 'college_dept' => $data['college_dept'], 'college_major' => $data['college_major'], 'dst_school' => $data['dst_school'], 'dst_dept' => $data['dst_dept'], 'dst_major' => $data['dst_major'], 'dst_research' => $data['dst_research'], 'politics_score' => $data['politics_score'], 'lang_score' => $data['lang_score'], 'bus_one_score' => $data['bus_one_score'], 'bus_two_score' => $data['bus_two_score'], 'politics_rank' => $data['politics_rank'], 'lang_rank' => $data['lang_rank'], 'bus_one_rank' => $data['bus_one_rank'], 'leap_intention' => $data['leap_intention'], 'weak_subject' => $data['weak_subject'], 'parent_phone' => $data['parent_phone'], 'stu_wechat' => $data['stu_wechat'], 'stu_qq' => $data['stu_qq'], 'stu_email' => $data['stu_email'], 'remark' => $data['remark'], 'create_uid' => get_current_admin_id(), 'stu_no' => $user_ar['user_no']);
		// $stu['user_id'] = $result;, 'user_no' => $no
		$sarr     = $mstu->where("user_id = ".$data['id'])->find();
		if(empty($sarr)){
			$resstu = $mstu->data($stu)->add();
		} else {
			$resstu = $mstu->where("user_id = ".$data['id'])->save($stu);
		}
		if(false !== $resuser || false !== $resstu){

			/*埋点 检测用户的im账号 begin*/
			if (empty($user_ar['im_accid'])) {
				$create_info = (new ChannelsService())->createID($data['id'], hide_phone($data['user_login']));
				if (200 == $create_info['code']) {
					$muser->where(['id'=>$data['id']])->save([
						'im_accid'=>$create_info['info']['accid'],
						'im_token'=>$create_info['info']['token'],
						'im_name'=>$create_info['info']['name'],
					]);
				}
			}
			/*埋点 检测用户的im账号 end*/

			$muser->commit();   //只有$resuser 和  $resstu  都执行成功是才真正执行上面的数据库操作
			return true;
		}else{
			$muser->rollback();  //  条件不满足，回滚
			return false;
		}
	}

	/*
	 *添加学员时，查询添加人
	 */
	public function getone()
	{
		$stu    = M("admin_users");
		$result = $stu->where("id = ".get_current_admin_id())->find();
		return $result;
	}

	/*
	 *验证是否是潜在客户，或者是学员
	 */
	public function varification($value)
	{
		$user       = M("users");
		$market     = M("market_user");
		$user_arr   = $user->where("user_login = ".$value." and user_type != 0")->find();
		$user_a     = $user->where("user_login = ".$value)->find();
		$martet_arr = $market->where("user_phone = ".$value." and is_del = 1")->find();
		if((!empty($user_arr) && empty($martet_arr)) || (!empty($user_arr) && !empty($martet_arr))){
			$result = array('code' => 1, 'data' => '');  // 已经成为学员
		} elseif (!empty($user_a) && empty($martet_arr)){
			$result = array('code' => 2, 'data' => $user_a);  // 成为注册会员
		} elseif (empty($user_a) && !empty($martet_arr)){
			$result = array('code' => 3, 'data' => $martet_arr);  // 成为潜在客户
		}
		return $result;
	}

	/*
	 *查询回访数据
	 */
	public function visit($id)
	{
		$user  = M("users");
		$admin = M("admin_users");
		$stu   = M("stu_visit");
		$count = $stu->where("user_id = ".$id." and is_del = 1")->order('id desc')->count();
		$Page  = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show  = $Page->show();// 分页显示输出
		$data  = $stu->field(C('DB_PREFIX').'stu_visit.*, '.C('DB_PREFIX').'admin_users.user_nicename')->join('left join '.C('DB_PREFIX').'admin_users on '.C('DB_PREFIX').'stu_visit.create_uid = '.C('DB_PREFIX').'admin_users.id')->where("user_id = ".$id." and is_del = 1")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		// 查询用户信息
		$user_ar = $user->field(C('DB_PREFIX').'users.user_login, '.C('DB_PREFIX').'users.user_name, '.C('DB_PREFIX').'users.user_no, '.C('DB_PREFIX').'stu.zone_name, '.C('DB_PREFIX').'stu.sub_zone_name')->join(C('DB_PREFIX').'stu on '.C('DB_PREFIX').'users.id = '.C('DB_PREFIX').'stu.user_id')->where(C('DB_PREFIX').'users.id = '.$id)->find();
		$admin_ar = $admin->field('user_nicename')->where("id = ".get_current_admin_id())->find();
		$result  = array('data' => $data, 'page' => $show, 'user' => $user_ar, 'admin_ar' => $admin_ar);
		return $result;
	}

	/*
	 *添加学员数据处理
	 */
	public function visit_post($data)
	{
		$stu    = M("stu_visit");
		$result = $stu->data($data)->add();
		return $result;
	}

	/**
     * 添加结业信息
     */
    public function graduation($id)
    {
    	$user    = M("users");
    	$stu     = M("stu_graduation");
    	// 查询用户信息
		$user_ar = $user->field(C('DB_PREFIX').'users.user_login, '.C('DB_PREFIX').'users.user_name, '.C('DB_PREFIX').'users.user_no, '.C('DB_PREFIX').'stu.zone_name, '.C('DB_PREFIX').'stu.sub_zone_name')->join(C('DB_PREFIX').'stu on '.C('DB_PREFIX').'users.id = '.C('DB_PREFIX').'stu.user_id')->where(C('DB_PREFIX').'users.id = '.$id)->find();
		$gra_arr = $stu->where("user_id = ".$id)->find();
		$data    = array('user_ar' => $user_ar, 'gra_arr' => $gra_arr);
		return $data;
    }

    /*
	 *学员结业信息数据处理
	 */
	public function gra_post($data)
	{
		$stu    = M("stu_graduation");
		$re     = $stu->where("user_id = ".$data['user_id'])->find();
		if($data['stu_gone'] != 1){
			$data['school_id'] = '';
			$data['school_name'] = '';
			$data['dept_id'] = '';
			$data['dept_name'] = '';
			$data['major_id'] = '';
			$data['major_name'] = '';
			$data['research_id'] = '';
			$data['research_name'] = '';
		}
		if(empty($re)){
			$result = $stu->data($data)->add();
		} else {
			$result = $stu->where("id = ".$re['id'])->save($data);
		}
		return $result;
	}

	/**
	 * @param $id 学员id
     * 学员配置班级页面数据
     */
    public function distribution($id)
    {
    	$user          = M("users");
    	$class         = M("class");
    	$class_user    = M("class_user");
    	$class_service = M("class_service");
    	// 查询所有启用服务
    	$class_service_arr = $class_service->field("id, srv_name")->where("srv_status = 1")->select();
    	// 查询用户信息
		$user_ar = $user->field(C('DB_PREFIX').'users.user_login, '.C('DB_PREFIX').'users.user_name, '.C('DB_PREFIX').'users.user_no, '.C('DB_PREFIX').'users.service, '.C('DB_PREFIX').'stu.zone_name, '.C('DB_PREFIX').'stu.sub_zone_name, '.C('DB_PREFIX').'stu.zone_id, '.C('DB_PREFIX').'stu.sub_zone_id')->join(C('DB_PREFIX').'stu on '.C('DB_PREFIX').'users.id = '.C('DB_PREFIX').'stu.user_id')->where(C('DB_PREFIX').'users.id = '.$id)->find();
		$srv_ar = explode(',', $user_ar['service']);
		foreach($class_service_arr as $k=>$v){
			if(!empty($srv_ar)){
				foreach($srv_ar as $kk=>$vv){
					if($class_service_arr[$k]['id'] == $srv_ar[$kk]){
						$class_service_arr[$k]['choice'] = 1;
					}
				}
			} else {
				$class_service_arr[$k]['choice'] = 0;
			}
		}
		// 查询用户所在校区的班级
		$count = $class->join('left join '.C('DB_PREFIX').'course on '.C('DB_PREFIX').'class.course_id = '.C('DB_PREFIX').'course.id')->where("zone_id = ".$user_ar['zone_id']." and sub_zone_id = ".$user_ar['sub_zone_id']." and class_status != 3 and class_type != 3")->count();
		$Page  = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show  = $Page->show();// 分页显示输出
		$data  = $class->field(C('DB_PREFIX').'class.id, '.C('DB_PREFIX').'class.class_name, '.C('DB_PREFIX').'class.class_no, '.C('DB_PREFIX').'class.class_master, '.C('DB_PREFIX').'class.tch_name, '.C('DB_PREFIX').'course.course_name')->join('left join '.C('DB_PREFIX').'course on '.C('DB_PREFIX').'class.course_id = '.C('DB_PREFIX').'course.id')->where("zone_id = ".$user_ar['zone_id']." and sub_zone_id = ".$user_ar['sub_zone_id']." and class_status != 3 and class_type != 3")->order("add_time desc")->limit($Page->firstRow.','.$Page->listRows)->select();
		$arr = $class_user->where("order_type = 1 and status = 1 and user_id = ".$id)->select();
		foreach($data as $k=>$v){
			if(!empty($arr)){
				foreach($arr as $kk=>$vv){
					if($data[$k]['id'] == $arr[$kk]['class_id']){
						$data[$k]['choice'] = 1;
					} else {
						$data[$k]['choice'] = 0;
					}
				}
			} else {
				$data[$k]['choice'] = 0;
			}
		}
		$result = array('user_ar' => $user_ar, 'data' => $data, 'service' => $class_service_arr);
		return $result;
    }

    /*
	 * 分配班级时点击选择按钮触发
     */
    public function choice($class_id, $user_id)
    {
    	$user           = M("users");
    	$class          = M("class");                // 班级表
    	$class_user     = M("class_user");           // 分配课程表
    	$class_schedule = M("class_schedule");       // 班级课表
    	$stu_schedule   = M("stu_schedule");         // 学生课表
    	$stu_sign       = M("stu_sign");             // 学生签到表
    	$class_book     = M("class_book");           // 班级资料表
    	$receive        = M("class_book_receive");   // 资料领取表

        // 查询班级课表是否有课程
        $sch_arr_class = $class_schedule->where(["class_id" => $class_id])->select();
    	$find_class_user = $class_user->where("user_id = ".$user_id." and class_id = ".$class_id)->find();
    	$class_arr = $class->where("id = ".$class_id)->find();
	    $user_arr  = $user->field(C('DB_PREFIX').'users.id, '.C('DB_PREFIX').'users.user_login, '.C('DB_PREFIX').'users.user_name, '.C('DB_PREFIX').'users.user_no, '.C('DB_PREFIX').'stu.zone_name, '.C('DB_PREFIX').'stu.sub_zone_name, '.C('DB_PREFIX').'stu.zone_id, '.C('DB_PREFIX').'stu.sub_zone_id')->join(C('DB_PREFIX').'stu on '.C('DB_PREFIX').'users.id = '.C('DB_PREFIX').'stu.user_id')->where(C('DB_PREFIX').'users.id = '.$user_id)->find();
        if(!empty($sch_arr_class)){   // 如果有课程，可以添加，如果没有，就不允许配置课程
            if(empty($find_class_user)){
                // 启用事务
                $class_user->startTrans();
                /*************************订单详情表（class_user）生成数据开始****************************/

                $class_user_arr = array(
                    'order_type' => 1,
                    'user_id' 	 => $user_id,
                    'user_login' => $user_arr['user_login'],
                    'class_id'   => $class_arr['id'],
                    'class_no'   => $class_arr['class_no'],
                    'class_name' => $class_arr['class_name']
                );
                $res_class_user = $class_user->data($class_user_arr)->add();  // 事务判断

                /*************************订单详情表（class_user）生成数据结束****************************/
                /********根据班级课表生成学生课表（class_schedule => stu_schedule）生成数据开始*************/

                $class_schedule_arr = $class_schedule->field('id, class_id, tch_id, tch_name, class_date, start_time, end_time, hour_num, course_title, course_no')->where("class_id = ".$class_id)->select();

                if(!empty($class_schedule_arr)){
                    foreach($class_schedule_arr as $k=>$v){
                        $class_schedule_arr[$k]['user_id'] = $user_id;
                        $class_schedule_arr[$k]['sign_code'] = rand(1000, 9999);
                        $class_schedule_arr[$k]['schedule_id'] = $class_schedule_arr[$k]['id'];
                        unset($class_schedule_arr[$k]['id']);
                        $res_stu_schedule = $stu_schedule->data($class_schedule_arr[$k])->add();  // 事务判断

                    }
                }

                /*****根据班级课表生成学生课表（class_schedule => stu_schedule）生成数据结束*******/
                /**************根据学生课表（stu_schedule）生成签到表（stu_sign）数据开始*******************/
                // 注：签到表信息给学生推送消息是生成

                for($i=0;$i<count($class_schedule_arr);$i++){
                    $stu_sign_arr = array(
                        'class_id'   => $class_arr['id'],
                        'class_no'   => $class_arr['class_no'],
                        'class_name' => $class_arr['class_name'],
                        'schedule_id'=> $class_schedule_arr[$i]['schedule_id'],
                        'sign_code'  => $class_schedule_arr[$i]['sign_code'],
                        'user_id'    => $user_id,
                        'user_no'    => $user_arr['user_no'],
                        'user_name'  => $user_arr['user_name'],
                        'zone_id'    => $user_arr['zone_id'],
                        'zone_name'  => $user_arr['zone_name'],
                        'sub_zone_id'=> $user_arr['sub_zone_id'],
                        'sub_zone_name' => $user_arr['sub_zone_name']
                    );
                    // 循环添加学生签到信息
                    $res_stu_sign = $stu_sign->data($stu_sign_arr)->add();
                }

                /**************根据学生课表（stu_schedule）生成签到表（stu_sign）数据结束*******************/
                /**************根据班级资料表（class_book）生成资料领取表（stu_sign）数据开始*******************/

                $class_book_arr = $class_book->where("class_id = ".$class_id." and book_status = 1")->select();
                $bool_add = true;
                if(!empty($class_book_arr)){
                    foreach($class_book_arr as $k=>$v){

                        $class_book_data = array(
                            'class_id' => $class_arr['id'],
                            'class_no' => $class_arr['class_no'],
                            'class_name' => $class_arr['class_name'],
                            'user_login' => $user_arr['user_login'],
                            'user_no' => $user_arr['user_no'],
                            'user_nicename' => $user_arr['user_name'],
                            'zone_id' => $user_arr['zone_id'],
                            'zone_name' => $user_arr['zone_name'],
                            'sub_zone_id' => $user_arr['sub_zone_id'],
                            'sub_zone_name' => $user_arr['sub_zone_name'],
                            'book_id' => $class_book_arr[$k]['id'],
                            'book_no' => $class_book_arr[$k]['book_no'],
                            'book_name' => $class_book_arr[$k]['book_name']
                        );

                        $res_receive = $receive->data($class_book_data)->add();
                        if (!$res_receive) $bool_add = false;
                    }
                }

                /**************根据班级资料表（class_book）生成资料领取表（stu_sign）数据结束*******************/
                // var_dump($res_class_user, $res_stu_schedule, $bool_add);die;

                if($res_class_user && $res_stu_schedule && $bool_add){
                    $class_user->commit();   //只有$resuser 和  $resstu  都执行成功是才真正执行上面的数据库操作
                    $res_return = array('code' => 1, 'msg' => '配置成功');
                    return $res_return;
                }else{
                    $class_user->rollback();  //  条件不满足，回滚
                    $res_return = array('code' => 0, 'msg' => '配置失败');
                    return $res_return;
                }
            } else {
                // 修改订单详情状态
                if($find_class_user['status'] == 1){
                    // 取消选择状态，对学生课表进行删除
                    $data = array('status' => 2);
                    $res_stu_schedule = $stu_schedule->where("class_id = ".$class_id." and user_id = ".$user_id)->delete();
                    $sa  = array('is_del' => 1);
                    $res_sign = $stu_sign->where("class_id = ".$class_id." and user_id = ".$user_id)->save($sa);
                } else {
                    // 选择状态，对学生课表进行添加更新
                    $data = array('status' => 1);
                    $class_schedule_arr = $class_schedule->field('id, class_id, tch_id, tch_name, class_date, start_time, end_time, hour_num, course_title, course_no')->where("class_id = ".$class_id)->select();
                    foreach($class_schedule_arr as $k=>$v){
                        $class_schedule_arr[$k]['user_id'] = $user_id;
                        $class_schedule_arr[$k]['sign_code'] = rand(1000, 9999);
                        $class_schedule_arr[$k]['schedule_id'] = $class_schedule_arr[$k]['id'];
                        unset($class_schedule_arr[$k]['id']);
                        $res_stu_schedule = $stu_schedule->data($class_schedule_arr[$k])->add();  // 事务判断
                    }
                    // 生成新的签到信息，以前的作废
                    for($i=0;$i<count($class_schedule_arr);$i++){
                        $stu_sign_arr = array(
                            'class_id'   => $class_arr['id'],
                            'class_no'   => $class_arr['class_no'],
                            'class_name' => $class_arr['class_name'],
                            'schedule_id'=> $class_schedule_arr[$i]['schedule_id'],
                            'sign_code'  => $class_schedule_arr[$i]['sign_code'],
                            'user_id'    => $user_id,
                            'user_no'    => $user_arr['user_no'],
                            'user_name'  => $user_arr['user_name'],
                            'zone_id'    => $user_arr['zone_id'],
                            'zone_name'  => $user_arr['zone_name'],
                            'sub_zone_id'=> $user_arr['sub_zone_id'],
                            'sub_zone_name' => $user_arr['sub_zone_name']
                        );
                        // 循环添加学生签到信息
                        $res_stu_sign = $stu_sign->data($stu_sign_arr)->add();
                    }
                }
                $res = $class_user->where("user_id = ".$user_id." and class_id = ".$class_id)->save($data);
                if($res && $res_stu_schedule){
                    $class_user->commit();    // 执行
                    $res_return = array('code' => 1, 'msg' => '配置成功');
                    return $res_return;
                } else {
                    $class_user->rollback();  // 条件不满足，回滚
                    $res_return = array('code' => 0, 'msg' => '配置失败');
                    return $res_return;
                }
            }
        } else {
            $res_return = array('code' => 2, 'msg' => '配置失败,请联系管理员配置本班级课表');
            return $res_return;
        }
    }

    /*
	 * 分配班级时点击选择按钮触发
     */
    public function service_post($id, $str)
    {
    	$user   = M("users");
    	$result = $user->where("id = ".$id)->save(array('service' => $str));
		return $result;
    }

    /*
	 * 学员详情页面
     */
    public function info($id){
    	$user           = M('users');
    	$class_user     = M('class_user');  // 学员配置课程表
    	$class_service  = M('class_service');  // 服务表
    	$stu_visit      = M('stu_visit');  // 学员回访记录表
    	$stu_graduation = M('stu_graduation');  // 学员结业表

    	// 查询学员的个人信息
    	$user_arr = $user->join("left join ".C('DB_PREFIX')."stu on ".C('DB_PREFIX')."users.id = ".C('DB_PREFIX')."stu.user_id")->where(C('DB_PREFIX')."users.id = ".$id)->find();

    	// 查询学员的课程及服务
    	$class_user_arr = $class_user->join("left join ".C('DB_PREFIX')."class on ".C('DB_PREFIX')."class_user.class_id = ".C('DB_PREFIX')."class.id")->join("left join ".C('DB_PREFIX')."course on ".C('DB_PREFIX')."class.course_id = ".C('DB_PREFIX')."course.id")->where(C('DB_PREFIX')."class_user.user_id = ".$id." and ".C('DB_PREFIX')."class_user.status = 1")->select();
    	$str = $user_arr['service'];
    	if($str != ''){
    		$class_service_arr = $class_service->where("id in(".$str.")")->select();
    	} else {
    		$class_service_arr = '';
    	}

    	// 查询学员的回访记录
    	$stu_visit_arr = $stu_visit->where("user_id = ".$id)->select();
    	foreach($stu_visit_arr as $k=>$v){
    		$stu_visit_arr[$k]['user_name'] = $user_arr['user_name'];
    	}

    	// 查询学员的结业信息
    	$stu_graduation_arr = $stu_graduation->where("user_id = ".$id)->find();

    	$result = array('user_arr' => $user_arr, 'class_user_arr' => $class_user_arr, 'class_service_arr' => $class_service_arr, 'stu_visit_arr' => $stu_visit_arr, 'stu_graduation_arr' => $stu_graduation_arr);
    	return $result;
    }
}