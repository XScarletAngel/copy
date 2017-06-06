<?php
namespace Common\Model;

class ClassScheduleModel extends CommonModel
{

    /**
     * User: maChuang
     * @param array $where
     * @return array
     * 分页取出数据
     */
    public function findClassScheduleByPage($where = [])
    {
        $cs = M('class_schedule');// 实例化对象
        $count  = $cs->where($where)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        //分页跳转的时候保证查询条件
        foreach($where as $key=>$val) {
            $Page->parameter[$key]   =   urlencode($val);
        }
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $cs->where($where)->order('add_time')->limit($Page->firstRow.','.$Page->listRows)->select();
//        echo $classRoom->getLastSql();
        $result=[
            'list'=>$list,
            'page'=>$show,
        ];

        return $result;
    }
    /**
     * @param $data 需要添加的数据（数组）
     * @return ture false
     */
    public function addData($data)
    {
        $schedule  = M('class_schedule');
        $result = $schedule->data($data)->add();
        return $result;
    }


    /**
     * @param $id  要删除的id
     * @return ture false
     */
    public function delete($id)
    {
        if(empty($id)) return false;
        return M('class_schedule')->delete($id);
    }

    /**
     * User: maChuang
     * @param array $where
     * @return mixed
     * 获得全部
     */
    public function getClassSchedule($where = [])
    {
        $schedule   = M('class_schedule');
        $result = $schedule->where($where)->select();
        return $result;
    }

    /**
     * User: maChuang
     * @param array $where
     * @return mixed
     * 更新数据
     */
    public function update($where,$data = [])
    {
        if(empty($where)) return false;
        $schedule  = M('class_schedule');
        $result = $schedule->where($where)->data($data)->save();
        return $result;
    }

}