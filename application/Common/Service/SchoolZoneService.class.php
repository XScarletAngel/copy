<?php
namespace Common\Service;

use Common\Model\SchoolZoneModel;

class SchoolZoneService 
{
	public $sz_model = null;

	public function __construct()
	{
		$this->sz_model = new SchoolZoneModel();
	}

	/**
	 * @param $where 条件(数组) 
	 * @param $field 字段
	 * @return array
	 */
	public function getall() 
	{
		$schoolzone = new SchoolZoneModel;
		$result     = $schoolzone->getall();
//        print_r($result);die;
		$tree = new \Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        
        $newmenus=array();
        foreach ($result as $m){
        	$newmenus[$m['id']]=$m;
        	 
        }
        foreach ($result as $n=> $r) {
        	// $result[$n]['type'] = '';
        	//$result[$n]['level'] = $this->_get_level($r['id'], $newmenus);
        	$result[$n]['parentid_node'] = ($r['parentid']) ? ' class="child-of-node-' . $r['parentid'] . '"' : '';
        	
        	$result[$n]['style'] = empty($r['parentid']) ? '' : 'display:none;';
        	// $result[$n]['major_type'] = 1 ? '' : '';

        	if ($result[$n]['sz_type'] == '1'){
        		$result[$n]['type'] = "总校";
        	} elseif ($result[$n]['sz_type'] == '2'){
        		$result[$n]['type'] = "分校";
        	} elseif ($result[$n]['sz_type'] == '3'){
        		$result[$n]['type'] = "校区";
        	} elseif ($result[$n]['sz_type'] == '4'){
                $result[$n]['type'] = "其他";
            }
        	
            $result[$n]['str_manage'] = '<a href="' . U("Schoolzone/add", array("parentid" => $r['id'], "sz_name" => $r['sz_name'], "sz_type" => $r['sz_type'])) . '">'.'添加子菜单'.'</a> | <a href="' . U("Schoolzone/edit", array("id" => $r['id'])) . '">'.L('EDIT').'</a> | <a href="javascript:void(0);" id-value="'.$r['id'].'" class="delete">'.L('DELETE').'</a>';
            // $result[$n]['status'] = $r['status'] ? L('DISPLAY') : L('HIDDEN');
            // if(APP_DEBUG){
            // 	$result[$n]['app']=$r['app']."/".$r['model']."/".$r['action'];
            // }
        }
//         echo '<pre>';print_r($result);die;
        $tree->init($result);
        $str = "<tr id='node-\$id' \$parentid_node style='\$style'>
        <td style='padding-left:20px;'></td>
        			<td>\$type</td>
					<td style='padding-left:20px;'>\$sz_code</td>
        			<td>\$sz_name</td>
					<td>\$sz_address</td>
				    <td>\$sz_master</td>
					<td>\$sz_phone</td>
					<td>\$str_manage</td>
				</tr>";
        $categorys = $tree->get_tree(0, $str);
//         echo '<pre>';print_r($categorys);die;
		return $categorys;
	}

	/**
	 * @param $data 添加的数据(数组)
	 * @return ture false
	 */
	public function add_post($data)
	{
		$schoolzone = new SchoolZoneModel;
		$result     = $schoolzone->add_post($data);
		return $result;
	}

	/**
	 * @param $id 修改的数据id
	 * @return array
	 */
	public function edit($id)
	{
		$schoolzone = new SchoolZoneModel;
		$result     = $schoolzone->edit($id);
		return $result;
	}

	/**
	 * @param $data 修改的data  array
	 * @return ture false
	 */
	public function edit_post($data)
	{
		$schoolzone = new SchoolZoneModel;
		$result     = $schoolzone->edit_post($data);
		return $result;
	}

	/**
     * @param $id 主键
     * @return ture false
     */
    public function delete($id)
    {
        $del    = new SchoolZoneModel;
        $result = $del->delete($id);
        return $result;
    }

	/**
	 * 通过父ID获取子元素
	 * @param int $pid
	 * @return array
	 */
	public function getSubZone($pid = 0)
	{
		return $this->sz_model->getSubZone($pid);
	}

	/*
	 * 获取多个校区
	 */
	public function getZoneByIds($ids)
	{
		if (empty($ids)) {
			return [];
		}
		$where['id'] = ['in',$ids];
		return $this->sz_model->where($where)->select();
	}

	/*
	 * 获取多个校区
	 */
	public function getZoneById($id)
	{
		if (empty($id)) {
			return [];
		}
		return $this->sz_model->where(['id'=>$id])->find();
	}

	public static function find($id)
    {
        if(empty($id)) return false;
        $zone   = M('school_zone');
        return $zone->find($id);
    }


}