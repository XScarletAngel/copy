<?php
/**
 * 院校列表
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\SchoolService;

class SchoolController extends AdminbaseController 
{

    /**
     * 元宵查询列表
     */
    public function index() 
    {   
        $where       = array();
        $code        = I('request.code');
        $school_name = I('request.school_name');
        $flag_211    = I('request.flag_211');
        $flag_985    = I('request.flag_985');
        $flag_score  = I('request.flag_score');
        $flag_master = I('request.flag_master');
        $area_id     = I('request.area_id');
        
        $where       = array('code' => $code, 'school_name' => $school_name, 'flag_211' => $flag_211, 'flag_985' => $flag_985, 'flag_score' => $flag_score, 'flag_master' => $flag_master, 'area_id' => $area_id);
    	$school      = new SchoolService;
    	$categorys   = $school->getAll($where);
    	// echo '<pre>';print_r($categorys);die;
    	$this->assign('page', $categorys['page']);  // 分页数据显示
        $this->assign('lists', $categorys['result']);  // 数据显示
        $this->assign('area', $categorys['area']);  // 分页数据显示
       	$this->display();
    }

    /**
     * 添加院校页面
     */
    public function add() 
    {   
        $school = new SchoolService;
        $area   = $school->getArea();
        $this->assign('area', $area);
        $this->display();
    }

    /**
     * 添加院校数据处理
     */
    public function add_post() 
    {   
        if (IS_POST){
            $school_name = I('post.school_name');
            $code        = I('post.code');
            $area_id     = I('post.area_id');
            $belong      = I('post.belong');       // 隶属机构
            $flag_985    = I('post.flag_985');
            $flag_211    = I('post.flag_211');
            $flag_master = I('post.flag_master');  // 是否是研究生院校
            $flag_score  = I('post.flag_score');   // 是否是自主划线院校

            if ($school_name != '' && $code != '' && $belong != ''){
                $data        = array('school_name' => $school_name, 'code' => $code, 'area_id' => $area_id, 'belong' => $belong, 'flag_985' => $flag_985, 'flag_211' => $flag_211, 'flag_master' => $flag_master, 'flag_score' => $flag_score);

                $school      = new SchoolService;
                $result      = $school->add_post($data);
                
                if ($result != false){
                    $this->success("添加成功", U('School/index'));
                } else {
                    $this->error("添加失败");
                }
            } else {
                $this->error("请填写完整数据，重新提交");
            }
        } else {
            $this->error("提交数据失败，请重新提交");
        }
    }

    /**
     * 院校编辑页面
     */
    public function edit() 
    {   
        $id     = I('get.id');
        $school = new SchoolService;
        $res    = $school->edit($id);
        $this->assign('data', $res['result']);   // 学校信息
        $this->assign('area', $res['area']);   // 地区信息
        $this->display();
    }

    /**
     * 编辑院校数据处理
     */
    public function edit_post() 
    {   
        if (IS_POST){
            $id          = I('post.id');
            $school_name = I('post.school_name');
            $code        = I('post.code');
            $area_id     = I('post.area_id');
            $belong      = I('post.belong');       // 隶属机构
            $flag_985    = I('post.flag_985');
            $flag_211    = I('post.flag_211');
            $flag_master = I('post.flag_master');  // 是否是研究生院校
            $flag_score  = I('post.flag_score');   // 是否是自主划线院校

            if ($school_name != '' && $code != '' && $belong != ''){
                $data        = array('id' => $id, 'school_name' => $school_name, 'code' => $code, 'area_id' => $area_id, 'belong' => $belong, 'flag_985' => $flag_985, 'flag_211' => $flag_211, 'flag_master' => $flag_master, 'flag_score' => $flag_score);

                $school      = new SchoolService;
                $result      = $school->edit_post($data);
                
                if ($result != false){
                    $this->success("编辑成功", U('School/index'));
                } else {
                    $this->error("编辑失败");
                }
            } else {
                $this->error("请填写完整数据，重新提交");
            }
        } else {
            $this->error("提交数据失败，请重新提交");
        }
    }


    /**
     * 删除有id的表数据
     */
    public function delete()
    {
        $id     = I('request.id');
        $del    = new SchoolService;
        $res    = $del->delete($id);
        if ($res) {
            $this->ajaxReturn(['msg'=>'删除成功～','code'=>1]);
        } else {
            $this->ajaxReturn(['msg'=>'删除失败～','code'=>2]);
        }
    }
}