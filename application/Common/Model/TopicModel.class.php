<?php
namespace Common\Model;

use Common\Model\CommonModel;

class TopicModel extends CommonModel
{

	/**
	 * @param $where 条件(数组) 
	 * @param $field 字段
	 * @return array
	 */
	public function getAll($sql, $where)
	{
		$topic = M('topic');
		$reply = M('topic_reply');
        $count = $topic->field(C('DB_PREFIX').'topic.id, content, is_ban')->join(C('DB_PREFIX').'users on '.C('DB_PREFIX').'topic.user_id = '.C('DB_PREFIX').'users.id')->where($sql.' and is_del = 1')->count();  // 查找所有的问题
		 $Page = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		 //分页跳转的时候保证查询条件
		 if (!empty($where)){
		 	foreach($where as $key=>$val) {
		 	    $Page->parameter[$key]   =   urlencode($val);
		 	}
		 }
        $show  = $Page->show();// 分页显示输出
		$topic = $topic->field(C('DB_PREFIX').'topic.id, content, view_times, topic_num, is_ban, is_top')->join(C('DB_PREFIX').'users on '.C('DB_PREFIX').'topic.user_id = '.C('DB_PREFIX').'users.id')->where($sql.' and is_del = 1')->order("is_top desc")->limit($Page->firstRow.','.$Page->listRows)->select();  // 查找所有的问题
		foreach($topic as $k=>$v){
			$topic_reply = $reply->field('content as reply_con')->where("parentid = 0 and topic_id = ".$topic[$k]['id'])->order('add_time desc')->find();
			if($topic_reply != ''){
				$topic[$k]['con'] = $topic_reply['reply_con'];
			} else {
				$topic[$k]['con'] = '';
			}
		}
		$data	  = array('topic' => $topic, 'page' => $show);
		return $data;
	}

	/**
     * 帖子批量屏蔽
     */
    public function shield($ids)
    {
        $topic      = M('topic');
        $arr        = explode(',', $ids);
        for($i=0;$i<count($arr);$i++){
            $result = $topic->where(' id = '.$arr[$i])->setField('is_ban', '1');
        }
        return $result;
    }

    /**
     * 帖子批量恢复
     */
    public function recovery($ids)
    {
        $topic      = M('topic');
        $arr        = explode(',', $ids);
        for($i=0;$i<count($arr);$i++){
            $result = $topic->where(' id = '.$arr[$i])->setField('is_ban', '0');
        }
        return $result;
    }

    /**
     * 帖子批量置顶
     */
    public function top($ids)
    {
        $topic      = M('topic');
        $arr        = explode(',', $ids);
        for($i=0;$i<count($arr);$i++){
            $result = $topic->where(' id = '.$arr[$i])->setField('is_top', '2');
        }
        return $result;
    }

    /**
     * 帖子批量取消置顶
     */
    public function notop($ids)
    {
        $topic      = M('topic');
        $arr        = explode(',', $ids);
        for($i=0;$i<count($arr);$i++){
            $result = $topic->where(' id = '.$arr[$i])->setField('is_top', '1');
        }
        return $result;
    }

    /**
     * 问题详情，只是问题C("DB_PRIFIX")
     */
    public function details($id)
    {
        $topic = M('topic');
        $topic = $topic->field(C('DB_PREFIX')."topic.*, ".C('DB_PREFIX')."users.user_login, ".C('DB_PREFIX')."users.user_nicename")->join(C('DB_PREFIX').'users on '.C('DB_PREFIX').'topic.user_id = '.C('DB_PREFIX').'users.id')->where(C('DB_PREFIX')."topic.id = ".$id)->find();
        return $topic;
    }

    /**
     * 问题详情，只是回复
     */
    public function reply($id)
    {
        $user  = M("users"); 
        $topic_reply = M('topic_reply');
        $reply = $topic_reply->field(C('DB_PREFIX')."topic_reply.*, a.user_login, a.user_nicename, a.avatar, a.user_type, b.user_login as buser_login, b.user_nicename as buser_nicename, b.avatar as bavatar, b.user_type as buser_type")->join("left join ".C('DB_PREFIX').'users as a on '.C('DB_PREFIX').'topic_reply.user_id = a.id')->join("left join ".C('DB_PREFIX').'users as b on '.C('DB_PREFIX').'topic_reply.to_uid = b.id')->where(C('DB_PREFIX')."topic_reply.topic_id = ".$id)->select();
        $categorys = $this->noLimitCate($reply, $parentid=0, $level=0);
        return $categorys;
    }

