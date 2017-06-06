<?php
/**
 * 参    数：
 * 作    者：lhr
 * 功    能：用户管理
 * 修改日期：2017-04-13
 */
namespace User\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\UsersService;
use Common\Service\ApiService;
use Common\Lib\Crypt\Mcrypt;

class UserController extends AdminbaseController {

    public function test()
    {

        $result = ApiService::call('push.autosettag', [
            'pushid' => '3c42c3cb72b66e4b303b22d9c6e17fe1',
            'user_id' => 4,
            'user_type' => 3,
            'class_id' => 7,
        ]);
        var_dump($result);die;

        $ret = ApiService::call('member.notice', [
            'mobile' => 13521032797,
            'content' => "您已成功注册星空海天账号，登录账号为【13521032797】密码为【88888888】,请点【链接地址】下载APP，或访问【网站地址】登录。"
        ]);
        var_dump($ret);die;
    }

	/*
	 *用户信息查询
	 */
	public function search()
	{
		if($_POST['usertype'] != '' && $_POST['user_con'] != ''){
			if(IS_POST){
				$usertype = I("post.usertype");
				$user_con = I("post.user_con");
				$user     = new UsersService();
				$result   = $user->search($usertype, $user_con);
				// print_r($result);die;
				if($result == ''){
					$result = 1;
					$this->assign('user', $result);
				} else {
					$this->assign('user', $result);
					$this->assign('usertype', $usertype);
					$this->assign('user_con', $user_con);
				}
			} else {
				$this->error("数据提交错误，请重新提交");
			}
		}
		$this->display();
	}

	/*
	 *用户列表
	 */
	public function lists()
	{
		$usertype   = I('request.usertype');
		$user_con   = I('request.user_con');
		$stu        = I('request.stu');
		$teacher    = I('request.teacher');
		$start_time = I('request.start_time');
		$end_time   = I('request.end_time');
		$source     = I('request.source');

		$user = new UsersService();
		$data = $user->lists($usertype, $user_con, $stu, $teacher, $start_time, $end_time, $source);
		$this->assign('data', $data['data']);
		$this->assign('page', $data['page']);
		$this->display();
	}

	/*
	 *用户列表入口学员详情
	 */
	public function info()
	{
		$id     = I("get.id");
		$user   = new UsersService();
		$result = $user->info($id);
		$this->assign('user', $result['data']);
        $this->assign('stu', $result['stu']);
		$this->display();
	}

    /**
     * 用户账号状态变更
     */
    public function status()
    {
        $id     = I('request.id');     // 公告id
        $status = I('request.value');     // 公告要更改的状态
        $users = new UsersService();
        $result = $users->status($id, $status);
        if ($result) {
            $this->ajaxReturn(['msg'=>'修改成功～','code'=>1]);
        } else {
            $this->ajaxReturn(['msg'=>'修改失败～','code'=>2]);
        }
    }
}