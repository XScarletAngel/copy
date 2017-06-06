<?php
namespace Msg\Controller;

use Common\Controller\AdminbaseController;
use Common\Model\InnerMsgTplModel;
use Common\Service\ClassService;
use Common\Service\InnerMsgService;
use Common\Service\UsersService;

/**
 * 推送相关
 * Class PushController
 * @package Dict\Controller
 */

class InnerMsgController extends AdminbaseController
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
        $where = [];
        $data = InnerMsgService::findInnerMsg($where);
        $this->assign('res', $data);
		$this->display();
	}

	/**
	 * 添加页面
	 */
	public function add()
	{
	    $tpls = (new InnerMsgTplModel())->getInnerMsgTpls();
	    $classes = ClassService::getAll();
        $this->assign('tpls', $tpls);
        $this->assign('class', $classes);
//        echo "<pre>"; print_r($classes);die;
		$this->display();
	}

    /**
     * User: maChuang
     * 新建推送
     */
    public function create()
    {
        $param      = I('request.');
        $session = session();
        $pdata = explode(',',$param['user_phone']);
        foreach ($pdata as $datum) {
            //正则验证手机号
            if(preg_match("/^1[34578]\d{9}$/", $datum)){
                //查询user_id
               $id = (new UsersService())->getInfo(['user_login'=>$datum]);
               if(!empty($id)) $ids[] = $id['id'];
            }
        }
//        echo "<pre>"; print_r($ids);die;
        foreach($ids as $id){
            $insert = [
                'user_id'       =>  $id,
                'msg_title'     =>  $param['msg_title'],
                'msg_body'      =>  $param['msg_body'],
                'tpl_id'        =>  $param['tpl_id'],
                'msg_type'      =>  empty($param['msg_type'])?0:$param['msg_type'],
                'is_push'       =>  empty($param['is_push'])?0:$param['is_push'],
                'class_id'      =>  $param['class_id'],
                'oper_uid'      =>  $session['admin_user']['id'],
                'oper_uname'    =>  $session['name'],
                'user_type'     =>  empty($param['user_type'])?0:$param['user_type'],
                'mix_id'        =>  empty($param['mix_id'])?0:$param['mix_id'],
            ];
            (new InnerMsgService())->addData($insert);
        }
        $this->redirect('InnerMsg/index');
    }



}
