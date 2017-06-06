<?php
namespace Common\Service;

use Common\Model\CourseModel;

class CourseService {



    /**
     * @return array`
     */
    public function getCourse()
    {
        $course  = new CourseModel();
        $result  = $course->getCourse();
        return $result;
    }

    /*
     * 课程详情页面
     */
    public function courseinfo($id)
    {
        $course = new CourseModel();
        $data   = $course->courseinfo($id);
        return $data;
    }

    /*
     * 课程列表删除课程
     */
    public function delcourse($id)
    {
        $course  = new CourseModel();
        $result  = $course->delcourse($id);
        return $result;
    }

    /*
     * 课程状态修改
     */
    public function status($id, $status)
    {
        $course  = new CourseModel();
        $result  = $course->status($id, $status);
        return $result;
    }

    /**
     * @param $data 需要添加的数据（数组）
     * @return array
     */
    public function add_post($data)
    {
        $course  = new CourseModel();
        $result = $course->add_post($data);
        return $result;
    }


    /**
     * @param $id 主键
     * @return ture false
     */
    public function delete($id)
    {
        $del    = new CourseModel;
        $result = $del->delete($id);
        return $result;
    }

    /**
     * @return $字段名
     */
    public function getall($course_no, $course_name, $subject_type, $course_version, $course_status)
    {
        $sql      = "1=1";
        if($course_no != ''){
            $sql .= " and course_no = ".$course_no;
        }
        if($course_name != ''){
            $sql .= " and course_name = ".$course_name;
        }
        if($subject_type != ''){
            $sql .= " and subject_type = ".$subject_type;
        }
        if($course_version != ''){
            $sql .= " and course_version = ".$course_version;
        }
        if($course_status != ''){
            $sql .= " and course_status = ".$course_status;
        }
        $where    = array('course_no' => $course_no, 'course_name' => $course_name, 'subject_type' => $subject_type, 'course_version' => $course_version, 'course_status' => $course_status);
        $course   = new CourseModel();
        $result   = $course->getall($sql, $where);
        return $result;
    }

    /**
     * @param $id 主键
     * @return ture false
     */
    public function addcourse($id)
    {
        $course = new CourseModel;
        $result = $course->addcourse($id);
        return $result;
    }

    /**
     * @return $data  数组
     * 添加课程数据处理
     */
    public function basic_post($data)
    {
        $course   = new CourseModel();
        $result   = $course->basic_post($data);
        return $result;
    }

    /*
     * 知识点大纲页面
     */
    public function outline($id)
    {
        $course = new CourseModel();
        $data = $course->outline($id);
//        echo "<pre>";print_r($data);die;
        $categorys = $this->noLimitCate($data, $parentid=0, $level=0);
//        echo "<pre>";print_r($categorys);die;
        return $categorys;
    }

    /*
     * 知识点大纲弹出页面数据导入
     */
    public function outline_layer_post($data, $id)
    {
        $course = new CourseModel();
        $result = $course->outline_layer_post($data, $id);
        
        return $result;
    }

    /*
     * 知识点大纲添加子节点弹层页面
     */
    public function node_layer_post($data)
    {
        $course = new CourseModel();
        $result = $course->node_layer_post($data);
        return $result;
    }

    /*
     * 知识点大纲编辑节点弹层页面
     */
    public function edit_layer($cid)
    {
        $course = new CourseModel();
        $data   = $course->edit_layer($cid);
        return $data;
    }

    /*
     * 知识点大纲编辑节点弹层页面数据提交
     */
    public function edit_layer_post($data)
    {
        $course = new CourseModel();
        $result = $course->edit_layer_post($data);
        return $result;
    }

    /*
     * 知识点大纲设置试看
     */
    public function look($id, $val){
        $course = new CourseModel();
        $result = $course->look($id, $val);
        return $result;
    }

    /*
     * 教案页面
     */
    public function teachplan($id)
    {
        $course    = new CourseModel();
        $data      = $course->teachplan($id);
        $categorys = $this->noLimitCate($data, $parentid=0, $level=0);
        
        return $categorys;
    }

