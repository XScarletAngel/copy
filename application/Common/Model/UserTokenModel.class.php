<?php
namespace Common\Model;

/**
 * 消息模版表
 * Class AdModel
 * @package Common\Model
 */
class UserTokenModel extends CommonModel
{
    //自动验证
    protected $_validate = array(
        //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
    );

    /**
     * 获取数据及分页
     * @param $where
     * @param $field
     * @return array
     */
    public function getAll($where,$field  = "*")
    {
        $count       = $this->field($field)->where($where)->count();
        $Page        = new \Think\Page($count,10);
        // //分页跳转的时候保证查询条件
        if (!empty($where)){
            foreach($where as $key=>$val) {
                $Page->parameter[$key]   =   urlencode($val);
            }
        }
        $show        = $Page->show();// 分页显示输出
        $notice      = $this->field($field)->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $data	  = array('data' => $notice, 'page' => $show);
        return $data;
    }

    //获得全部
    public function getUserTokens($where = [],$field = '')
    {
        $innerMsgTpls   = M('user_token');
        $result = $innerMsgTpls->field($field)->where($where)->select();
        return $result;
    }


    //更新数据
    public function update($where,$data = [])
    {
        if(empty($where)) return false;
        $innerMsgTpl  = M('user_token');
        $result = $innerMsgTpl->where($where)->data($data)->save();
        return $result;
    }

    /**
     * @param $data 需要添加的数据（数组）
     * @return ture false
     */
    public function addData($data)
    {
        $innerMsgTpl  = M('user_token');
        $result = $innerMsgTpl->data($data)->add();
        return $result;
    }

}