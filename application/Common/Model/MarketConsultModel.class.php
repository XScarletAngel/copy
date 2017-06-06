<?php
namespace Common\Model;

use Common\Model\CommonModel;

class MarketConsultModel extends CommonModel
{
	/**
     * @param $sql 条件
     * @return array
     */
    public function index($sql, $where)
    {
    	$consult = M("market_consult");
    	if(!empty($where['sc_no']) || !empty($where['phone']) || !empty($where['stu_name'])){
    	    $ysql = " and user_id = ".get_current_admin_id();
        } else {
    	    $ysql = " or deal_status = 1";
        }
        $count    = $consult->alias('c')->field("c.*, a.user_nicename as aname, b.user_nicename as bname")->join("left join ".C('DB_PREFIX')."admin_users as a on c.oper_user_id = a.id")->join("left join ".C('DB_PREFIX')."admin_users as b on c.now_user_id = b.id")->join("left join ".C('DB_PREFIX')."market_user_log as l on c.id = l.consult_id")->where($sql." and user_id = ".get_current_admin_id())->order("add_time desc")->count();
        $Page    = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        // //分页跳转的时候保证查询条件
        if (!empty($where)){
            foreach($where as $key=>$val) {
                $Page->parameter[$key]   =   urlencode($val);
            }
        }
        $show    = $Page->show();// 分页显示输出
        $data    = $consult->alias('c')->field("c.*, a.user_nicename as aname, b.user_nicename as bname")->join("left join ".C('DB_PREFIX')."admin_users as a on c.oper_user_id = a.id")->join("left join ".C('DB_PREFIX')."admin_users as b on c.now_user_id = b.id")->join("left join ".C('DB_PREFIX')."market_user_log as l on c.id = l.consult_id")->where($sql." and user_id = ".get_current_admin_id())->order("add_time desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $result  = array('data' => $data, 'page' => $show);
        return $result;
    }

    /*
     * 添加咨询单弹层页面获取数据
     */
    public function index_layer(){
        $user = M("admin_users");
        $data = $user->select();
        return $data;
    }

    /*
     * 添加咨询单弹层页面数据提交
     */
    public function index_layer_post($data){
        $consult = M("market_consult");   // 咨询单表
        $con_log = M("market_consult_log");  // 咨询单维度日志表
        $userlog = M("market_user_log");  // 人的维度，持有者日志表
        $user    = M("admin_users");
        $nostr   = date('Ymd', time()).rand(1000, 9999);
        $data['oper_user_id'] = get_current_admin_id();  // 分配人为当前登录用户
        $data['deal_status']  = 2;
        $data['sc_no']        = $nostr;
        if($data['now_user_id'] == ''){
            $data['now_user_id'] = get_current_admin_id();
        }

        $consult->startTrans();   // 启用事务
        $con_res = $consult->data($data)->add();  // 咨询单表添加数据
        /******************咨询单流程日志表添加数据开始********************/

        if($data['now_user_id'] != '' && $data['now_user_id'] != get_current_admin_id()){
            $b_user  = $user->where("id = ".$data['now_user_id'])->find();  // 查询被分配人信息
            $f_user  = $user->where("id = ".$data['oper_user_id'])->find(); // 查询分配人信息
            $arr = array('cid' => $con_res, 'oper_uid' => $f_user['id'], 'oper_uname' => $f_user['user_nicename'], 'oper_type' => 1, 'forward_uid' => $b_user['id'], 'forward_uname' => $b_user['user_nicename']);
            $con_log_res = $con_log->data($arr)->add();
        } else {
            $f_user  = $user->where("id = ".$data['oper_user_id'])->find(); // 查询分配人信息
            $arr = array('cid' => $con_res, 'oper_uid' => $f_user['id'], 'oper_uname' => $f_user['user_nicename'], 'oper_type' => 1);
            $con_log_res = $con_log->data($arr)->add();
        }

        /******************咨询单流程日志表添加数据结束********************/
        /******************持有人日志表添加数据结束***********************/

        if($data['now_user_id'] = $data['oper_user_id'] || $data['now_user_id'] = ''){  // 持有人与分配人相同，只有1条信息
            $user_arr = array('consult_id' => $con_res, 'user_id' => $data['now_user_id']);
            $user_res = $userlog->data($user_arr)->add();
        } else {
            $user_ar1 = array('consult_id' => $con_res, 'user_id' => $data['oper_user_id']);  // 分配人
            $user_ar2 = array('consult_id' => $con_res, 'user_id' => $data['now_user_id']);  // 现在持有人
            $user_re1 = $userlog->data($user_ar1)->add();  // 添加分配人信息
            $user_res = $userlog->data($user_ar2)->add();  // 添加被分配人信息
        }

        /******************持有人日志表添加数据结束***********************/

        if($con_res && $con_log_res && $user_res){
            $consult->commit();   //只有都执行成功是才真正执行上面的数据库操作
            return true;
        }else{
            $consult->rollback();  //  条件不满足，回滚
            return false;
        }

    }

    /*
     * 处理咨询单弹层页面
     */
    public function handle_layer($id){
        $consult = M("market_consult_log");
        $result  = $consult->where("cid = ".$id)->order("add_time desc")->select();
        return $result;
    }

    /*
     * 处理咨询单弹层页面数据提交
     */
    public function handle_layer_post($data){
        $user     = M("admin_users");
        $consult  = M("market_consult");  // 咨询单表
        $conlog   = M("market_consult_log");  // 咨询单流程日志表
        $userlog  = M("market_user_log");  // 咨询单经手人日志表
        $user_arr = $user->where("id = ".get_current_admin_id())->find();//当前登录用户信息
        $mc_arr   = $consult->where("id = ".$data['id'])->find();  // 查询当前咨询单的情况
        $consult->startTrans();   // 启用事务
        if($data['oper_type'] != 2){  // 没有分配
            if($mc_arr['oper_user_id'] == 0 && $mc_arr['now_user_id'] == 0){
                $add_arr = array('oper_user_id' => get_current_admin_id(), 'now_user_id' => get_current_admin_id(), 'deal_status' => $data['oper_type']);

                // 向持有人日志表添加数据
                $userlogarr = array('consult_id' => $data['id'], 'user_id' => get_current_admin_id());
                $userlog_res = $userlog->data($userlogarr)->add();
            } else {
                $add_arr = array('deal_status' => $data['oper_type']);
            }
            $con_res = $consult->where("id = ".$data['id'])->save($add_arr);  // 咨询单修改状态

            // 咨询单流程日志表添加数据
            $arr = array('cid' => $data['id'], 'oper_uid' => $user_arr['id'], 'oper_uname' => $user_arr['user_nicename'], 'oper_type' => $data['oper_type'], 'remark' => $data['remark']);
            $conlog_res = $conlog->data($arr)->add();
            if($con_res && $conlog_res){
                $consult->commit();   //只有都执行成功是才真正执行上面的数据库操作
                return 1;
            }else{
                $consult->rollback();  //  条件不满足，回滚
                return 0;
            }
        } elseif ($data['oper_type'] == 2 && $data['forward_uid'] != ''){  // 点击分配
            $con_arr = array('oper_user_id' => get_current_admin_id(), 'now_user_id' => $data['forward_uid'], 'deal_status' => 2);
            $con_res = $consult->where("id = ".$data['id'])->save($con_arr);  // 咨询单修改状态

            /*************以上修改咨询单表，以下修改咨询单日志表****************/
            $b_user = $user->where("id = ".$data['forward_uid'])->find();//被分配用户信息
            $arr = array('cid' => $data['id'], 'oper_uid' => $user_arr['id'], 'oper_uname' => $user_arr['user_nicename'], 'oper_type' => $data['oper_type'], 'remark' => $data['remark'], 'forward_uid' => $b_user['id'], 'forward_uname' => $b_user['user_nicename']);
            $conlog_res = $conlog->data($arr)->add();
            /*************以上修改咨询单日志表，以下修改持有人日志表****************/
            $userlogarr = array('consult_id' => $data['id'], 'user_id' => $data['forward_uid']);
            $userlog_res = $userlog->data($userlogarr)->add();
            if($con_res && $conlog_res && $userlog_res){
                $consult->commit();   //只有都执行成功是才真正执行上面的数据库操作
                return 1;
            }else{
                $consult->rollback();  //  条件不满足，回滚
                return 0;
            }
        }
    }

    /**
     * 我的咨询单
     * @param $sql 条件
     * @return array
     */
    public function deal($sql, $where)
    {
        $consult = M("market_consult");
        $log     = M("market_user_log");
        $userarr = $log->where("user_id = ".get_current_admin_id())->select();

        foreach($userarr as $k=>$v){
            $ids .= $userarr[$k]['consult_id'].",";
        }
        $count    = $consult->field(C('DB_PREFIX')."market_consult.*, a.user_nicename as aname, b.user_nicename as bname")->join("left join ".C('DB_PREFIX')."admin_users as a on ".C('DB_PREFIX')."market_consult.oper_user_id = a.id")->join("left join ".C('DB_PREFIX')."admin_users as b on ".C('DB_PREFIX')."market_consult.now_user_id = b.id")->where($sql." and ".C('DB_PREFIX')."market_consult.id in(".rtrim($ids, ',').")")->order("add_time desc")->count();
        $Page    = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        // //分页跳转的时候保证查询条件
        if (!empty($where)){
            foreach($where as $key=>$val) {
                $Page->parameter[$key]   =   urlencode($val);
            }
        }
        $show    = $Page->show();// 分页显示输出
        $data    = $consult->field(C('DB_PREFIX')."market_consult.*, a.user_nicename as aname, b.user_nicename as bname")->join("left join ".C('DB_PREFIX')."admin_users as a on ".C('DB_PREFIX')."market_consult.oper_user_id = a.id")->join("left join ".C('DB_PREFIX')."admin_users as b on ".C('DB_PREFIX')."market_consult.now_user_id = b.id")->where($sql." and ".C('DB_PREFIX')."market_consult.id in(".rtrim($ids, ',').")")->order("add_time desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $result  = array('data' => $data, 'page' => $show);
        return $result;
    }

    /*
     * 获取未处理咨询单的数量
     */
    public function GetCount(){
        $consult  = M("market_consult");  // 咨询单表
        $complain = M("complain");        // 投诉表
        $notice   = M("system_notice");   // 系统公告表
        $adminlog = M("admin_log");

        $con_num  = $consult->where("deal_status = 2 and now_user_id = ".get_current_admin_id())->count();  // 待处理咨询单
        $com_num  = $complain->where("deal_status = 0")->count();  // 待处理投诉单
        $not_num  = $notice->where("add_time <= '".date('Y-m-d H:i:s', time())."'")->order("add_time desc")->limit(3)->select();
        $log      = $adminlog->field(C('DB_PREFIX')."admin_log.add_time, ".C('DB_PREFIX')."admin_users.user_login")->join("left join ".C('DB_PREFIX')."admin_users on ".C('DB_PREFIX')."admin_log.admin_id = ".C('DB_PREFIX')."admin_users.id")->where(C('DB_PREFIX')."admin_log.action_name = 'dologin'")->order("add_time desc")->limit(10)->select();
        $result   = array('con_num' => $con_num, 'com_num' => $com_num, 'not_num' => $not_num, 'log' => $log);
        return $result;
    }


}