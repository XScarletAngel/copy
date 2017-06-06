<?php
namespace Common\Service;


use Common\Model\ClassScheduleModel;

class ScheduleService {



    //分页获得全部课表
    public function getScheduleByPage($where = [])
    {
        $sc = M('class_schedule');// 实例化对象
        $count  = $sc->where($where)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        //分页跳转的时候保证查询条件
        foreach($where as $key=>$val) {
            $Page->parameter[$key]   =   urlencode($val);
        }
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $sc->where($where)->order('id')->limit($Page->firstRow.','.$Page->listRows)->select();
        $result=[
            'list'=>$list,
            'page'=>$show,
        ];
        return $result;
    }
    /**
     * @param $data 需要添加的数据（数组）
     * @return array
     */
    public static function  addData($data)
    {
        $schedule  = new ClassScheduleModel();
        $result = $schedule->addData($data);
        return $result;
    }

    /**
     * @param $data 搜索
     * @return array
     */
    public static function  select($where = [],$field='')
    {
        $schedule  = M('class_schedule');
        $result = $schedule->field($field)->where($where)->select();
        return $result;
    }

    /**
     * @param $data 搜索
     * @return array
     */
    public static function  getGroup($where=[],$group = 'class_id')
    {
        $schedule  = M('class_schedule');
        $result = $schedule->where($where)->group($group)->select();
        return $result;
    }



}