<?php
namespace Common\Service;

class MarketService extends BasicService
{
    public $mod_activiy = null;

    public function __construct()
    {
        parent::__construct();
        $this->mod_activiy = D("Common/MarketActivity");
    }

    /**
     * @param $zone_id 校区ID
     * @param $act_name 活动名称
     * @param $begin_time 开始时间
     * @param $end_time  结束时间
     * @param $act_status  活动状态
     * @return array
     */
	public function queryActivityByPage($zone_id, $act_name, $begin_time, $end_time, $act_status)
    {
        $where = ['is_del'=>1];

        if (!empty($zone_id) && $zone_id > 0) {
            $where = array_merge($where, ['zone_id'=>$zone_id]);
        }

        if (isset($act_name) && !empty($act_name)) {
            $where = array_merge($where, ['act_name'=>['like',"%$act_name%"]]);
        }

        if (isset($begin_time) && !empty($begin_time)) {
            $where = array_merge($where, ['act_begin_time'=>['EGT',$begin_time]]);
        }

        if (isset($end_time) && !empty($end_time)) {
            $where = array_merge($where, ['act_begin_time'=>['LT',$end_time]]);
        }

        if (isset($act_status) && !empty($act_status)) {
            $where = array_merge($where, ['act_status'=>$act_status]);
        }
        parent::setPageNum(10);

        return parent::queryByPage($this->mod_activiy, $where);
    }

    /**
     * 添加纪录
     * @param $row
     * @return int
     */
    public function addActivity($row)
    {
        if ($this->mod_activiy->create($row,1) !== false) {
            if ($this->mod_activiy->add() !== false) {
                return ['code'=>1,'msg'=>"添加成功!"];
            } else {
                return ['code'=>0,'msg'=>"添加失败!"];
            }
        } else {
            return ['code'=>0,'msg'=>$this->mod_activiy->getError()];
        }
    }

    /**
     * 修改纪录
     * @param $row
     * @return int
     */
    public function editActivity($row)
    {
        if ($this->mod_activiy->create($row, 0) !== false) {
            if ($this->mod_activiy->save() !== false) {
                return ['code'=>1,'msg'=>"修改成功!"];
            } else {
                return ['code'=>0,'msg'=>"修改失败!"];
            }
        } else {
            return ['code'=>0,'msg'=>$this->mod_activiy->getError()];
        }
    }

    /**
     * 获取单个活动
     * @param $id
     * @return array|mixed
     */
    public function queryActById($id)
    {
        if (empty($id)) {
            return [];
        }
        return $this->mod_activiy->where(['id'=>$id])->find();
    }

    /**
     * 获取单个活动
     * @param $id
     * @return array|mixed
     */
    public function deleteAct($id)
    {
        if (empty($id)) {
            return [];
        }
        return $this->mod_activiy->where(['id'=>$id])->save(['is_del'=>2]);
    }
}