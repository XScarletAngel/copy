<?php
namespace Common\Model;

class InnerMsgModel extends CommonModel
{
    protected $_validate = array();

    //获得全部
    public function getinnerMsg($where = [],$field = '')
    {
        $innerMsg   = M('inner_msg');
        $result = $innerMsg->field($field)->where($where)->select();
        return $result;
    }

    //更新数据
    public function update($where,$data = [])
    {
        if(empty($where)) return false;
        $innerMsg  = M('inner_msg');
        $result = $innerMsg->where($where)->data($data)->save();
        return $result;
    }

    //筛选班级
    public function findinnerMsg($where = [])
    {
        $innerMsg = M('inner_msg');// 实例化对象
        $count  = $innerMsg->where($where)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        //分页跳转的时候保证查询条件
        foreach($where as $key=>$val) {
            $Page->parameter[$key]   =   urlencode($val);
        }
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $innerMsg->where($where)->order('add_time')->limit($Page->firstRow.','.$Page->listRows)->select();
//        echo $innerMsg->getLastSql();
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
        $innerMsg  = M('inner_msg');
        $result = $innerMsg->data($data)->add();
        return $result;
    }

}