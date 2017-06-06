<?php
/**
 * 班级公告
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\ClassNoticeService;
use Common\Service\QuestionService;

class QuestionController extends AdminbaseController 
{
	/**
     * 班级公告查询列表
     */
    public function index() 
    {   
        $class_id   = I('request.class_id');
        $que_type   = I('request.que_type');  // 问题类型，包括问题标题和问题内容
        $que_con    = I('request.que_con'); // 问题查询内容
        $accept_id  = I('request.accept_id');  // 被采纳，大于0就是已被采纳
        $user_type  = I('request.user_type');  // 提问人账号、姓名
        $user       = I('request.user');  // 提问人内容
        $start_time = I('request.start_time');  // 提问起始时间
        $end_time   = I('request.end_time');  // 提问终止时间
        $is_ban     = I('request.is_ban');  // 是否被屏蔽
        
    	$class      = new ClassNoticeService();
    	$school     = $class->zone();
    	$suestion   = new QuestionService();
    	$data       = $suestion->getAll($class_id, $que_type, $que_con, $accept_id, $user_type, $user, $start_time, $end_time, $is_ban);
    	$this->assign('page', $data['page']);  // 分页数据显示
        $this->assign('question', $data['question']);  // 数据显示
        $this->assign('school', $school);  // 学校
        // $this->assign('page', $data['page']);  // 分页数据显示
       	$this->display();
    }

    /**
     * 问题批量屏蔽
     */
    public function shield()
    {
        $ids      = I('request.ids');     // 公告id
        $question = new QuestionService();
        $result   = $question->shield($ids);
        if ($result) {
            $this->ajaxReturn(['msg'=>'修改成功～','code'=>1]);
        } else {
            $this->ajaxReturn(['msg'=>'修改失败～','code'=>2]);
        }
    }

    /**
     * 问题批量屏蔽
     */
    public function recovery()
    {
        $ids      = I('request.ids');     // 公告id
        $question = new QuestionService();
        $result   = $question->recovery($ids);
        if ($result) {
            $this->ajaxReturn(['msg'=>'修改成功～','code'=>1]);
        } else {
            $this->ajaxReturn(['msg'=>'修改失败～','code'=>2]);
        }
    }

    /**
     * 问题详情页面，含回复
     */
    public function details()
    {
        $id       = I('get.id');
        $question = new QuestionService();
        $que      = $question->details($id);  // 问题详情
        $reply    = $question->reply($id);    // 回复详情
        $this->assign('question', $que);
        $this->assign('reply', $reply);
        $this->display();
    }

    /**
     * 采纳回复的数据
     */
    public function adopt()
    {
        $id       = I('request.id');
        $question = new QuestionService();
        $que      = $question->adopt($id);
        if($que){
            $res  = array('msg' => "采纳成功", 'code' => 1);
        } else {
            $res  = array('msg' => "采纳失败", 'code' => 0);
        }
        exit(json_encode($res));
    }

    /**
     * 屏蔽弹层页面
     */
    public function details_layer()
    {
        $id = I('request.id');
        $this->assign("id", $id);
        $this->display();
    }

    /**
     * 屏蔽弹层页面数据提交
     */
    public function details_layer_post()
    {
        $id     = I('request.id');
        $is_ban = I('request.is_ban');
        $que    = new QuestionService();
        $result = $que->details_layer_post($id, $is_ban);
        if($result){
            $this->success("屏蔽成功");
            echo "<script>parent.location.reload();</script>";
        } else {
            $this->error("屏蔽失败,请重新操作");
            echo "<script>parent.location.reload();</script>";
        }
    }

    /**
     * 详情页回复数据恢复提交
     */
    public function reply_shield()
    {
        $id     = I('request.id');
        $que    = new QuestionService();
        $result = $que->reply_shield($id);
        if($result){
            $res  = array('msg' => "操作成功", 'code' => 1);
        } else {
            $res  = array('msg' => "操作失败", 'code' => 0);
        }
        exit(json_encode($res));
    }

    /**
     * 问题弹层页面
     */
    public function shield_layer()
    {
        $id = I('request.id');
        $this->assign("id", $id);
        $this->display();
    }

    /**
     * 问题屏蔽弹层页面数据提交
     */
    public function shield_layer_post()
    {
        $id     = I('request.id');
        $is_ban = I('request.is_ban');
        $que    = new QuestionService();
        $result = $que->shield_layer_post($id, $is_ban);
        if($result){
            $this->success("屏蔽成功");
            echo "<script>parent.location.reload();</script>";
        } else {
            $this->error("屏蔽失败,请重新操作");
            echo "<script>parent.location.reload();</script>";
        }
    }

    /**
     * 问题数据恢复提交
     */
    public function que_reply_shield()
    {
        $id     = I('request.id');
        $que    = new QuestionService();
        $result = $que->que_reply_shield($id);
        if($result){
            $res  = array('msg' => "操作成功", 'code' => 1);
        } else {
            $res  = array('msg' => "操作失败", 'code' => 0);
        }
        exit(json_encode($res));
    }

}