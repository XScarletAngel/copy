<?php
/**
 * 科目列表
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\SubjectsService;

class SubjectsController extends AdminbaseController 
{
	/**
     * 方向查询列表
     */
    public function index() 
    {   
        $where      = array();
        $sub        = I('request.sub');     // 包括code,name
        $count      = I('request.count');     // 搜索内容
        $sub_status = I('request.sub_status');  // 状态
        
        $where      = array('sub' => $sub, 'count' => $count, 'sub_status' => $sub_status);
    	$sub        = new SubjectsService;
    	$categorys  = $sub->getall($where);
        // echo "<pre>";print_r($categorys['result']);die;
    	$this->assign('page', $categorys['page']);  // 分页数据显示
        $this->assign('lists', $categorys['result']);  // 数据显示
        // $this->assign('where', $categorys['where']);  // 数据显示
       	$this->display();
    }
}