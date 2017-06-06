<?php
/**
 * 资料领取列表
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\ClassNoticeService;
use Common\Service\ReceiveService;

class ReceiveController extends AdminbaseController 
{
	/**
	 * 资料领取列表
	 */
	public function index()
	{
		$zone_id        = I('request.zone_id');
		$sub_zone_id    = I('request.sub_zone_id');
		$class_type     = I('request.class_type');
		$class_con      = I('request.class_con');
		$book_type      = I('request.book_type');
		$book_con       = I('request.book_con');
		$user_type      = I('request.user_type');
		$user_con       = I('request.user_con');
		$start_time     = I('request.start_time');
		$end_time       = I('request.end_time');
		$receive_status = I('request.receive_status');

		$class          = new ClassNoticeService();
    	$school         = $class->zone();
    	$receive        = new ReceiveService();
    	$result         = $receive->getall($zone_id, $sub_zone_id, $class_type, $class_con, $book_type, $book_con, $user_type, $user_con, $start_time, $end_time, $receive_status);
    	$this->assign('school', $school);
    	$this->assign('data', $result['data']);
    	$this->assign('page', $result['page']);
		$this->display();
	}

	/**
     * 资料批量领取
     */
    public function receive()
    {
        $ids      = I('request.ids');     // 公告id
        $receive  = new ReceiveService();
        $result   = $receive->receive($ids);
        if ($result) {
            $this->ajaxReturn(['msg'=>'修改成功～','code'=>1]);
        } else {
            $this->ajaxReturn(['msg'=>'修改失败～','code'=>2]);
        }
    }

    /**
     * 资料批量撤销领取
     */
    public function noreceive()
    {
        $ids      = I('request.ids');     // 公告id
        $receive  = new ReceiveService();
        $result   = $receive->noreceive($ids);
        if ($result) {
            $this->ajaxReturn(['msg'=>'撤销成功～','code'=>1]);
        } else {
            $this->ajaxReturn(['msg'=>'撤销失败～','code'=>2]);
        }
    }

    /**
     * 资料单挑领取撤销
     */
    public function singlereceive()
    {
        $id       = I('request.id');     // 资料领取表ID
        $status   = I('request.status');     // 公告id
        $receive  = new ReceiveService();
        $result   = $receive->singlereceive($id, $status);
        if ($result) {
            $this->ajaxReturn(['msg'=>'操作成功～','code'=>1]);
        } else {
            $this->ajaxReturn(['msg'=>'操作失败～','code'=>2]);
        }
    }
}