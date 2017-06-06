<?php
namespace Msg\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\PushMsgService;
use Common\Service\PushTaskService;
use Common\Service\UsersService;
use Common\Service\UserTokenService;
use Course\Controller\CourseController;

/**
 * 推送相关
 * Class PushController
 * @package Dict\Controller
 */

class PushMsgController extends AdminbaseController
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
        $param = I('request.');
//        echo "<pre>"; print_r($param); die;
        $where = [];
        if(!empty($param['device_type'])){
            $where['device_type'] = $param['device_type'];
        }
        if(!empty($param['user_type'])){
            $where['user_type'] = $param['user_type'];
        }
        if(!empty($param['is_notice'])){
            $where['is_notice'] = $param['is_notice'];
        }
        if(!empty($param['begin_time'])){
            $where['push_time'][] = array('egt',$param['begin_time']);
        }
        if(!empty($param['end_time'])){
            $where['push_time'][] = array('elt',$param['end_time']);
        }
        $data = PushMsgService::findPushMsg($where);
        $this->assign('res', $data);
//        echo "<pre>";print_r($data);die;
        $this->assign('req', $param);
		$this->display();
	}



}
