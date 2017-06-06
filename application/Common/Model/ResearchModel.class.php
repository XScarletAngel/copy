<?php
namespace Common\Model;

use Common\Model\CommonModel;

class ResearchModel extends CommonModel
{

	/**
	 * @param $where 条件(数组) 
	 * @param $field 字段
	 * @return array
	 */
	public function getAll($where, $field) 
	{	
		$resear   = M('research');
		// 条件拼接
		$sql      = "1=1";
		if ($where['rch_code'] != ''){
			$sql .= " and rch_code like '%".$where['rch_code']."%'";
		}
		if ($where['rch_name'] != ''){
			$sql .= " and rch_name like '%".$where['rch_name']."%'";
		}
		if ($where['school_name'] != ''){
			$sql .= " and school_name like '%".$where['school_name']."%'";
		}
		if ($where['dept_name'] != ''){
			$sql .= " and dept_name like '%".$where['dept_name']."%'";
		}
		if ($where['major_name'] != ''){
			$sql .= " and major_name like '%".$where['major_name']."%'";
		}
		if ($where['rch_status'] != ''){
			$sql .= " and rch_status = ".$where['rch_status'];
		}
		$count    = $resear->where($sql.' and is_del = 1')->count();// 查询满足要求的总记录数
		$Page     = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		//分页跳转的时候保证查询条件
		if (!empty($where)){
			foreach($where as $key=>$val) {
			    $Page->parameter[$key]   =   urlencode($val);
			}
		}
		$show     = $Page->show();// 分页显示输出$page->show('Admin')
		$result   = $resear->field($field)->where($sql.' and is_del = 1')->limit($Page->firstRow.','.$Page->listRows)->select();
		$data	  = array('page' => $show, 'result' => $result, 'where' => $where);
		return $data;
	}

	/**
	 * @param $field 字段
	 * @return array
	 */
	public function getschool($field) 
	{
		$school = M('school');
		$result = $school->select();
		return $result;
	}

	/**
	 * @param $id 学校主键id
	 * @return array
	 */
	public function getdept($id) 
	{
		$dept   = M('school_dept');
		$result = $dept->where('school_id = '.$id)->select();
		return $result;
	}

	/**
	 * @param $id 院系主键id
	 * @return array
	 */
	public function getmajor($id) 
	{
		$major  = M('school_major');
		$result = $major->where('dept_id = '.$id.' and is_del = 1')->select();
		return $result;
	}

	/**
	 * @param $id 专业id
	 * @return array
	 */
	public function getresearch($id) 
	{
		$major  = M('research');
		$result = $major->where('major_id = '.$id.' and is_del = 1')->select();
		return $result;
	}

	/**
	 * @param $data 需要添加的数据（数组）
	 * @return ture false
	 */
	public function add_post($data) 
	{
		$area   = M('area');
		$area_r = $area->where('id = '.$data['area_code'])->find();
		$data['area_code'] = $area_r['code'];
		$res    = M('research');
		$result = $res->data($data)->add();
		return $result;
	}

	/**
	 * @param $id 方向主键id
	 * @param $field 查询的字段
	 * @return array
	 */
	public function edit($id, $field) 
	{
		$rch    = M('research');
		$result = $rch->field($field)->where('id = '.$id)->find();
		return $result;
	}

	/**
	 * @param $data 需要修改的数据
	 * @return ture false
	 */
	public function edit_post($data) 
	{
		$rch    = M('research');
		$result = $rch->where('id = '.$data['id'])->save($data);
		return $result;
	}

	/**
     * @param $id  要删除的id
     * @return ture false
     */
    public function delete($id)
    {
        $db     = M('research');
        $data   = array('is_del' => 2);
        $result = $db->where("id = ".$id)->save($data);
        return $result;
    }
}