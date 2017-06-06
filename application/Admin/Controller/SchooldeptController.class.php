<?php
/**
 * 院校列表
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\SchoolDeptService;

class SchooldeptController extends AdminbaseController 
{

    /**
     * 元宵查询列表
     */
    public function index() 
    {   
        $where       = array();
        $dept_code   = I('request.dept_code');
        $school_name = I('request.school_name');
        $dept_name   = I('request.dept_name');
        $dept_status = I('request.dept_status');
        
        $where       = array('dept_code' => $dept_code, 'school_name' => $school_name, 'dept_name' => $dept_name, 'dept_status' => $dept_status);
    	$school      = new SchoolDeptService;
    	$categorys   = $school->getAll($where);
        // echo "<pre>";print_r($categorys['result']);die;
    	$this->assign('page', $categorys['page']);  // 分页数据显示
        $this->assign('lists', $categorys['result']);  // 数据显示
        // $this->assign('where', $categorys['where']);  // 地区数据显示
       	$this->display();
    }

    /**
     * 添加院校页面
     */
    public function add() 
    {   
        $school = new SchoolDeptService;
        $school   = $school->getschool();
        $this->assign('school', $school);
        $this->display();
    }

    /**
     * 添加院校数据处理
     */
    public function add_post() 
    {   
        if (IS_POST){
            $dept_name       = I('post.dept_name');    // 院系名称
            $dept_code       = I('post.dept_code');    // 院系编号
            $school_id       = I('post.school_id');    // 所属院校id
            $school_code     = I('post.school_code');  // 院校编码
            $dept_status     = I('post.dept_status');  // 状态

            if ($dept_name != '' && $dept_code != ''){
                $data        = array('dept_name' => $dept_name, 'dept_code' => $dept_code, 'school_id' => $school_id, 'school_code' => $school_code, 'dept_status' => $dept_status);

                $school      = new SchoolDeptService;
                $result      = $school->add_post($data);
                
                if ($result != false){
                    $this->success("添加成功", U('SchoolDept/index'));
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
        $school = new SchoolDeptService;
        $res    = $school->edit($id);
        $this->assign('school', $res['school']);   // 学校信息
        $this->assign('dept', $res['dept']);   // 地区信息
        $this->display();
    }

    /**
     * 编辑院校数据处理
     */
    public function edit_post() 
    {   
        if (IS_POST){
            $id              = I('post.id');    // 院系名称
            $dept_name       = I('post.dept_name');    // 院系名称
            $dept_code       = I('post.dept_code');    // 院系编号
            $school_id       = I('post.school_id');    // 所属院校id
            $school_code     = I('post.school_code');  // 院校编码
            $dept_status     = I('post.dept_status');  // 状态

            if ($dept_name != '' && $dept_code != ''){
                $data        = array('dept_name' => $dept_name, 'dept_code' => $dept_code, 'school_id' => $school_id, 'school_code' => $school_code, 'dept_status' => $dept_status, 'id' => $id);
                
                $school      = new SchoolDeptService;
                $result      = $school->edit_post($data);
                
                if ($result != false){
                    $this->success("编辑成功", U('SchoolDept/index'));
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
        $del    = new SchoolDeptService;
        $res    = $del->delete($id);
        if ($res) {
            $this->ajaxReturn(['msg'=>'删除成功～','code'=>1]);
        } else {
            $this->ajaxReturn(['msg'=>'删除失败～','code'=>2]);
        }
    }
}