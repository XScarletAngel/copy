<?php
namespace Common\Model;

class TeacherModel extends CommonModel
{
    protected $_validate = array(
        array('user_id','require','缺少用户ID！'),
        array('user_login','require','请输入手机号！'),
        array('user_name','require','请输入姓名！'),
        array('tch_no','require','请输入编号！'),
        array('sign_date','require','请选择签约日期！'),
        array('sign_status','require','请选择签约状态！'),
        array('school','require','请输入读研学校！'),
        array('school_dept','require','请输入读研院系！'),
        array('school_major','require','请输入读研专业！'),
        array('school_research','require','请输入读研方向！'),
        array('add_time','require','请输入创建时间！'),
    );

    //筛选班级
    public function getDataByPage($where = [])
    {
        $tc = M('teacher');                      // 实例化对象
        $count  = $tc->where($where)->count();   // 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        //分页跳转的时候保证查询条件
        foreach($where as $key=>$val) {
            $Page->parameter[$key]   =   urlencode($val);
        }
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $tc->where($where)->order('add_time')->limit($Page->firstRow.','.$Page->listRows)->select();
//        echo $classRoom->getLastSql();
        $result=[
            'list'=>$list,
            'page'=>$show,
        ];

        return $result;
    }

    //获得全部
    public function getClass()
    {
        $tc   = M('teacher');
        $result = $tc->select();
        return $result;
    }

    //更新数据
    public function update($where,$data = [])
    {
        if(empty($where)) return false;
        $tcRoom  = M('teacher');
        $result = $tcRoom->where($where)->data($data)->save();
        return $result;
    }

    /**
     * @param $data 需要添加的数据（数组）
     * @return ture false
     */
    public function addData($data)
    {
        $tc  = M('teacher');
        $result = $tc->data($data)->add();
        return $result;
    }

}