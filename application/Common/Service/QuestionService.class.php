<?php
namespace Common\Service;

use Common\Model\QuestionModel;

class QuestionService 
{
	/**
	 * @param $search 条件(数组)
	 * @return array
	 */
	public function getAll($class_id, $que_type, $que_con, $accept_id, $user_type, $user, $start_time, $end_time, $is_ban)
	{
	    $where    = array('class_id' => $class_id, 'que_type' => $que_type, 'que_con' => $que_con, 'accept_id' => $accept_id, 'user_type' => $user_type, 'user' => $user, 'start_time' => $start_time, 'end_time' => $end_time, 'is_ban' => $is_ban);
		$sql      = "1=1";
		if ($class_id != ''){
			$sql .= " and class_id = ".$class_id;
		}
		if ($que_type != '' && $que_con != ''){
			$sql .= " and ".$que_type." like '".$que_con."%'";
		}
		if ($accept_id != '' && $accept_id == '2'){  // 没有采纳，待回答
			$sql .= " and accept_id = 0";
		}
		if ($accept_id != '' && $accept_id == '1'){  // 已采纳，已回答
			$sql .= " and accept_id > 0";
		}
		if ($user_type != '' && $user != ''){
			$sql .= " and ".$user_type." = '".$user."'";
		}
		if ($start_time != ''){
			$sql .= " and add_time >= '".$start_time."'";
		}
		if ($end_time != ''){
			$sql .= " and add_time <= '".$end_time."'";
		}
		if ($is_ban != '' && $is_ban == '2'){
			$sql .= " and is_ban = 0";
		}
		if ($is_ban != '' && $is_ban == '1'){
			$sql .= " and is_ban > 0";
		}
		$question = new QuestionModel();
		$result   = $question->getAll($sql, $where);
		return $result;
	}

    /**
     * 问题批量屏蔽
     */
    public function shield($ids)
    {
        $question = new QuestionModel();
        $result   = $question->shield($ids);
        return $result;
    }

    /**
     * 问题批量恢复
     */
    public function recovery($ids)
    {
        $question = new QuestionModel();
        $result   = $question->recovery($ids);
        return $result;
    }

    /**
     * 问题详情
     */
    public function details($id)
    {
        $question = new QuestionModel();
        $question = $question->details($id);
        return $question;
    }

    /**
     * 问题回复详情
     */
    public function reply($id)
    {
        $question = new QuestionModel();
        $reply    = $question->reply($id);
        return $reply;
    }

    /**
     * 采纳回复
     */
    public function adopt($id)
    {
        $question = new QuestionModel();
        $que      = $question->adopt($id);  // 问题详情
        return $que;
    }

    /**
     * 屏蔽弹层页面数据提交
     */
    public function details_layer_post($id, $is_ban)
    {
        $question = new QuestionModel();
        $que      = $question->details_layer_post($id, $is_ban);  
        return $que;
    }

    /**
     * 详情页回复数据恢复提交
     */
    public function reply_shield($id)
    {
        $que    = new QuestionModel();
        $result = $que->reply_shield($id);
        return $result;
    }

    /**
     * 问题屏蔽弹层页面数据提交
     */
    public function shield_layer_post($id, $is_ban)
    {
        $question = new QuestionModel();
        $que      = $question->shield_layer_post($id, $is_ban);  
        return $que;
    }

    /**
     * 问题数据恢复提交
     */
    public function que_reply_shield($id)
    {
        $que    = new QuestionModel();
        $result = $que->que_reply_shield($id);
        return $result;
    }
}