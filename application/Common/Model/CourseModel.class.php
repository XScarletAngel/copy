<?php
namespace Common\Model;

class CourseModel extends CommonModel
{


    //获得全部课程
    public function getCourse()
    {
        $area   = M('course');
        $result = $area->select();
        return $result;
    }

    /*
     * 课程列表删除课程
     */
    public function delcourse($id)
    {
        $course  = M("course");
        $data    = array('is_del' => '1');
        $result  = $course->where("id = ".$id)->save($data);
        if($result){
            $result = array('code' => 1, 'msg' => '删除成功');
            return $result;
        } else {
            $result = array('code' => 0, 'msg' => '删除失败，请重新修改');
            return $result;
        }
    }

    /*
     * 课程状态修改
     */
    public function status($id, $status)
    {
        $course  = M("course");
        $data    = array('course_status' => $status);
        $result  = $course->where("id = ".$id)->save($data);
        if($result){
            $result = array('code' => 1, 'msg' => '修改成功');
            return $result;
        } else {
            $result = array('code' => 0, 'msg' => '修改失败，请重新修改');
            return $result;
        }
    }

    /**
     * @param $data 需要添加的数据（数组）
     * @return ture false
     */
    public function add_post($data)
    {
        $course  = M('course');
        $result = $course->data($data)->add();
        return $result;
    }

    /**
     * @param $sql 条件  $where（数组）
     * @return 
     */
    public function getall($sql, $where)
    {
        $area   = M('course');
        $count  = $area->where($sql." and is_del = 0")->count();
        $Page   = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        // //分页跳转的时候保证查询条件
        if (!empty($where)){
            foreach($where as $key=>$val) {
                $Page->parameter[$key]   =   urlencode($val);
            }
        }
        $show   = $Page->show();// 分页显示输出
        $result = $area->where($sql." and is_del = 0")->limit($Page->firstRow.','.$Page->listRows)->select();
        $res    = array('result' => $result, 'page' => $show);
        return $res;
    }

    /*
     * 课程详情页面
     */
    public function courseinfo($id)
    {
        $course   = M("course");
        $baseinfo = $course->where("id = ".$id)->find();
        return $baseinfo;
    }

    /**
     * @param $id 主键
     * @return ture false
     */
    public function addcourse($id)
    {
        $course = M("course");
        $result = $course->where("id = ".$id)->find();
        return $result;
    }

    /**
     * @return $data  数组
     * 添加课程数据处理
     */
    public function basic_post($data)
    {
        $course = M('course');
        if(empty($data['id'])){
            $result = $course->data($data)->add();
            return $result;
        } else {
            $result = $course->where("id = ".$data['id'])->save($data);
            if($result){
                return $data['id'];
            } else {
                return false;
            }
        }
    }

    /*
     * 知识点大纲页面
     */
    public function outline($id)
    {
        $course = M('course_catalog');
        $result = $course->field("id, parentid, course_id, catalog_name, is_exam_point, tag, is_look")->where("course_id = ".$id)->select();
        return $result;
    }

