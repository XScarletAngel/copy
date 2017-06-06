<?php
namespace Common\Model;

use Common\Model\CommonModel;

class ClassNoticeModel extends CommonModel
{

	/**
	 * @param $where 条件(数组) 
	 * @param $field 字段
	 * @return array
	 */
	public function getAll($sql, $where) 
	{
		$classnotice = M('class_notice');
		$count       = $classnotice->alias('c')->field('c.*, u.user_nicename')->join(C('DB_PREFIX').'users as u on c.user_id = u.id')->where($sql)->count();
		$Page        = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		// //分页跳转的时候保证查询条件
		if (!empty($where)){
			foreach($where as $key=>$val) {
			    $Page->parameter[$key]   =   urlencode($val);
			}
		}
		$show        = $Page->show();// 分页显示输出
		$notice      = $classnotice->alias('c')->field('c.*, u.user_nicename')->join(C('DB_PREFIX').'users as u on c.user_id = u.id')->where($sql)->order('add_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$data	  = array('notice' => $notice, 'page' => $show);
		return $data;
	}

	/**
     * 获取校区
     */
    public function zone() 
    {   
     	$szone  = M('school_zone');
		$school = $szone->field('id, sz_name, serial')->where("sz_type = 1 or sz_type = 2")->select();  // 查询校区
     	return $school;

    }

    /**
     * 发布公告数据处理添加
     */
    public function add_post($data) 
    {   
     	$class  = M('class_notice');
		$result = $class->data($data)->add();  // 查询校区
     	return $result;

    }

	/**
     * 分校查询
     */
    public function getZone($id) 
    {   
     	$zone = M('school_zone');     // 校区id
        // 查询传过来的数据是总校还是分校
        $arr  = $zone->where(['id' => $id])->find();
        if($arr['sz_type'] == 1){
            $data = '';
        } else {
            $data = $zone->where("parentid = %d", $id)->select();
        }
     	return $data;
    }

    /**
     * 班级查询
     */
    public function getClass($id) 
    {   
     	$zone = M('class');     // 校区id
     	$data = $zone->where("sub_zone_id = %d", $id)->select();
     	return $data;

    }

    /**
     * @param id 公告id
     * @param status 要修改成的状态
     * @return 返回的数组
     */
    public function status($id, $status)
    {
        $notice = M('class_notice');
        $result = $notice->where("id = %d", $id)->save(['status' => $status]);
        return $result;
    }

}