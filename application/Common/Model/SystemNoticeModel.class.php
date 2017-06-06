<?php
namespace Common\Model;

/**
 * 班级服务设置
 * Class AdModel
 * @package Common\Model
 */
class SystemNoticeModel extends CommonModel
{
    //自动验证
    protected $_validate = array(
        //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
        array('content', 'require', '公告内容不能为空！', 1, 'regex', 3),
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

}