    /*
     * 知识点大纲弹出页面数据导入
     */
    public function outline_layer_post($data, $id)
    {
        $course  = M("course");
        $plan    = M("course_tech_plan");
        $catalog = M("course_catalog");
        $cata    = M();
        $cou_arr = $course->where(["id" => $id])->find();
        $coudata = array('parentid' => 0, 'course_id' => $id, 'catalog_name' => $cou_arr['course_name'], 'cur_path' => 0);
        $planarr = $plan->where("course_id = ".$id)->select();     // 大纲节点上面的课件
        $cataarr = $catalog->where("course_id = ".$id)->select();  // 大纲节点数据
        if(empty($planarr)){  // 如果大纲节点上面有课件，不允许删除更新
            foreach($data as $k=>$v){
                $insert[$k]['cur_path']      = $data[$k]['0'];
                $insert[$k]['level']         = count(explode('.', $data[$k]['0']));
                $insert[$k]['catalog_name']  = $data[$k]['1'];
                $insert[$k]['is_exam_point'] = $data[$k]['2'];
                $insert[$k]['tag'] = $data[$k]['3'];
                $insert[$k]['course_id']     = $id;
            }
            if(!empty($cataarr)){  // 如果catalog表里面有数据，先进行删除
                $re = $catalog->where("course_id = ".$id)->delete();
                $course_res = $catalog->data($coudata)->add();
                foreach($insert as $k=>$v){
                if($insert[$k]['level'] > 1){
                    $num = strripos($insert[$k]['cur_path'], '.');
                    $insert[$k]['p_path'] = substr($insert[$k]['cur_path'], 0, $num);
                } else {
                    $insert[$k]['p_path'] = 0;
                }
                unset($insert[$k]['level']);
                $result = $catalog->data($insert[$k])->add();
                }
                $res = $cata->execute("update ".C('DB_PREFIX')."course_catalog as cc left join ".C('DB_PREFIX')."course_catalog as cc2 on cc.p_path=cc2.cur_path set cc.parentid=cc2.id where cc.p_path != '' and cc2.course_id = ".$id." and cc.course_id = ".$id);
                return $res;
            } else {
                $course_res = $catalog->data($coudata)->add();
                foreach($insert as $k=>$v){
                    if($insert[$k]['level'] > 1){
                        $num = strripos($insert[$k]['cur_path'], '.');
                        $insert[$k]['p_path'] = substr($insert[$k]['cur_path'], 0, $num);
                    } else {
                        $insert[$k]['p_path'] = 0;
                    }
                    unset($insert[$k]['level']);
                    $result = $catalog->data($insert[$k])->add();
                }
                $res = $cata->execute("update ".C('DB_PREFIX')."course_catalog as cc left join ".C('DB_PREFIX')."course_catalog as cc2 on cc.p_path=cc2.cur_path set cc.parentid=cc2.id where cc.p_path != '' and cc2.course_id = ".$id." and cc.course_id = ".$id);
                return $res;
            }
        } else {
            return false;
        }
        
        // echo "<pre>";print_r($insert);die;
        return $res;
    }

    /*
     * 知识点大纲添加子节点弹层页面
     */
    public function node_layer_post($data)
    {
        $course = M("course_catalog");
        foreach($data['tag'] as $k=>$v){
            $str .= $data['tag'][$k]."|";
        }
        $data['tag'] = rtrim($str, '|');
        $result = $course->data($data)->add();
        return $result;
    }

    /*
     * 知识点大纲编辑节点弹层页面
     */
    public function edit_layer($cid)
    {
        $course = M("course_catalog");
        $data   = $course->where("id = ".$cid)->find();
        return $data;
    }

    /*
     * 知识点大纲编辑节点弹层页面数据提交
     */
    public function edit_layer_post($data)
    {
        $course = M("course_catalog");
        foreach($data['tag'] as $k=>$v){
            $str .= $data['tag'][$k]."|";
        }
        $data['tag'] = rtrim($str, '|');
        $result = $course->where("id = ".$data['id'])->save($data);
        return $result;
    }

    /*
     * 知识点大纲设置试看
     */
    public function look($id, $val){
        $course = M("course_catalog");
        $data   = array('is_look' => $val);
        $result = $course->where("id = ".$id)->save($data);
        return $result;
    }

    /*
     * 教案页面
     */
    public function teachplan($id)
    {
        $course = M('course_catalog');
        $plan   = M('course_tech_plan');
        $result = $course->field("id, parentid, course_id, catalog_name, is_exam_point")->where("course_id = ".$id)->select();
        foreach($result as $k=>$v){
            $result[$k]['plan'] = $plan->where("tech_type = 1 and catalog_id = ".$result[$k]['id'])->select();
        }
        return $result;
    }

    /*
     * 教案页面数据删除
     */
    public function del_plan($id)
    {
        $plan   = M('course_tech_plan');
        $map    = M("catalog_tech_map");

        // $plan->startTrans();   // 启用事务

        $res    = $plan->where("id = ".$id)->delete();
        $result = $map->where("tech_plan_id = ".$id)->delete();
        if($res){
            // $plan->commit();   //只有$resuser 和  $resstu  都执行成功是才真正执行上面的数据库操作
            $result = array('code' => "1", 'msg' => "删除成功");
            return $result;
        }else{
            // $plan->rollback();  //  条件不满足，回滚
            $result = array('code' => "0", 'msg' => "删除失败");
            return $result;
        }
    }

