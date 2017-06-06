<?php
namespace Common\Service;

use Common\Model\MarketConsultModel;

class ConsultService 
{
	/**
	 * @param $ 条件(数组)
	 * @return array
	 */
	public function index($sc_no, $phone, $stu_name, $deal_status, $channel, $start_time, $end_time)
	{
        $sql       = "1=1";
        if($sc_no != ''){
            $sql  .= " and sc_no = '".trim($sc_no)."'";
        }
        if($phone != ''){
            $sql  .= " and phone = '".trim($phone)."'";
        }
        if($stu_name != ''){
            $sql  .= " and stu_name = '".trim($stu_name)."'";
        }
        if($deal_status == 1){
            $sql  .= " and deal_status = 1";
        }
        if($deal_status == 2){
            $sql  .= " and deal_status != 1";
        }
        if($channel != ''){
            $sql  .= " and channel = ".$channel;
        }
        if($start_time != ''){
            $sql .= " and add_time >= '".$start_time."'";
        }
        if($end_time != ''){
            $sql .= " and add_time <= '".$end_time."'";
        }
        $where     = array('sc_no' => $sc_no, 'phone' => $phone, 'stu_name' => $stu_name, 'deal_status' => $deal_status, 'channel' => $channel, 'start_time' => $start_time, 'end_time' => $end_time);
		$consult   = new MarketConsultModel();
		$data      = $consult->index($sql, $where);
		return $data;
	}

	/*
     * 添加咨询单弹层页面获取数据
     */
    public function index_layer(){
        $consult = new MarketConsultModel();
        $data    = $consult->index_layer();
        return $data;
    }

    /*
     * 添加咨询单弹层页面数据提交
     */
    public function index_layer_post($data){
        $consult = new MarketConsultModel();
        $result  = $consult->index_layer_post($data);
        return $result;
    }

    /*
     * 处理咨询单弹层页面
     */
    public function handle_layer($id){
        $consult = new MarketConsultModel();
        $result  = $consult->handle_layer($id);
        return $result;
    }

    /*
     * 处理咨询单弹层页面数据提交
     */
    public function handle_layer_post($data){
        $consult = new MarketConsultModel();
        $result  = $consult->handle_layer_post($data);
        return $result;
    }

    /**
     * @param $ 条件(数组)
     * @return array
     */
    public function deal($sc_no, $phone, $stu_name, $deal_status, $channel, $start_time, $end_time)
    {
        $sql       = "1=1";
        if($sc_no != ''){
            $sql  .= " and sc_no = '".$sc_no."'";
        }
        if($phone != ''){
            $sql  .= " and phone = '".$phone."'";
        }
        if($stu_name != ''){
            $sql  .= " and stu_name = '".$stu_name."'";
        }
        if($deal_status == 1){
            $sql  .= " and deal_status = 1";
        }
        if($deal_status == 2){
            $sql  .= " and deal_status != 1";
        }
        if($channel != ''){
            $sql  .= " and channel = ".$channel;
        }
        if($start_time != ''){
            $sql .= " and add_time >= '".$start_time."'";
        }
        if($end_time != ''){
            $sql .= " and add_time <= '".$end_time."'";
        }
        $where     = array('sc_no' => $sc_no, 'phone' => $phone, 'stu_name' => $stu_name, 'deal_status' => $deal_status, 'channel' => $channel, 'start_time' => $start_time, 'end_time' => $end_time);
        $consult   = new MarketConsultModel();
        $data      = $consult->deal($sql, $where);
        return $data;
    }

    /*
     * 获取未处理咨询单的数量
     */
    public function GetCount(){
        $consult = new MarketConsultModel();
        $result  = $consult->GetCount();
        return $result;
    }


}