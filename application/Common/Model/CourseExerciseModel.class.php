<?php
namespace Common\Model;

class CourseExerciseModel extends CommonModel
{
    protected $_validate = array(
        array('ce_no','require','请输入试题编号！'),
        array('ce_type','require','请输入题型！'),
        array('ce_title','require','请输入题干！'),
        array('options','require','请输入习题选项！'),
        array('answer','require','请输入答案,例如： A，AB！'),
        array('score','require','请输入分数！'),
//        array('course_id','require','请输入课程ID！'),
//        array('ce_skill','require','请输入技能标签！'),
//        array('ce_level','require','请输入难易程度！'),
//        array('ce_analyze','require','请输入题目的解析！'),
//        array('point_info','require','请输入知识点信息！'),
    );

    //获得全部
    public function getCourseExercise($where = [],$field = '')
    {
        $courseExercise   = M('course_exercise');
        $result = $courseExercise->field($field)->where($where)->select();
        return $result;
    }

    //更新数据
    public function update($where,$data = [])
    {
        if(empty($where)) return false;
        $courseExercise  = M('course_exercise');
        $result = $courseExercise->where($where)->data($data)->save();
        return $result;
    }

    //筛选班级
    public function findCourseExercise($where = [])
    {
        $courseExercise = M('course_exercise');// 实例化对象
        $count  = $courseExercise->where($where)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        //分页跳转的时候保证查询条件
        foreach($where as $key=>$val) {
            $Page->parameter[$key]   =   urlencode($val);
        }
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $courseExercise->where($where)->order('add_time')->limit($Page->firstRow.','.$Page->listRows)->select();
//        echo $courseExercise->getLastSql();
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
        $courseExercise  = M('course_exercise');
        $result = $courseExercise->data($data)->add();
        return $result;
    }

}