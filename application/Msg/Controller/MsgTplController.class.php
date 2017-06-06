<?php
namespace Msg\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\MsgTplService;

/**
 * 消息模版管理
 * Class NoticeController
 * @package Dict\Controller
 */

class MsgTplController extends AdminbaseController
{

	public  $srv = null;
	public function _initialize()
	{
		parent::_initialize();
		$this->srv = new MsgTplService();
	}

	/**
	 * 服务管理列表
	 */
	public function index()
	{
		$res = $this->srv->getAll($_REQUEST);
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
				'tpl_name'=>I('post.tpl_name'),
				'tpl_code'=>I('post.tpl_code'),
				'tpl_content'=>I('post.tpl_content'),
				'msg_type'=>I('post.msg_type'),
				'is_push'=>I('post.is_push'),
				'tpl_status'=>I('post.tpl_status'),
				'after_open'=>I('post.after_open'),
				'oper_uid'=>$this->admin_user['id'],
				'oper_uname'=>$this->admin_user['user_login'],
			];
			if ($data['tpl_content'] != ''){
				$result      = $this->srv->addRow($data);
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
		$item   = $this->srv->queryById($id);
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
				'tpl_name'=>I('post.tpl_name'),
				'tpl_code'=>I('post.tpl_code'),
				'tpl_content'=>I('post.tpl_content'),
				'msg_type'=>I('post.msg_type'),
				'is_push'=>I('post.is_push'),
				'tpl_status'=>I('post.tpl_status'),
				'after_open'=>I('post.after_open'),
				'oper_uid'=>$this->admin_user['id'],
				'oper_uname'=>$this->admin_user['user_login'],
			];
			if ($data['tpl_content'] != '' ){
				$result      = $this->srv->saveRow($data);
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
			I('request.field')=>I('request.field_val'),
		];
		$res = $this->srv->saveRow($data);
		if ($res) {
			$this->ajaxReturn(['msg'=>'操作成功～','code'=>1]);
		} else {
			$this->ajaxReturn(['msg'=>'操作失败～','code'=>2]);
		}
	}
}
