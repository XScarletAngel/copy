<?php
namespace Common\Service;

use Common\Model\ClassModel;

class ClassService {



    /**
     * @return array
     * 分页获得搜索的数据
     */
    public static function findClass($where = [])
    {
        $classRoom  = new ClassModel();
        $result  = $classRoom->findClass($where);

        $zone = new SchoolZoneService();
        foreach($result['list'] as $key=>$temp)
        {
            $result['list'][$key]['zone_name']=$zone->getZoneById($temp['zone_id'])['sz_name'];
            $result['list'][$key]['sub_zone_name']=$zone->getZoneById($temp['sub_zone_id'])['sz_name'];
            $groupNames =   explode(',',$result['list'][$key]['answer_group']);
            $result['list'][$key]['answer_group_names'] = '';
//            echo "<pre>"; print_r();var_dump($groupNames, $result['list'][$key]['answer_group_names']);exit;
             if(!empty($groupNames))
             {
                 foreach ($groupNames as $id)
                 {
//                     $userInfo = UsersService::find($id);
                     $userInfo = (new TeacherService())->select(['user_id'=>$id]);
                     if(!empty($userInfo))
                     {
                         $userInfo = $userInfo[0];
                     $result['list'][$key]['answer_group_names'] = $result['list'][$key]['answer_group_names'] . '&nbsp;&nbsp;' . $userInfo['user_name'];
                     }
                 }
             }

//            echo "<pre>".$result['list'][$key]['answer_group_names']."<br>";
        }
        return $result;
    }

    public static function getAll($where = [],$field='*'){
        $classRoom  = new ClassModel();
        $result  = $classRoom->getClass($where,$field);
        return $result;
    }

    /**
     * @param $data 需要添加的数据（数组）
     * @return array
     */
    public function addData($data)
    {
        $course  = new ClassModel();
        $result = $course->addData($data);
        return $result;
    }

    /**
     * User: maChuang
     * @param $id
     * @return mixed
     * id查询
     */
    public static function find($id)
    {
        $class   = M('class');
        return $class->find($id);
    }

    /**
     * User: maChuang
     * @param $where
     * @param array $data
     * @return bool
     * 更新
     */
    public function update($where,$data=[])
    {
        if(empty($where)) return false;
        $class  = new ClassModel();
        $res = $class->update($where,$data);
        return $res;
    }

    /**
     * User: maChuang
     * @param array $where
     * @return mixed
     * 根据条件获取所有班级
     */
    public function getClasses($where = [],$field = '')
    {
        $class  = new ClassModel();
        $res = $class->getClass($where,$field);
        return $res;
    }

    /**
     * 通过关键词查询 未上课的班级
     * @param $kw
     * @param $field
     * @return  array
     */
    public function queryClassByKw($kw,$field ='id, class_name')
    {
        if (empty($kw)) {
            return [];
        }
        $class  = new ClassModel();
        $class_list = $class->field($field)->where("class_name like '%".$kw."%'")->select();
        return $class_list;
    }

}