    /*
     * 无限极分类
     */
    public function noLimitCate($data, $parentid=0, $level=0){
        $str = "";
        for($i=0;$i<=$level;$i++){
            $str .= "";
        }
        static $list1=array();
        foreach ($data as $key => $v) {
            if($v['parentid']==$parentid){
                $v['level']=$level;
                $v['user_nicename'] = $str.$v['user_nicename'];
                $list1[]=$v;
                $this->noLimitCate($data, $v['id'], $level+1);
            }
        }
        return $list1;
    }

    /**
     * 屏蔽回复弹层页面数据提交(屏蔽回复)
     */
    public function details_layer_post($id, $is_ban)
    {
        $que  = M("topic_reply");
        $user = M("users");
        $arr  = $que->where("id = ".$id)->find();  // 查询要修改的回复信息
        $uid  = $arr['user_id'];
        $data = array('is_ban' => $is_ban);
        if($is_ban == 1){
            $que_res = $que->where("id = ".$id)->save($data);
            if($que_res){
                return true;
            } else {
                return false;
            }
        } elseif($is_ban == 2){
            $u_arr    = array('no_talk' => 2);
            $que_res  = $que->where("id = ".$id)->save($data);
            $user_res = $user->where("id = ".$uid)->save($u_arr);
            if($que_res && $user_res){
                return true;
            } else {
                return false;
            }
        } elseif($is_ban == 3){
            $u_arr    = array('user_status' => 0);
            $que_res  = $que->where("id = ".$id)->save($data);
            $user_res = $user->where("id = ".$uid)->save($u_arr);
            if($que_res && $user_res){
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * 详情页回复数据恢复提交（恢复回复）
     */
    public function reply_shield($id)
    {
        $que    = M("topic_reply");
        $user   = M("users");
        $quearr = $que->where(["id" => $id])->find();
        $data   = array('is_ban' => 0);
        if($quearr['is_ban'] == 1){
            $que_res = $que->where(["id" => $id])->save($data);
            if($que_res){
                return true;
            } else {
                return false;
            }
        } elseif ($quearr['is_ban'] == 2){
            $u_arr    = array('no_talk' => 1);
            $que_res = $que->where(["id" => $id])->save($data);
            $user_res = $user->where("id = ".$quearr['user_id'])->save($u_arr);
            if($que_res && $user_res){
                return true;
            } else {
                return false;
            }
        } elseif ($quearr['is_ban'] == 3){
            $u_arr    = array('user_status' => 1);
            $que_res = $que->where(["id" => $id])->save($data);
            $user_res = $user->where("id = ".$quearr['user_id'])->save($u_arr);
            if($que_res && $user_res){
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * 屏蔽回复弹层页面数据提交（屏蔽问题）
     */
    public function shield_layer_post($id, $is_ban)
    {
        $que  = M("topic");
        $user = M("users");
        $arr  = $que->where("id = ".$id)->find();  // 查询要修改的回复信息
        $uid  = $arr['user_id'];
        $data = array('is_ban' => $is_ban);
        if($is_ban == 1){
            $que_res = $que->where("id = ".$id)->save($data);
            if($que_res){
                return true;
            } else {
                return false;
            }
        } elseif($is_ban == 2){
            $u_arr    = array('no_talk' => 2);
            $que_res  = $que->where("id = ".$id)->save($data);
            $user_res = $user->where("id = ".$uid)->save($u_arr);
            if($que_res && $user_res){
                return true;
            } else {
                return false;
            }
        } elseif($is_ban == 3){
            $u_arr    = array('user_status' => 0);
            $que_res  = $que->where("id = ".$id)->save($data);
            $user_res = $user->where("id = ".$uid)->save($u_arr);
            if($que_res && $user_res){
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * 详情页回复数据恢复提交（恢复问题）
     */
    public function que_reply_shield($id)
    {
        $que    = M("topic");
        $user   = M("users");
        $quearr = $que->where(["id" => $id])->find();
        $data   = array('is_ban' => 0);
        if($quearr['is_ban'] == 1){
            $que_res = $que->where(["id" => $id])->save($data);
            if($que_res){
                return true;
            } else {
                return false;
            }
        } elseif ($quearr['is_ban'] == 2){
            $u_arr    = array('no_talk' => 1);
            $que_res = $que->where(["id" => $id])->save($data);
            $user_res = $user->where("id = ".$quearr['user_id'])->save($u_arr);
            if($que_res && $user_res){
                return true;
            } else {
                return false;
            }
        } elseif ($quearr['is_ban'] == 3){
            $u_arr    = array('user_status' => 1);
            $que_res = $que->where(["id" => $id])->save($data);
            $user_res = $user->where("id = ".$quearr['user_id'])->save($u_arr);
            if($que_res && $user_res){
                return true;
            } else {
                return false;
            }
        }
    }


}