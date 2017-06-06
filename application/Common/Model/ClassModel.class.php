<?php
namespace Common\Model;

class ClassModel extends CommonModel
{
    protected $_validate = array(
        array('class_type',array(1,2,3),'班级类型不正确！',2,'in'),
        array('class_name','require','请输入班级名称！'),
        array('class_simple_name','require','请输入班级简名！'),
//        array('class_cover','require','请输入班级封面！'),
        array('class_master','require','请输入班主任！'),
        array('zone_id','require','请选择校区！'),
        array('course_id','require','请选择课程！'),
        array('class_no','require','请输入班级编号！'),
        array('answer_group','require','请选择答疑组成员！'),
        array('tch_id','require','请选择教师！'),
        array('tch_name','require','请输入教师！'),
//        array('room_id','require','请输入教室信息！'),
        array('room_name','require','请输入教室名！'),
        array('can_change',array(0,1),'教师权限状态的请选择！',2,'in'),
        array('class_status',array(1,2,3),'班级状态的范围不正确！',2,'in'),
    );

    //获得全部
    public function getClass($where = [],$field = '')
    {
        $class   = M('class');
        $result = $class->field($field)->where($where)->select();
        return $result;
    }


    //更新数据
    public function update($where,$data = [])
    {
        if(empty($where)) return false;
        $classRoom  = M('class');
        $result = $classRoom->where($where)->data($data)->save();
        return $result;
    }

    //筛选班级
    public function findClass($where = [])
    {
        $classRoom = M('class');// 实例化对象
        $count  = $classRoom->where($where)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        //分页跳转的时候保证查询条件
        foreach($where as $key=>$val) {
            $Page->parameter[$key]   =   urlencode($val);
        }
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $classRoom->where($where)->order('add_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
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
        $class  = M('class');
        $result = $class->data($data)->add();
        return $result;
    }

}