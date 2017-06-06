<?php
namespace Common\Model;

class GoodsModel extends CommonModel
{
    /**
     * @var array
     * 数据验证
     */
    protected $_validate = array(
        array('class_id','require','缺少班级ID！'),
        array('class_no','require','缺少班级编号！'),
        array('class_name','require','请选择班级！'),
        array('sale_price','require','请输入售价！'),
        array('goods_cover','require','请选择商品封面图！'),
        array('goods_info','require','请输入班级详情！'),
        array('goods_name','require','请输入商品名！'),
    );

    /**
     * User: maChuang
     * @param array $where
     * @return mixed
     * 获得全部
     */
    public function getGoods($where = [],$field = '')
    {
        $goods   = M('goods');
        $result = $goods->field($field)->where($where)->select();
        return $result;
    }

    /**
     * User: maChuang
     * @param $where
     * @param array $data
     * @return bool
     * 更新数据
     */
    public function update($where,$data = [])
    {
        if(empty($where)) return false;
        $goods  =  M('goods');
        $result = $goods->where($where)->data($data)->save();
        $goods->getLastSql();
        return $result;
    }


    /**
     * User: maChuang
     * @param array $where
     * @return array
     * 筛选按照分页取出数据
     */
    public function findGoods($where = [])
    {
        $goods = M('goods');                                               // 实例化对象
        $count = $goods->where($where)->count();                           // 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);         // 实例化分页类 传入总记录数和每页显示的记录数(25)
        foreach($where as $key=>$val) {
            $Page->parameter[$key]   =   urlencode($val);
        }
        $show  = $Page->show();
        //整理查询条件
        $whereSql = "1=1 ";
        if(!empty($where['zone_id'])){
            $whereSql.=" and ".C('DB_PREFIX').'class.zone_id = '.$where['zone_id'];
        }
        if(!empty($where['sub_zone_id'])){
            $whereSql.=" and ".C('DB_PREFIX').'class.sub_zone_id = '.$where['sub_zone_id'];
        }
        if(!empty($where['shelf_status'])){
            $whereSql.=" and ".C('DB_PREFIX').'goods.shelf_status = '.$where['shelf_status'];
        }
        if(!empty($where['class_name'])){
            $whereSql.=" and ".C('DB_PREFIX').'class.class_name = '."'".$where['class_name']."'";
        }
        if(!empty($where['class_no'])){
            $whereSql.=" and ".C('DB_PREFIX').'class.class_no = '.$where['class_no'];
        }

        // 分页显示输出
        $list = $goods
            ->field(C('DB_PREFIX').'goods.*, '.C('DB_PREFIX').'class.class_name,'.C('DB_PREFIX').'class.class_cover,'.C('DB_PREFIX').'class.zone_id,'.C('DB_PREFIX').'class.sub_zone_id')
            ->join(' '.C('DB_PREFIX').'class on '.C('DB_PREFIX').'goods.class_id = '.C('DB_PREFIX').'class.id')
            ->where($whereSql)
            ->order(C('DB_PREFIX').'goods.'.'add_time')
            ->limit($Page->firstRow.','.$Page->listRows)
            ->select();
        foreach($list as $k=>$t){
            $list[$k]['zone_name'] = M('school_zone')->field('sz_name')->find($t['zone_id'])['sz_name'];
            $list[$k]['sub_zone_name'] = M('school_zone')->field('sz_name')->find($t['sub_zone_id'])['sz_name'];
        }
//        echo $goods->getLastSql(); //打印最近一条sql语句
        $result=[
            'list'=>$list,
            'page'=>$show,
        ];

        return $result;
    }

    /**
     * User: maChuang
     * @param $data 需要添加的数据（数组）
     * @return ture false
     */
    public function addData($data)
    {
        $goods  =  M('goods');
        $result = $goods->data($data)->add();
        return $result;
    }

}