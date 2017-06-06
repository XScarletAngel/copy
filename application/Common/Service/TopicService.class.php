<?php
namespace Common\Service;

use Common\Model\TopicModel;

class TopicService 
{
	/**
	 * @param $search 条件(数组)
	 * @return array
	 */
	public function getAll($class_id, $topic_type, $topic_con, $user_type, $user, $start_time, $end_time, $is_ban)
	{
	    $where    = array('class_id' => $class_id, 'topic_type' => $topic_type, 'topic_con' => $topic_con, 'user_type' => $user_type, 'user' => $user, 'start_time' => $start_time, 'end_time' => $end_time, 'is_ban' => $is_ban);
		$sql      = "1=1";
		if ($class_id != ''){
			$sql .= " and class_id = ".$class_id;
		}
		if ($topic_type != '' && $topic_con != ''){
			$sql .= " and ".$topic_type." like '".$topic_con."%'";
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
		$topic  = new TopicModel();
		$result = $topic->getAll($sql, $where);
		return $result;
	}

	/**
     * 帖子批量屏蔽
     */
    public function shield($ids)
    {
        $topic  = new TopicModel();
        $result = $topic->shield($ids);
        return $result;
    }

    /**
     * 帖子批量恢复
     */
    public function recovery($ids)
    {
        $topic  = new TopicModel();
        $result = $topic->recovery($ids);
        return $result;
    }

    /**
     * 帖子批量置顶
     */
    public function top($ids)
    {
        $topic  = new TopicModel();
        $result = $topic->top($ids);
        return $result;
    }

    /**
     * 帖子批量取消置顶
     */
    public function notop($ids)
    {
        $topic  = new TopicModel();
        $result = $topic->notop($ids);
        return $result;
    }

    /**
     * 帖子详情
     */
    public function details($id)
    {
        $topic = new TopicModel();
        $res   = $topic->details($id);
        return $res;
    }

    /**
     * 帖子回复详情
     */
    public function reply($id)
    {
        $topic = new TopicModel();
        $reply = $topic->reply($id);
        return $reply;
    }

    /**
     * 屏蔽弹层页面数据提交
     */
    public function details_layer_post($id, $is_ban)
    {
        $topic = new TopicModel();
        $que   = $topic->details_layer_post($id, $is_ban);  
        return $que;
    }

    /**
     * 详情页回复数据恢复提交
     */
    public function reply_shield($id)
    {
        $topic  = new TopicModel();
        $result = $topic->reply_shield($id);
        return $result;
    }

    /**
     * 问题屏蔽弹层页面数据提交
     */
    public function shield_layer_post($id, $is_ban)
    {
        $topic = new TopicModel();
        $que   = $topic->shield_layer_post($id, $is_ban);  
        return $que;
    }

    /**
     * 问题数据恢复提交
     */
    public function que_reply_shield($id)
    {
        $topic  = new TopicModel();
        $result = $topic->que_reply_shield($id);
        return $result;
    }
}