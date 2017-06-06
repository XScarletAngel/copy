<?php
namespace Common\Service;

use Common\Model\MajorModel;

class MajorService {


	public function select() 
	{
		$major = new MajorModel();
		$result   = $major->getAll();

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

        	if ($result[$n]['level'] == '1'){
        		$result[$n]['type'] = "门类";
        	} elseif ($result[$n]['level'] == '2'){
        		$result[$n]['type'] = "学科";
        	} elseif ($result[$n]['level'] == '3'){
        		if($result[$n]['major_type'] == '1'){
        			$result[$n]['type'] = "学术型硕士";
        		} else {
        			$result[$n]['type'] = "专业型硕士";
        		}
        	}
        	
            $result[$n]['str_manage'] = '<a href="' . U("Major/add", array("parentid" => $r['id'], "major_name" => $r['major_name'])) . '">'.'添加子菜单'.'</a> | <a href="' . U("Major/edit", array("id" => $r['id'])) . '">'.L('EDIT').'</a> | <a href="javascript:void(0);" id-value="'.$r['id'].'" class="delete">'.L('DELETE').'</a>';
            // $result[$n]['status'] = $r['status'] ? L('DISPLAY') : L('HIDDEN');
            // if(APP_DEBUG){'. U("Del/delete", array("id" => $r['id'], "dbname" => 'major')) . '
            // 	$result[$n]['app']=$r['app']."/".$r['model']."/".$r['action'];
            // }
        }
        // echo '<pre>';print_r($result);die;
        $tree->init($result);
        $str = "<tr id='node-\$id' \$parentid_node style='\$style'>
        <td style='padding-left:20px;'></td>
					<td style='padding-left:20px;'>\$id</td>
        			<td>\$major_name</td>
					<td>\$type</td>
				    <td>\$major_code</td>
					<td>\$str_manage</td>
				</tr>";
        $categorys = $tree->get_tree(0, $str);
        // echo '<pre>';print_r($categorys);die;
		return $categorys;
	}

    /**
     * @param $post 数组名称
     * @return 返回的插入的id值
     */
    public function add_post($post)
    {
        $major = new MajorModel();
        if (!empty($post)){
            $result = $major->add($post);
        }
        return $result;
    }

    /**
     * @param $id id值
     * @return 返回array
     */
    public function edit($id)
    {
        $major = new MajorModel();
        $result = $major->edit($id);
        return $result;
    }

    /**
     * @param $id id值
     * @param $post array
     * @return 返回ture false
     */
    public function edit_post($id, $post)
    {
        $major = new MajorModel();
        $result = $major->edit_post($id, $post);
        return $result;
    }

    /**
     * @param $id 主键
     * @return ture false
     */
    public function delete($id)
    {
        $del    = new MajorModel;
        $result = $del->delete($id);
        return $result;
    }
}