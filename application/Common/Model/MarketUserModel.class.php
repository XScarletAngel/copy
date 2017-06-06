<?php
namespace Common\Model;

use Common\Model\CommonModel;

class MarketUserModel extends CommonModel
{
	/**
     * @param $sql 条件
     * @return array
     */
    public function index($sql, $where)
    {
        $user  = M("market_user");
        $count = $user->where($sql." and is_del = 1")->count();
        $Page        = new \Think\Page($count,25);// 实例化分页类
        if (!empty($where)){
			foreach($where as $key=>$val) {
			    $Page->parameter[$key]   =   urlencode($val);
			}
		}
		$show = $Page->show();// 分页显示输出
		$data = $user->where($sql." and is_del = 1")->limit($Page->firstRow.','.$Page->listRows)->select();
		$res  = array('page' => $show, 'data' => $data);
        return $res;
    }

    /**
     * @param $sql 条件
     * @return array
     */
    public function add_layer()
    {
        $user = M("market_activity");
        $data = $user->where("is_del = 1")->select();
        return $data;
    }

    /**
     * 添加客户档案数据处理
     */
    public function add_post($data) 
    {
    	$user   = M("market_user");
    	$mcat   = M("market_user_act");
    	$cat    = $data['cat'];
    	unset($data['cat']);
    	$one    = $user->where("user_phone = ".$data['user_phone'])->find();
		if(empty($one)){
			$result = $user->data($data)->add();
			if($result){
				if(!empty($cat)){
					foreach($cat as $k=>$v){
						$cat_ar = array('mkt_user_id' => $result, 'act_id' => $cat[$k]);
						$res = $mcat->data($cat_ar)->add();
					}
					if($res){
						return 1;  // 成功
					} else {
						return 2;
					}
				} else {
					return 1;  // 成功
				}
			} else {
				return 0;
			}
		} else {
			return 3;
		}
    }

    /**
     * 客户档案编辑
     */
    public function edit($id) 
    {
    	$user = M("market_user");
    	$mcat = M("market_user_act");
    	$res  = $user->where("id = ".$id)->find();  // 客户数据
    	$arr  = $mcat->where("mkt_user_id = ".$id)->select();  // 用户参加的活动
    	$resu = array('res' => $res, 'arr' => $arr);
    	return $resu;
    }

    /**
     * 客户档案编辑数据提交
     */
    public function edit_post($data) 
    {
    	$user     = M("market_user");
    	$mcat     = M("market_user_act");
    	$cat      = $data['cat'];
    	unset($data['cat']);
    	$del_res  = $mcat->where("mkt_user_id = ".$data['id'])->delete();
    	// if(!empty($cat)){
		foreach($cat as $k=>$v){
			$cat_ar = array('mkt_user_id' => $data['id'], 'act_id' => $cat[$k]);
			$res = $mcat->data($cat_ar)->add();
		}
    	// }
    	$user_res = $user->where("id = ".$data['id'])->save($data);
    	if($user_res || $res){
    		return 1;
    	} else {
    		return 0;
    	}
    }

    /**
     * 客户档案查看页面
     */
    public function info($id) 
    {
    	$user = M("market_user");
    	$mcat = M("market_user_act");
    	$acti = M("market_activity");
    	$res  = $user->where("id = ".$id)->find();  // 客户的数据
    	$cat  = $mcat->where("mkt_user_id = ".$id)->select();
    	foreach($cat as $k=>$v){
    		$ids .= $cat[$k]['act_id'].",";
    	}
    	if(!empty($ids)){
    		$acts = $acti->where("id in(".rtrim($ids, ',').")")->select();  // 客户参加的活动
    	}
    	$result = array('res' => $res, 'acts' => $acts);
    	return $result;
    }
}