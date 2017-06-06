<?php
namespace Common\Model;

class ClassRoomModel extends CommonModel
{


    //获得全部教室
    public function getAllClass($where = [])
    {
        $classRoom = M('class_room');// 实例化对象
        $list = $classRoom->where($where)->select();
        return $list;
    }

    //分页获得全部教室
    public function getClassRoom($where = [])
    {
        $classRoom = M('class_room');// 实例化对象


        $count  = $classRoom->where($where)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        //分页跳转的时候保证查询条件
        foreach($where as $key=>$val) {
            $Page->parameter[$key]   =   urlencode($val);
        }
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $classRoom->where($where)->order('id')->limit($Page->firstRow.','.$Page->listRows)->select();
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
    public function add_post($data = [])
    {
        $classRoom  = M('class_room');
        $result = $classRoom->data($data)->add();
        return $result;
    }
    public function update($data = [])
    {
        $classRoom  = M('class_room');
        $result = $classRoom->data($data)->save();
        return $result;
    }

    public function delete($where = [])
    {
        if(empty($where)) return false;
        $classRoom  = M('class_room');
        $result = $classRoom->where($where)->delete();
        return $result;
    }



}