    /*
     * 教案弹层页面数据数据提交
     */
    public function teachplan_layer_post($data, $file)
    {
        $plan   = M("course_tech_plan");
        $course = M("course_catalog");
        $map    = M("catalog_tech_map");

        $plan->startTrans();   // 启用事务

        $course_arr = $course->where("id = ".$data['catalogid'])->find();
        $file['catalog_id'] = $course_arr['id'];
        $file['catalog_name'] = $course_arr['catalog_name'];
        $file['course_id'] = $course_arr['course_id'];

        $res_plan = $plan->data($file)->add();

        for($i=0;$i<count($data['point']);$i++){
            $course_cat_arr = $course->where("id = ".$data['point'][$i])->find();
            $arr[$i]['tech_plan_id'] = $res_plan;
            $arr[$i]['cata_id'] = $course_cat_arr['id'];
            $arr[$i]['cata_name'] = $course_cat_arr['catalog_name'];
            $arr[$i]['position'] = $data['page'][$i];
            $arr[$i]['file_type'] = '1';
        }
        foreach($arr as $k=>$v){
            $res_map = $map->data($arr[$k])->add();
        }
        if($res_plan && $res_map){
            $plan->commit();   //只有$resuser 和  $resstu  都执行成功是才真正执行上面的数据库操作
            return true;
        }else{
            $plan->rollback();  //  条件不满足，回滚
            return false;
        }
    }

    /*
     * 教案文件弹层页面（修改）数据提交
     */
    public function teachplan_file_layer_post($data)
    {
        $plan = M("course_tech_plan");
        $course = M("course_catalog");
        $map  = M("catalog_tech_map");
        $res  = $map->where("tech_plan_id = ".$data['planid'])->delete();
        $planarr = $plan->where("id = ".$data['planid'])->find();
        for($i=0;$i<count($data['point']);$i++){
            $course_cat_arr = $course->where("id = ".$data['point'][$i])->find();
            $arr[$i]['tech_plan_id'] = $planarr['id'];
            $arr[$i]['cata_id'] = $course_cat_arr['id'];
            $arr[$i]['cata_name'] = $course_cat_arr['catalog_name'];
            $arr[$i]['position'] = $data['page'][$i];
            $arr[$i]['file_type'] = '1';
        }
        foreach($arr as $k=>$v){
            $res_map = $map->data($arr[$k])->add();
        }
        return $res_map;
    }

    /*
     * 图文课件页面
     */
    public function ware($id)
    {
        $course = M('course_catalog');
        $plan   = M('course_tech_plan');
        $result = $course->field("id, parentid, course_id, catalog_name, is_exam_point")->where("course_id = ".$id)->select();
        foreach($result as $k=>$v){
            $result[$k]['plan'] = $plan->where("tech_type = 2 and catalog_id = ".$result[$k]['id'])->select();
        }
        return $result;
    }

    /*
     * 图文课件弹层页面数据数据提交
     */
    public function ware_layer_post($data, $file)
    {
        $plan   = M("course_tech_plan");
        $course = M("course_catalog");
        $map    = M("catalog_tech_map");

        $plan->startTrans();   // 启用事务

        $course_arr = $course->where("id = ".$data['catalogid'])->find();
        $file['catalog_id'] = $course_arr['id'];
        $file['catalog_name'] = $course_arr['catalog_name'];
        $file['course_id'] = $course_arr['course_id'];

        $res_plan = $plan->data($file)->add();

        for($i=0;$i<count($data['point']);$i++){
            $course_cat_arr = $course->where("id = ".$data['point'][$i])->find();
            $arr[$i]['tech_plan_id'] = $res_plan;
            $arr[$i]['cata_id'] = $course_cat_arr['id'];
            $arr[$i]['cata_name'] = $course_cat_arr['catalog_name'];
            $arr[$i]['position'] = $data['page'][$i];
            $arr[$i]['file_type'] = '1';
        }
        foreach($arr as $k=>$v){
            $res_map = $map->data($arr[$k])->add();
        }
        if($res_plan && $res_map){
            $plan->commit();   //只有$resuser 和  $resstu  都执行成功是才真正执行上面的数据库操作
            return true;
        }else{
            $plan->rollback();  //  条件不满足，回滚
            return false;
        }
    }