    /*
     * 教案页面数据删除
     */
    public function del_plan($id)
    {
        $course = new CourseModel();
        $res    = $course->del_plan($id);
        return $res;
    }

    /*
     * 无限极分类
     */
    public function noLimitCate($data, $parentid=0, $level=0){
        $str = "";
        for($i=0;$i<=$level;$i++){
            $str .= "&nbsp;&nbsp;&nbsp;&nbsp;";
        }
        static $list1=array();
        foreach ($data as $key => $v) {
            if($v['parentid']==$parentid){
                $v['level']=$level;
                $v['catalog_name'] = $str.$v['catalog_name'];
                $list1[]=$v;
                $this->noLimitCate($data, $v['id'], $level+1);
            }
        }
        return $list1;
    }


    /*
     * 教案弹层页面获取数据
     */
//    public function get_teachplan_layer($id)
//    {
//        $course = new CourseModel();
//        $data = $course->get_teachplan_layer($id);
//        return $data;
//    }

    /*
     * 教案弹层页面数据数据提交
     */
    public function teachplan_layer_post($data, $file)
    {
        $course = new CourseModel();
        $result = $course->teachplan_layer_post($data, $file);
        
        return $result;
    }

    /*
     * 教案文件弹层页面（修改）
     */
    public function teachplan_file_layer($id, $course_id, $adds = "")
    {
        global $str;
        $number = 1;
        //$icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $icon = array('&nbsp;&nbsp;&nbsp;', '&nbsp;&nbsp;&nbsp;', '&nbsp;&nbsp;&nbsp;');
        $nbsp = "&nbsp;&nbsp;&nbsp;";

        $child = M('course_catalog')->where(["parentid"=>$id,'course_id'=>$course_id])->select();
        $total = count($child);
        if ($child) {
            foreach ($child as $k=>$row) {
                $j = $k = '';
                if ($number == $total) {
                    $j .= $icon[2];
                } else {
                    $j .= $icon[1];
                }
                $spacer = $adds ? $adds . $j : '';

                $str .= "<option value='".$row['id']."'>" .$spacer .$row['catalog_name'] . "</option>";

                $this->get_ware_layer($row['id'], $course_id, $adds . $j . $nbsp);

                $number++;
            }
        }
        return $str;
    }

    /*
     * 教案文件弹层页面（修改）数据提交
     */
    public function teachplan_file_layer_post($data)
    {
        $course = new CourseModel();
        $result = $course->teachplan_file_layer_post($data);
        return $result;
    }

    /*
     * 图文课件
     */
    public function ware($id)
    {
        $course    = new CourseModel();
        $data      = $course->ware($id);
        $categorys = $this->noLimitCate($data, $parentid=0, $level=0);
        
        return $categorys;
    }

    /*
     * 图文课件弹层页面获取数据
     */
    public function get_ware_layer($id, $course_id, $adds = "")
    {
        global $str;
        $number = 1;
        //$icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $icon = array('&nbsp;&nbsp;&nbsp;', '&nbsp;&nbsp;&nbsp;', '&nbsp;&nbsp;&nbsp;');
        $nbsp = "&nbsp;&nbsp;&nbsp;";

        $child = M('course_catalog')->where(["parentid"=>$id,'course_id'=>$course_id])->select();
        $total = count($child);
        if ($child) {
            foreach ($child as $k=>$row) {
                $j = $k = '';
                if ($number == $total) {
                    $j .= $icon[2];
                } else {
                    $j .= $icon[1];
                }
                $spacer = $adds ? $adds . $j : '';

                $str .= "<option value='".$row['id']."'>" .$spacer .$row['catalog_name'] . "</option>";

                $this->get_ware_layer($row['id'], $course_id, $adds . $j . $nbsp);

                $number++;
            }
        }
        return $str;
    }

    /*
     * 图文课件弹层页面数据数据提交
     */
    public function ware_layer_post($data, $file)
    {
        $course = new CourseModel();
        $result = $course->ware_layer_post($data, $file);
        
        return $result;
    }

