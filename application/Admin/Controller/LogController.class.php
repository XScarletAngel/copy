<?php
/**
 * 班级公告
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\AdminLogService;
use Common\Service\ClassNoticeService;
use Common\Service\QuestionService;

class LogController extends AdminbaseController
{
    /**
     * User: maChuang
     * 后台日志管理
     */
    public function index() 
    {
//        $data = M('menu')->where(['action'=>'dologin'])->select(); echo "<pre>"; print_r($data); die;
        $param = I('request.');
//        echo "<pre>"; print_r($param); die;
        $where = [];
        if(!empty($param['admin_id'])){
            $where['admin_id'] = $param['admin_id'];
        }
        if(!empty($param['object'])){
            $where['object'] = $param['object'];
        }
        if(!empty($param['type'])){
            $where['type'] = $param['type'];
        }
        if(!empty($param['begin_time'])){
            $where['add_time'][] = array('egt',$param['begin_time']);
        }
        if(!empty($param['end_time'])){
            $where['add_time'][] = array('elt',$param['end_time']);
        }
    	$data = AdminLogService::getByPage($where);
//    	var_dump($data);die;
        $this->assign('page', $data['page']);  // 分页数据显示
        $this->assign('list', $data['list']);  // 数据显示
        $this->assign('req', $param);    // old数据维持
       	$this->display();
    }


}