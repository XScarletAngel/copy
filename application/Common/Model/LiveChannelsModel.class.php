<?php
namespace Common\Model;

/**
 * 班级服务设置
 * Class AdModel
 * @package Common\Model
 */
class LiveChannelsModel extends CommonModel
{
    //自动验证
    protected $_validate = array(
        //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
        array('channel_title', 'require', '直播间标题必填！', 1, 'regex', 3),
    );

    /**
     * 获取数据及分页
     * @param $where
     * @param $field
     * @return array
     */
    public function getAll($where, $field  = "*")
    {
        $count       = $this->field($field)->where($where)->count();
        $Page        = new \Think\Page($count,20);
        // //分页跳转的时候保证查询条件
        if (!empty($where)){
            foreach($where as $key=>$val) {
                $Page->parameter[$key]   =   urlencode($val);
            }
        }
        $show     = $Page->show();// 分页显示输出
        $channels = $this->field($field)->where($where)->order('begin_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $data	  = array('data' => $channels, 'page' => $show);
        return $data;
    }

    public function get($where = [], $field  = "*"){
        return $this->field($field)->where($where)->select();
    }

    /**
     * 添加记录
     * @param $addRow
     * @return int|mixed
     */
    public function addRow($addRow)
    {
        if (empty($addRow)) {
            return 0;
        }
        return $this->add($addRow);
    }

    /**
     * 修改记录:包含主键
     * @param $saveRow
     * @return int|mixed
     */
    public function saveRow($saveRow)
    {
        if (empty($saveRow)) {
            return 0;
        }
        return $this->save($saveRow);
    }

    /**
     * 返回单条记录
     *
     * @param $id
     * @return array
     */
    public function getRow($id)
    {
        if (empty($id)) {
            return [];
        }
        return $this->where(['id'=>$id])->find();
    }
}