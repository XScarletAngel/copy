<?php
namespace Msg\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\NoticeService;

/**
 * 系统公告
 * Class NoticeController
 * @package Dict\Controller
 */

class NoticeController extends AdminbaseController
{

	public function _initialize()
	{
		parent::_initialize();
	}

	/**
	 * 服务管理列表
	 */
	public function index()
	{
		$res = (new NoticeService())->getAll($_REQUEST);
		$this->assign('res', $res);
		$this->assign('req', $_REQUEST);
		$this->display();
	}

	/**
	 * 添加页面
	 */
	public function add()
	{
		$this->display();
	}

	/**
	 * 添加数据处理
	 */
	public function addPost()
	{
		if (IS_POST){
			$data = [
				'content'=>I('post.content'),
				'is_stu'=>I('post.is_stu') ? 1 : 0,
				'is_teacher'=>I('post.is_teacher') ? 1 : 0,
				'is_frontend'=>I('post.is_frontend') ? 1 : 0,
				'is_oper'=>I('post.is_oper') ? 1 : 0,
				'release'=>I('post.release'),
				'timing_time'=>I('post.timing_time'),
				'notice_status'=>I('post.notice_status'),
				'oper_uid'=>$this->admin_user['id'],
				'oper_uname'=>$this->admin_user['user_login'],
			];
			if ($data['content'] != ''){
				$result      = (new NoticeService())->addRow($data);
				if ($result != false){
					$this->success("添加成功");
				} else {
					$this->error("添加失败");
				}
			} else {
				$this->error("请填写完整数据，重新提交");
			}
		} else {
			$this->error("提交数据失败，请重新提交");
		}
	}

	/**
	 * 编辑页面
	 */
	public function edit()
	{
		$id     = I('get.id');
		if (empty($id)) {
			$this->error("参数错误");
		}
		$item   = (new NoticeService())->queryById($id);
		$this->assign("item", $item);
		$this->display();
	}

	/**
	 * 编辑数据处理
	 */
	public function editPost()
	{
		if (IS_POST){
			$data = [
				'id'=>I('post.id'),
				'content'=>I('post.content'),
				'is_stu'=>I('post.is_stu'),
				'is_teacher'=>I('post.is_teacher'),
				'is_frontend'=>I('post.is_frontend'),
				'is_oper'=>I('post.is_oper'),
				'release'=>I('post.release'),
				'timing_time'=>I('post.timing_time'),
				'notice_status'=>I('post.notice_status'),
				'oper_uid'=>$this->admin_user['id'],
				'oper_uname'=>$this->admin_user['user_login'],
			];
			if ($data['content'] != '' ){
				$result      = (new NoticeService())->saveRow($data);
				if ($result != false){
					$this->success("修改成功");
				} else {
					$this->error("修改失败");
				}
			} else {
				$this->error("请填写完整数据，重新提交");
			}
		} else {
			$this->error("提交数据失败，请重新提交");
		}
	}


	/**
	 * 删除有id的表数据
	 */
	public function changeStatus()
	{
		$data  = [
			'id'=>I('request.id'),
			'notice_status'=>I('request.notice_status'),
		];
		$res = (new NoticeService())->saveRow($data);
		if ($res) {
			$this->ajaxReturn(['msg'=>'操作成功～','code'=>1]);
		} else {
			$this->ajaxReturn(['msg'=>'操作失败～','code'=>2]);
		}
	}
}