    /*
     * 图文课件弹层页面（修改）
     */
    public function ware_file_layer($id)
    {
        $course = new CourseModel();
        $data   = $course->ware_file_layer($id);
        return $data;
    }

    /*
     * 图文课件弹层页面（修改）数据提交
     */
    public function ware_file_layer_post($data)
    {
        $course = new CourseModel();
        $result = $course->ware_file_layer_post($data);
        return $result;
    }

    /*
     * 视频课件页面
     */
    public function video($id)
    {
        $course    = new CourseModel();
        $data      = $course->video($id);
        $categorys = $this->noLimitCate($data, $parentid=0, $level=0);
        
        return $categorys;
    }

    /*
     * 视频课件弹层页面获取数据
     */
    public function get_video_layer($id)
    {
        $course = new CourseModel();
        $data = $course->get_video_layer($id);
        return $data;
    }

    /*
     * 视频课件弹层页面数据数据提交
     */
    public function video_layer_post($data, $file)
    {
        $course = new CourseModel();
        $result = $course->video_layer_post($data, $file);
        
        return $result;
    }

    /*
     * 视频课件弹层页面（修改）
     */
    public function video_file_layer($id)
    {
        $course = new CourseModel();
        $data   = $course->video_file_layer($id);
        return $data;
    }

    /*
     * 视频课件弹层页面（修改）数据提交
     */
    public function video_file_layer_post($data)
    {
        $course = new CourseModel();
        $result = $course->video_file_layer_post($data);
        return $result;
    }

    /*
     * 习题库页面
     */
    public function exercises($id, $ce_type, $ce_skill, $ce_level, $point_info)
    {
        $sql = "1=1";
        if($ce_type != ''){
            $sql .= " and ce_type = ".$ce_type;
        }
        if($ce_skill != ''){
            $sql .= " and ce_skill = ".$ce_skill;
        }
        if($ce_level != ''){
            $sql .= " and ce_level = ".$ce_level;
        }
        if($point_info != ''){
            $sql .= " and point_info like '%".$point_info."%'";
        }
        $course = new CourseModel();
        $data   = $course->exercises($id, $sql);
        
        return $data;
    }

    /**
     * User: maChuang
     * @param array $where
     * @param string $course_id
     * @return array
     * 分页获取习题数据
     */
    public function getExercisesByPage($where = [])
    {
        $course = new CourseModel();
        $data   = $course->getExercisesByPage($where);
        return $data;
    }

    /*
     *  设计试卷页面
     */
    public function paper($id)
    {
        $course = new CourseModel();
        $data   = $course->paper($id);
        return $data;
    }

    /*
     *  设计试卷页面删除数据
     */
    public function del_paper($id)
    {
        $course = new CourseModel();
        $res    = $course->del_paper($id);
        return $res;
    }

    /*
     * 设计试卷弹层页面(修改试卷信息)
     */
    public function edit_paper_layer($id)
    {
        $course = new CourseModel();
        $data   = $course->edit_paper_layer($id);
        return $data;
    }

    /*
     * 设计试卷弹层页面(修改试卷信息数据提交)
     */
    public function edit_paper_layer_post($data)
    {
        $course = new CourseModel();
        $res    = $course->edit_paper_layer_post($data);
        return $res;
    }

    /*
     * 设计试卷弹层页面数据提交
     */
    public function paper_layer_post($data)
    {
        $course = new CourseModel();
        $result = $course->paper_layer_post($data);
        
        return $result;
    }

    /*
     * 试卷分配页面
     */
    public function distribution($id)
    {
        $course    = new CourseModel();
        $data      = $course->distribution($id);
        $categorys = $this->noLimitCate($data, $parentid=0, $level=0);
        
        return $categorys;
    }

    /*
     * 试卷分配页面弹层
     */
    public function distribution_layer($courseid)
    {
        $course = new CourseModel();
        $data   = $course->distribution_layer($courseid);
        return $data;
    }

    /*
     * 试卷分配弹层页面数据提交
     */
    public function distribution_layer_post($data)
    {
        $course = new CourseModel();
        $result = $course->distribution_layer_post($data);
        
        return $result;
    }

}