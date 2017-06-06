<?php
namespace Common\Model;

class SchoolDeptModel extends CommonModel
{

	/**
	 * @param $where 条件(数组) 
	 * @param $field 字段
	 * @return array
	 */
	public function getAll($where) 
	{	
		$scdept   = M('school_dept');
		// 条件拼接
		$sql      = "1=1";
		if ($where['dept_code'] != ''){
			$sql .= " and dept_code like '%".$where['dept_code']."%'";
		}
		if ($where['school_name'] != ''){
			$sql .= " and school_name like '%".$where['school_name']."%'";
		}
		if ($where['dept_name'] != ''){
			$sql .= " and dept_name like '%".$where['dept_name']."%'";
		}
		if ($where['dept_status'] != ''){
			$sql .= " and dept_status = ".$where['dept_status'];
		}
		$count    = $scdept->alias('d')->join(C('DB_PREFIX').'school as s on d.school_id = s.id')->where($sql.' and d.is_del = 1')->count();// 查询满足要求的总记录数
		$Page     = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		//分页跳转的时候保证查询条件
		if (!empty($where)){
			foreach($where as $key=>$val) {
			    $Page->parameter[$key]   =   urlencode($val);
			}
		}
		$show     = $Page->show();// 分页显示输出$page->show('Admin')
		$result   = $scdept->alias('d')->field("s.school_name, d.id, d.dept_name, d.dept_code, d.dept_status")->join(C('DB_PREFIX').'school as s on d.school_id = s.id')->where($sql.' and d.is_del = 1')->limit($Page->firstRow.','.$Page->listRows)->select();
		$data	  = array('page' => $show, 'result' => $result, 'where' => $where);
		return $data;
	}

	/**
     * @return array
     */
    public function getschool()
    {
        $school   = M('school');
        $result = $school->select();
        return $result;
    }

    /**
     * @param $data 数组
     * @return 返回的id
     */
    public function add_post($data)
    {
        $school   = M('school_dept');
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
        $dept     = M('school_dept');
        $dept     = $dept->alias('d')->field("s.school_name, d.id, d.school_id, d.dept_name, d.school_code, d.dept_code, d.dept_status")->join(C('DB_PREFIX').'school as s on d.school_id = s.id')->where('d.id = '.$id)->find(); // 要修改的院系的信息
        $school   = $school->select();  // 查询的学校信息
        $res      = array('dept' => $dept, 'school' => $school);
        return $res;
    }

    /**
     * @param $data 数组
     * @return 返回ture false
     */
    public function edit_post($data)
    {
        $school   = M('school_dept');
        $dept     = $school->where('id = '.$data['id'])->find();
        if ($dept['school_id'] == $data['school_id']     && 
        	$dept['school_name'] == $data['school_name'] && 
        	$dept['school_code'] == $data['school_code'] && 
        	$dept['dept_code'] == $data['dept_code']     && 
        	$dept['dept_status'] == $data['dept_status']
        ){
        	$result = ture;
        } else {
        	$result   = $school->where('id = '.$data['id'])->save($data);
        }
        return $result;
    }

    /**
     * @param $id  要删除的id
     * @return ture false
     */
    public function delete($id)
    {
        $db     = M('school_dept');
        $data   = array('is_del' => 2);
        $result = $db->where("id = ".$id)->save($data);
        return $result;
    }
}