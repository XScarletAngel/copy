<?php
namespace Dict\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\ClassServiceService;

class SrvController extends AdminbaseController
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
		$kw = I('request.kw');
		$res = (new ClassServiceService())->getAll($kw);
		$this->assign('res', $res);
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
				'srv_name'=>I('post.srv_name'),
				'srv_info'=>I('post.srv_info'),
				'srv_status'=>I('post.srv_status'),
			];
			if ($data['srv_name'] != '' && $data['srv_info'] !=''){
				$result      = (new ClassServiceService())->addRow($data);
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
		$item   = (new ClassServiceService())->queryById($id);
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
				'srv_name'=>I('post.srv_name'),
				'srv_info'=>I('post.srv_info'),
				'srv_status'=>I('post.srv_status'),
				'id'=>I('post.id'),
			];
			if ($data['srv_name'] != '' && $data['srv_info'] !=''){
				$result      = (new ClassServiceService())->saveRow($data);
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
			'srv_status'=>I('request.srv_status'),
		];
		$res = (new ClassServiceService())->saveRow($data);
		if ($res) {
			$this->ajaxReturn(['msg'=>'操作成功～','code'=>1]);
		} else {
			$this->ajaxReturn(['msg'=>'操作失败～','code'=>2]);
		}
	}
}
