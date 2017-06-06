<?php
/**
 * 投诉管理
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\ClassNoticeService;
use Common\Service\ComplainService;

class ComplainController extends AdminbaseController 
{
	/**
     * 班级投诉查询列表
     */
    public function index() 
    {   
        $zone_id     = I('request.zone_id');    // 分校（大）id
        $zone_sub_id = I('request.zone_sub_id');  // 校区id(小)
        $class_id    = I('request.class_id'); // 班级id
        $user        = I('request.user');  // 投诉人账号、姓名
        $user_con    = I('request.user_con');  // 要查询的投诉人的姓名账号
        $touser      = I('request.touser');  // 被投诉人账号、姓名
        $touser_con  = I('request.touser_con');  // 要查询的被投诉人的姓名账号
        $com_type    = I('request.com_type');  // 投诉类型
        $deal_status = I('request.deal_status');  // 状态
        $com         = I('request.com');  // 投诉编号和内容
        $com_con     = I('request.com_con');  // 查询投诉编号和内容的关键字
        $start_time  = I('request.start_time');  // 提问起始时间
        $end_time    = I('request.end_time');  // 提问终止时间
        
        
    	$class       = new ClassNoticeService();
    	$school      = $class->zone();
    	$complain    = new ComplainService();
    	$data        = $complain->getAll($zone_id, $zone_sub_id, $class_id, $user, $user_con, $touser, $touser_con, $com_type, $deal_status, $com, $com_con, $start_time, $end_time);
    	foreach($data['complain'] as $k=>$v){
    		if(mb_strlen($data['complain'][$k]['content']) > 10){
    			$data['complain'][$k]['content'] = mb_substr($data['complain'][$k]['content'], 0, 10)."...";
    		}
    	}
        
    	$this->assign('page', $data['page']);  // 分页数据显示
        $this->assign('complain', $data['complain']);  // 数据显示
        $this->assign('school', $school);  // 学校
       	$this->display();
    }

    /**
     * 班级投诉处理
     */
    public function details()
    {
        $id       = I('request.id');
        $complain = new ComplainService();
        $result   = $complain->details($id);
        $this->assign("com", $result['com']);        // 投诉人信息
        $this->assign("tocom", $result['tocom']);    // 被投诉人信息
        $this->display();
    }

    /**
     * 班级投诉处理
     */
    public function deal()
    {
        $id          = I('request.id');             // 投诉数据id
        $deal_status = I('request.deal_status');    // 投诉处理状态
        $deal_reason = I('request.deal_reason');    // 处理理由
        $remark      = I('request.remark');         // 处理备注
        $deal_time   = date('Y-m-d H:i:s', time()); // 处理时间

        $complain    = new ComplainService();
        $result      = $complain->deal($id, $deal_status, $deal_reason, $remark, $deal_time);
        if (!empty($deal_reason)){
            if ($result){
                $this->success("投诉处理成功", U("Complain/index"));
            } else {
                $this->error("投诉处理失败, 请重新处理");
            }
        } else {
            $this->error("处理理由不能为空");
        }
    }



}