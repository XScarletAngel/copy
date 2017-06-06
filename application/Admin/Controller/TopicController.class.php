<?php
/**
 * 班级公告
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\ClassNoticeService;
use Common\Service\TopicService;

class TopicController extends AdminbaseController 
{
	/**
     * 班级公告查询列表
     */
    public function index() 
    {   
        $class_id   = I('request.class_id');
        $topic_type = I('request.topic_type');  // 话题类型，包括话题标题和话题内容
        $topic_con  = I('request.topic_con'); // 话题查询内容
        $user_type  = I('request.user_type');  // 创建人账号、姓名
        $user       = I('request.user');  // 创建人内容
        $start_time = I('request.start_time');  // 提问起始时间
        $end_time   = I('request.end_time');  // 提问终止时间
        $is_ban     = I('request.is_ban');  // 是否被屏蔽
        
    	$class      = new ClassNoticeService();
    	$school     = $class->zone();
    	$suestion   = new TopicService();
    	$data       = $suestion->getAll($class_id, $topic_type, $topic_con, $user_type, $user, $start_time, $end_time, $is_ban);
    	$this->assign('page', $data['page']);  // 分页数据显示
        $this->assign('topic', $data['topic']);  // 数据显示
        $this->assign('school', $school);  // 学校
       	$this->display();
    }

    /**
     * 问题批量屏蔽
     */
    public function shield()
    {
        $ids      = I('request.ids');     // 公告id
        $question = new TopicService();
        $result   = $question->shield($ids);
        if ($result) {
            $this->ajaxReturn(['msg'=>'屏蔽成功～','code'=>1]);
        } else {
            $this->ajaxReturn(['msg'=>'屏蔽失败～','code'=>2]);
        }
    }

    /**
     * 问题批量屏蔽
     */
    public function recovery()
    {
        $ids      = I('request.ids');     // 公告id
        $question = new TopicService();
        $result   = $question->recovery($ids);
        if ($result) {
            $this->ajaxReturn(['msg'=>'恢复成功～','code'=>1]);
        } else {
            $this->ajaxReturn(['msg'=>'恢复失败～','code'=>2]);
        }
    }

    /**
     * 批量置顶
     */
    public function top()
    {
        $ids    = I('request.ids');     // 公告id
        $topic  = new TopicService();
        $result = $topic->top($ids);
        if ($result) {
            $this->ajaxReturn(['msg'=>'置顶成功～','code'=>1]);
        } else {
            $this->ajaxReturn(['msg'=>'置顶失败～','code'=>2]);
        }
    }

    /**
     * 批量置顶
     */
    public function notop()
    {
        $ids    = I('request.ids');     // 公告id
        $topic  = new TopicService();
        $result = $topic->notop($ids);
        if ($result) {
            $this->ajaxReturn(['msg'=>'操作成功～','code'=>1]);
        } else {
            $this->ajaxReturn(['msg'=>'操作失败～','code'=>2]);
        }
    }

    /**
     * 帖子详情页面，含回复
     */
    public function details()
    {
        $id    = I('get.id');
        $topic = new TopicService();
        $que   = $topic->details($id);  // 问题详情
        $reply = $topic->reply($id);    // 回复详情
        $this->assign('question', $que);
        $this->assign('reply', $reply);
        $this->display();
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
        $topic  = new TopicService();
        $result = $topic->details_layer_post($id, $is_ban);
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
        $topic  = new TopicService();
        $result = $topic->reply_shield($id);
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
        $topic  = new TopicService();
        $result = $topic->shield_layer_post($id, $is_ban);
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
        $topic  = new TopicService();
        $result = $topic->que_reply_shield($id);
        if($result){
            $res  = array('msg' => "操作成功", 'code' => 1);
        } else {
            $res  = array('msg' => "操作失败", 'code' => 0);
        }
        exit(json_encode($res));
    }
}