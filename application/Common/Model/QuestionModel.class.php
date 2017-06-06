<?php
namespace Common\Model;

use Common\Model\CommonModel;

class QuestionModel extends CommonModel
{

	/**
	 * @param $where 条件(数组) 
	 * @param $field 字段
	 * @return array
	 */
	public function getAll($sql, $where)
	{
		$question = M('question');
		$reply    = M('question_reply');
        $count    = $question->field(C('DB_PREFIX').'question.id, que_name, is_ban')->join(' '.C('DB_PREFIX').'users on '.C('DB_PREFIX').'question.user_id = '.C('DB_PREFIX').'users.id')->where($sql.' and is_del = 1')->count();  // 查找所有的问题
		 $Page    = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		 //分页跳转的时候保证查询条件
		 if (!empty($where)){
		 	foreach($where as $key=>$val) {
		 	    $Page->parameter[$key]   =   urlencode($val);
		 	}
		 }
        $show     = $Page->show();// 分页显示输出
		$question = $question->field(C('DB_PREFIX').'question.id, que_name, is_ban')->join(' '.C('DB_PREFIX').'users on '.C('DB_PREFIX').'question.user_id = '.C('DB_PREFIX').'users.id')->where($sql.' and is_del = 1')->limit($Page->firstRow.','.$Page->listRows)->select();  // 查找所有的问题
		foreach($question as $k=>$v){
			$question_reply = $reply->field('content as reply_con')->where("parentid = 0 and que_id = ".$question[$k]['id'])->order('add_time desc')->find();
			if($question_reply != ''){
				$question[$k]['con'] = $question_reply['reply_con'];
			} else {
				$question[$k]['con'] = '';
			}
		}
		$data	  = array('question' => $question, 'page' => $show);
		return $data;
	}

    /**
     * 问题批量屏蔽
     */
    public function shield($ids)
    {
        $question   = M('question');
        $arr        = explode(',', $ids);
        for($i=0;$i<count($arr);$i++){
            $result = $question->where(' id = '.$arr[$i])->setField('is_ban', '1');
        }
        return $result;
    }

    /**
     * 问题批量恢复
     */
    public function recovery($ids)
    {
        $question   = M('question');
        $arr        = explode(',', $ids);
        for($i=0;$i<count($arr);$i++){
            $result = $question->where(' id = '.$arr[$i])->setField('is_ban', '0');
        }
        return $result;
    }

    /**
     * 问题详情，只是问题C("DB_PRIFIX")
     */
    public function details($id)
    {
        $question = M('question');
        $question = $question->field(C('DB_PREFIX')."question.*, ".C('DB_PREFIX')."users.user_login, ".C('DB_PREFIX')."users.user_nicename")->join(C('DB_PREFIX').'users on '.C('DB_PREFIX').'question.user_id = '.C('DB_PREFIX').'users.id')->where(C('DB_PREFIX')."question.id = ".$id)->find();
        return $question;
    }

    /**
     * 问题详情，只是回复
     */
    public function reply($id)
    {
        $user     = M("users"); 
        $question = M('question_reply');
        $reply = $question->field(C('DB_PREFIX')."question_reply.*, a.user_login, a.user_nicename, a.avatar, a.user_type, b.user_login as buser_login, b.user_nicename as buser_nicename, b.avatar as bavatar, b.user_type as buser_type")->join("left join ".C('DB_PREFIX').'users as a on '.C('DB_PREFIX').'question_reply.user_id = a.id')->join("left join ".C('DB_PREFIX').'users as b on '.C('DB_PREFIX').'question_reply.to_uid = b.id')->where(C('DB_PREFIX')."question_reply.que_id = ".$id)->select();
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
     * 采纳回复的数据
     */
    public function adopt($id)
    {
        $question = M("question_reply");
        $array    = array('is_accept' => 2);
        $que      = $question->where(["id"=>$id])->save($array);
        return $que;
    }

    /**
     * 屏蔽回复弹层页面数据提交(屏蔽回复)
     */
    public function details_layer_post($id, $is_ban)
    {
        $que  = M("question_reply");
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
        $que    = M("question_reply");
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
        $que  = M("question");
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
        $que    = M("question");
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