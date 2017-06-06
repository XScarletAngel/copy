<?php
namespace Common\Model;

class PaperModel extends CommonModel
{
    protected $_validate = array(
        
    );

    //获得全部
    public function getPaper($where = [],$field = '')
    {
        $paper   = M('paper');
        $result = $paper->field($field)->where($where)->select();
        return $result;
    }

    //更新数据
    public function update($where,$data = [])
    {
        if(empty($where)) return false;
        $paper  = M('paper');
        $result = $paper->where($where)->data($data)->save();
        return $result;
    }

    //筛选班级
    public function findPaper($where = [])
    {
        $paper = M('paper');// 实例化对象
        $count  = $paper->where($where)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        //分页跳转的时候保证查询条件
        foreach($where as $key=>$val) {
            $Page->parameter[$key]   =   urlencode($val);
        }
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $paper->where($where)->order('add_time')->limit($Page->firstRow.','.$Page->listRows)->select();
//        echo $paper->getLastSql();
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
        $paper  = M('paper');
        $result = $paper->data($data)->add();
        return $result;
    }

}