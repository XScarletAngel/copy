<?php
namespace Common\Model;

use Common\Model\CommonModel;

class SchoolModel extends CommonModel
{

	/**
	 * @param $where 条件(数组) 
	 * @param $field 字段
	 * @return array
	 */
	public function getAll($where) 
	{
		$school   = M('school');
		$area     = M('area');
		$area_ar  = $area->select();
		// 条件拼接
		$sql      = "1=1";
		if (!empty($where['code'])){
			$sql .= " and s.code like '%".$where['code']."%'";
		}
		if (!empty($where['school_name'])){
			$sql .= " and school_name like '%".$where['school_name']."%'";
		}
		if (!empty($where['flag_211'])){
			$sql .= " and flag_211 = ".$where['flag_211'];
		}
		if (!empty($where['flag_985'])){
			$sql .= " and flag_985 = ".$where['flag_985'];
		}
		if (!empty($where['flag_score'])){
			$sql .= " and flag_score = ".$where['flag_score'];
		}
		if (!empty($where['flag_master'])){
			$sql .= " and flag_master = ".$where['flag_master'];
		}
		if (!empty($where['area_id'])){
			$sql .= " and ".C('DB_PREFIX')."school.area_id = ".$where['area_id'];
		}
		$count    = $school->alias('s')->join(C('DB_PREFIX').'area as a on s.area_id = a.id')->where($sql.' and is_del = 1')->count();// 查询满足要求的总记录数
		$Page     = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		//分页跳转的时候保证查询条件
		if (!empty($where)){
			foreach($where as $key=>$val) {
			    $Page->parameter[$key]   =   urlencode($val);
			}
		}
		$show     = $Page->show();// 分页显示输出$page->show('Admin')
		$result   = $school->alias('s')->field("a.area, s.id, s.school_name, s.belong, s.flag_985, s.flag_211, s.flag_master, s.flag_score, s.code, s.school_url")->join(C('DB_PREFIX').'area as a on s.area_id = a.id')->where($sql.' and is_del = 1')->limit($Page->firstRow.','.$Page->listRows)->select();
		$data	  = array('page' => $show, 'result' => $result, 'area' => $area_ar, 'where' => $where);
		return $data;
	}

	/**
     * @return array
     */
    public function getArea()
    {
        $area   = M('area');
        $result = $area->select();
        return $result;
    }

    /**
     * @param $data 数组
     * @return 返回的id
     */
    public function add_post($data)
    {
        $school   = M('school');
        $result   = $school->data($data)->add();
        return $result;
    }

    /**
     * @param  id
     * @return 返回的数组
     */
    public function edit($id)
    {
        $school   = M('school');
        $area     = M('area');
        $area     = $area->select();
        $result   = $school->where('id = '.$id)->find();
        $res      = array('area' => $area, 'result' => $result);
        return $res;
    }

    /**
     * @param $data 数组
     * @return 返回ture false
     */
    public function edit_post($data)
    {
        $school   = M('school');
        $result   = $school->where('id = '.$data['id'])->save($data);;
        return $result;
    }

    /**
	 * @param $id  要删除的id
	 * @return ture false
	 */
	public function delete($id)
	{
		$db     = M('school');
		$data   = array('is_del' => 2);
		$result = $db->where("id = ".$id)->save($data);
		return $result;
	}

	public function getSchools($where = [])
    {
        $db     = M('school');
        $where['is_del'] = 1;
        $result = $db->where($where)->select();
        return $result;
    }
}