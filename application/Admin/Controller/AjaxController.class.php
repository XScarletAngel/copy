<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\ClassService;

/**
 * ajax 异步返回数据
 *
 * @author lixiuran
 */
class AjaxController extends AdminbaseController
{

	/**
	 * 通过关键词查询未上课的班级
	 */
	public function queryClassByKw()
	{
		$kw = I("request.kw", '');
		if (empty($kw)) $this->ajaxError("参数错误");
		$class_list = (new ClassService())->queryClassByKw($kw);
		$this->ajaxOk('success',$class_list);
	}


	/**
	 * 获取班级信息
	 * class_type: 1:线下,2:直播 3:录播
	 */
	public function ajaxGetClass()
	{
		$sub_zone_id = I("request.sub_zone_id", 0);
		$class_type  = I("request.class_type", 0);
		if (empty($sub_zone_id) || empty($class_type)) {
			$this->ajaxError("参数错误");
		}

		$list = (new ClassService())->getAll(['class_type'=>$class_type,'sub_zone_id'=>$sub_zone_id]);
		$this->ajaxOk('success',$list);
	}
}