    /*
     * 图文课件弹层页面（修改）数据提交
     */
    public function ware_file_layer_post($data)
    {
        $plan = M("course_tech_plan");
        $course = M("course_catalog");
        $map  = M("catalog_tech_map");
        $res  = $map->where("tech_plan_id = ".$data['planid'])->delete();
        $planarr = $plan->where("id = ".$data['planid'])->find();
        for($i=0;$i<count($data['point']);$i++){
            $course_cat_arr = $course->where("id = ".$data['point'][$i])->find();
            $arr[$i]['tech_plan_id'] = $planarr['id'];
            $arr[$i]['cata_id'] = $course_cat_arr['id'];
            $arr[$i]['cata_name'] = $course_cat_arr['catalog_name'];
            $arr[$i]['position'] = $data['page'][$i];
            $arr[$i]['file_type'] = '1';
        }
        foreach($arr as $k=>$v){
            $res_map = $map->data($arr[$k])->add();
        }
        return $res_map;
    }

    /*
     * 视频课件页面
     */
    public function video($id)
    {
        $course = M('course_catalog');
        $plan   = M('course_tech_plan');
        $result = $course->field("id, parentid, course_id, catalog_name, is_exam_point")->where("course_id = ".$id)->select();
        foreach($result as $k=>$v){
            $result[$k]['plan'] = $plan->where("tech_type = 3 and catalog_id = ".$result[$k]['id'])->select();
        }
        return $result;
    }

    /*
     * 视频课件弹层页面数据数据提交
     */
    public function video_layer_post($data, $file)
    {
        $plan   = M("course_tech_plan");
        $course = M("course_catalog");
        $map    = M("catalog_tech_map");

        $plan->startTrans();   // 启用事务

        $course_arr = $course->where("id = ".$data['catalogid'])->find();
        $file['catalog_id'] = $course_arr['id'];
        $file['catalog_name'] = $course_arr['catalog_name'];
        $file['course_id'] = $course_arr['course_id'];

        $res_plan = $plan->data($file)->add();

        for($i=0;$i<count($data['point']);$i++){
            $course_cat_arr = $course->where("id = ".$data['point'][$i])->find();
            $arr[$i]['tech_plan_id'] = $res_plan;
            $arr[$i]['cata_id'] = $course_cat_arr['id'];
            $arr[$i]['cata_name'] = $course_cat_arr['catalog_name'];
            $arr[$i]['position'] = $data['page'][$i];
            $arr[$i]['file_type'] = '2';
        }
        foreach($arr as $k=>$v){
            $res_map = $map->data($arr[$k])->add();
        }
        if($res_plan && $res_map){
            $plan->commit();   //只有$resuser 和  $resstu  都执行成功是才真正执行上面的数据库操作
            return true;
        }else{
            $plan->rollback();  //  条件不满足，回滚
            return false;
        }
    }

    /*
     * 视频课件弹层页面（修改）数据提交
     */
    public function video_file_layer_post($data)
    {
        $plan = M("course_tech_plan");
        $course = M("course_catalog");
        $map  = M("catalog_tech_map");
        $res  = $map->where("tech_plan_id = ".$data['planid'])->delete();
        $planarr = $plan->where("id = ".$data['planid'])->find();
        for($i=0;$i<count($data['point']);$i++){
            $course_cat_arr = $course->where("id = ".$data['point'][$i])->find();
            $arr[$i]['tech_plan_id'] = $planarr['id'];
            $arr[$i]['cata_id'] = $course_cat_arr['id'];
            $arr[$i]['cata_name'] = $course_cat_arr['catalog_name'];
            $arr[$i]['position'] = $data['page'][$i];
            $arr[$i]['file_type'] = '1';
        }
        foreach($arr as $k=>$v){
            $res_map = $map->data($arr[$k])->add();
        }
        return $res_map;
    }

