<?php
namespace Common\Model;

use Common\Model\CommonModel;

class ClassBookReceiveModel extends CommonModel
{

	/**
	 * @param $where 条件(数组) 
	 * @param $field 字段
	 * @return array
	 */
	public function getall($sql, $where)
	{
		$receive = M("class_book_receive");
		$count   = $receive->where($sql)->count();
		$Page    = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		// //分页跳转的时候保证查询条件
		if (!empty($where)){
			foreach($where as $key=>$val) {
			    $Page->parameter[$key]   =   urlencode($val);
			}
		}
		$show    = $Page->show();// 分页显示输出
		$data    = $receive->where($sql)->limit($Page->firstRow.','.$Page->listRows)->select();
		$result  = array('data' => $data, 'page' => $show);
		return $result;
	}

	/**
	 * @param $ids 条件
	 * @return ture false
	 */
	public function receive($ids)
	{
		$receive = M("class_book_receive");
		$arr     = explode(',', $ids);
		$time    = date('Y-m-d H:i:s', time());
		$data = array('receive_status'=>'2','receive_time'=>$time);
        for($i=0;$i<count($arr);$i++){
            $result = $receive->where(' id = '.$arr[$i])->setField($data);
        }
        return $result;
	}

	/**
	 * @param $ids 条件
	 * @return ture false
	 */
	public function noreceive($ids)
	{
		$receive = M("class_book_receive");
		$arr     = explode(',', $ids);
		$time    = date('0-0-0 0:0:0');
		$data = array('receive_status'=>'1','receive_time'=>$time);
        for($i=0;$i<count($arr);$i++){
            $result = $receive->where(' id = '.$arr[$i])->setField($data);
        }
        return $result;
	}

	/**
	 * @param $id 条件
	 * @param $status 条件
	 * @return ture false
	 */
	public function singlereceive($id, $status)
	{
		$receive = M("class_book_receive");
		if($status == 1){
			$time   = date('Y-m-d H:i:s', time());
			$data   = array('receive_status'=>'2','receive_time'=>$time);
			$result = $receive->where(' id = '.$id)->setField($data);
		} elseif ($status == 2){
			$time    = date('0-0-0 0:0:0');
			$data = array('receive_status'=>'1','receive_time'=>$time);
			$result = $receive->where(' id = '.$id)->setField($data);
		}
        return $result;
	}
}