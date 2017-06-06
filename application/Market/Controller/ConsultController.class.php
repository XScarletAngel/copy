<?php
/**
 * 客户档案
 */
namespace Market\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\ConsultService;

class ConsultController extends AdminbaseController
{
    /*
     * 咨询单列表
     */
    public function index()
    {
        $sc_no       = I("request.sc_no");
        $phone       = I("request.phone");
        $stu_name    = I("request.stu_name");
        $deal_status = I("request.deal_status");
        $channel     = I("request.channel");
        $start_time  = I("request.start_time");
        $end_time    = I("request.end_time");

        $consult     = new ConsultService();
        $da          = $consult->index($sc_no, $phone, $stu_name, $deal_status, $channel, $start_time, $end_time);
        $uid         = get_current_admin_id();  // 当前登录用户的ID
        $data        = $da['data'];
        foreach($data as $k=>$v){
        // 状态
            if($data[$k]['deal_status'] == 1){
                $data[$k]['type'] = 1;    // 待响应
            } elseif ($data[$k]['deal_status'] != 1 && $data[$k]['deal_status'] != 4 && $data[$k]['now_user_id'] != $uid){
                $data[$k]['type'] = 2;    // 待别人处理
            } elseif ($data[$k]['deal_status'] != 1 && $data[$k]['deal_status'] != 4 && $data[$k]['now_user_id'] == $uid){
                $data[$k]['type'] = 3;    // 待我处理
            } elseif ($data[$k]['deal_status'] == 4){
                $data[$k]['type'] = 4;    // 处理完成
            }
        // 操作显示
            if($data[$k]['deal_status'] == 1 || ($data[$k]['deal_status'] != 1 && $data[$k]['deal_status'] != 4 && $data[$k]['now_user_id'] == $uid)){
                $data[$k]['active'] = 1;    // 显示处理
            } elseif($data[$k]['deal_status'] == 4 || ($data[$k]['deal_status'] != 1 && $data[$k]['deal_status'] != 4 && $data[$k]['now_user_id'] != $uid)){
                $data[$k]['active'] = 2;    // 显示查看
            }
        }
        

        $this->assign("page", $da['page']);
        $this->assign("data", $data);
        $this->assign("uid", $uid);
        $this->display();
    }

    /*
     * 添加咨询单弹层页面
     */
    public function index_layer(){
        $consult = new ConsultService();
        $data    = $consult->index_layer();
        $this->assign("data", $data);
        $this->display();
    }

    /*
     * 添加咨询单弹层页面数据提交
     */
    public function index_layer_post(){
        $data    = $_POST;
        if(empty($data['phone']) || empty($data['stu_name']) || empty($data['dst_shool']) || empty($data['dst_major'])){
            $this->error("请添加完成数据");
        } else {
            $consult = new ConsultService();
            $result  = $consult->index_layer_post($data);
            if($result){
                $this->success("添加成功");
                echo "<script>parent.location.reload();</script>";
            } else {
                $this->error("添加失败,请重新添加");
                echo "<script>parent.location.reload();</script>";
            }
        }
    }

    /*
     * 处理咨询单弹层页面
     */
    public function handle_layer(){
        $id      = I('request.id');
        $consult = new ConsultService();
        $data    = $consult->index_layer();  // 显示的被分配人
        $datalog = $consult->handle_layer($id);  // 查看咨询单的流程日志
        $this->assign("data", $data);
        $this->assign("datalog", $datalog);
        $this->assign("id", $id);  // 咨询单id
        $this->display();
    }

    /*
     * 处理咨询单弹层页面数据提交
     */
    public function handle_layer_post(){
        $data = $_POST;
        $consult = new ConsultService();
        $result  = $consult->handle_layer_post($data);
        if($result == 1){
            $this->success("添加成功");
            echo "<script>parent.location.reload();</script>";
        } else {
            $this->error("添加失败,请重新添加");
            echo "<script>parent.location.reload();</script>";
        }
    }

    /*
     * 查看咨询单弹层页面
     */
    public function see_layer(){
        $id      = I('request.id');
        $consult = new ConsultService();
        $datalog = $consult->handle_layer($id);  // 查看咨询单的流程日志
        $this->assign("datalog", $datalog);
        $this->display();
    }

    /*
     * 我的咨询单列表
     */
    public function deal()
    {
        $sc_no       = I("request.sc_no");
        $phone       = I("request.phone");
        $stu_name    = I("request.stu_name");
        $deal_status = I("request.deal_status");
        $channel     = I("request.channel");
        $start_time  = I("request.start_time");
        $end_time    = I("request.end_time");

        $consult     = new ConsultService();
        $da          = $consult->deal($sc_no, $phone, $stu_name, $deal_status, $channel, $start_time, $end_time);
        $uid         = get_current_admin_id();  // 当前登录用户的ID
        $data        = $da['data'];
        foreach($data as $k=>$v){
        // 状态
            if($data[$k]['deal_status'] == 1){
                $data[$k]['type'] = 1;    // 待响应
            } elseif ($data[$k]['deal_status'] != 1 && $data[$k]['deal_status'] != 4 && $data[$k]['now_user_id'] != $uid){
                $data[$k]['type'] = 2;    // 待别人处理
            } elseif ($data[$k]['deal_status'] != 1 && $data[$k]['deal_status'] != 4 && $data[$k]['now_user_id'] == $uid){
                $data[$k]['type'] = 3;    // 待我处理
            } elseif ($data[$k]['deal_status'] == 4){
                $data[$k]['type'] = 4;    // 处理完成
            }
        // 操作显示
            if($data[$k]['deal_status'] == 1 || ($data[$k]['deal_status'] != 1 && $data[$k]['deal_status'] != 4 && $data[$k]['now_user_id'] == $uid)){
                $data[$k]['active'] = 1;    // 显示处理
            } elseif($data[$k]['deal_status'] == 4 || ($data[$k]['deal_status'] != 1 && $data[$k]['deal_status'] != 4 && $data[$k]['now_user_id'] != $uid)){
                $data[$k]['active'] = 2;    // 显示查看
            }
        }
        

        $this->assign("page", $da['page']);
        $this->assign("data", $data);
        $this->assign("uid", $uid);
        $this->display();
    }




}