    /*
     * 习题库页面
     */
    public function exercises($id, $sql)
    {
        $course   = M('course_exercise');

        $exercise = $course->where($sql." and course_id = ".$id)->select();
        foreach($exercise as $k=>$v){
            $exercise[$k]['options'] = explode('#$#', $exercise[$k]['options']);
        }
        return $exercise;
    }

    //分页获得全部教室
    public function getExercisesByPage($where = [])
    {
        $course = M('course_exercise');// 实例化对象
        $count  =$course->where($where)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        //分页跳转的时候保证查询条件
        foreach($where as $key=>$val) {
            $Page->parameter[$key]   =   urlencode($val);
        }
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $course->where($where)->order('id')->limit($Page->firstRow.','.$Page->listRows)->select();
        $result=[
            'list'=>$list,
            'page'=>$show,
        ];
        return $result;
    }

    /*
     *  设计试卷页面
     */
    public function paper($id)
    {
        $paper = M("paper");
        $data  = $paper->where("course_id = ".$id)->select();
        return $data;
    }

    /*
     *  设计试卷页面删除数据
     */
    public function del_paper($id)
    {
        $paper  = M("paper");
        $pa_ex  = M("paper_exercise");   // 试卷与习题的关系表
        $exerci = M("course_exercise");  // 课程习题表
        $pa_res = $paper->where("id = ".$id)->delete();       // 删除试卷表信息
        $data   = $pa_ex->where("paper_id = ".$id)->select(); // 查找关系表里试题的ID
        if(!empty($data)){
            foreach($data as $k=>$v){
                $ids .= $data[$k]['ce_id'];
            }
            $exercise_res = $exerci->where("id in(".$ids.")")->delete(); // 删除试题表里的信息
        }
        $ex_res = $pa_ex->where("paper_id = ".$id)->delete();  // 删除关系表里的数据
        if($pa_res){
            $result = array('code' => 1, 'msg' => '删除成功');
            return $result;
        } else {
            $result = array('code' => 0, 'msg' => '删除失败，请重新删除');
            return $result;
        }
    }

    /*
     * 设计试卷弹层页面(修改试卷信息)
     */
    public function edit_paper_layer($id)
    {
        $paper = M("paper");
        $data  = $paper->where("id = ".$id)->find();
        return $data;
    }

    /*
     * 设计试卷弹层页面(修改试卷信息数据提交)
     */
    public function edit_paper_layer_post($data)
    {
        $paper = M("paper");
        $res    = $paper->where("id = ".$data['id'])->save($data);
        return $res;
    }

    /*
     * 设计试卷弹层页面数据提交
     */
    public function paper_layer_post($data)
    {
        $course = M("paper");
        $data['is_online'] = 1;
        $result = $course->data($data)->add();
        
        return $result;
    }

    /*
     * 试卷分配页面
     */
    public function distribution($id)
    {
        $course = M('course_catalog');
        $plan   = M('course_tech_plan');
        $result = $course->field("id, parentid, course_id, catalog_name, is_exam_point")->where("course_id = ".$id)->select();
        foreach($result as $k=>$v){
            $result[$k]['plan'] = $plan->where("tech_type = 4 and catalog_id = ".$result[$k]['id'])->select();
        }
        return $result;
    }

    /*
     * 试卷分配页面弹层获取数据
     */
    public function distribution_layer($courseid)
    {
        $paper = M("paper");
        $data  = $paper->where("is_online = 1 and course_id = ".$courseid)->select();
        return $data;
    }

    /*
     * 试卷分配弹层页面数据提交
     */
    public function distribution_layer_post($data)
    {
        $catalog     = M("course_catalog");
        $paper       = M("paper");
        $plan        = M("course_tech_plan");

        $catalog_arr = $catalog->where("id = ".$data['catalogid'])->find();
        $paper_arr   = $paper->where("id = ".$data['paper_id'])->find();
        $arr         = array('catalog_id' => $catalog_arr['id'], 'catalog_name' => $catalog_arr['catalog_name'], 'course_id' => $catalog_arr['course_id'], 'file_name' => $paper_arr['paper_title'], 'tech_type' => 4, 'paper_id' => $paper_arr['id']);
        $result      = $plan->data($arr)->add();
        return $result;
    }



}