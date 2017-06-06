<?php
/**
 * 院校列表
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\ResearchService;

class ResearchController extends AdminbaseController 
{

    /**
     * 方向查询列表
     */
    public function index() 
    {   
        $where       = array();
        $rch_code    = I('request.rch_code');     // 方向code
        $rch_name    = I('request.rch_name');     // 方向名称
        $school_name = I('request.school_name');  // 所属院校名称
        $dept_name   = I('request.dept_name');    // 所属院系名称
        $major_name  = I('request.major_name');   // 所属专业名称
        $rch_status  = I('request.rch_status');   // 方向状态
        
        $where       = array('rch_code' => $rch_code, 'rch_name' => $rch_name, 'school_name' => $school_name, 'dept_name' => $dept_name, 'major_name' => $major_name, 'rch_status' => $rch_status);
    	$research    = new ResearchService;
    	$categorys   = $research->getAll($where);
        // echo "<pre>";print_r($categorys['result']);die;
    	$this->assign('page', $categorys['page']);  // 分页数据显示
        $this->assign('lists', $categorys['result']);  // 数据显示
        $this->assign('where', $categorys['where']);  // 数据显示
       	$this->display();
    }

    /**
     * 方向查询列表
     */
    public function add()
    {
        // 查询学校信息
        $research = new ResearchService;
        $school   = $research->getschool();
        $this->assign('school', $school);
        $this->display();
    }

    /**
     * 查询院校下属所有院系
     */
    public function getdept()
    {
        $id       = I('request.school_id');     // 学校id
        $research = new ResearchService;
        $dept     = $research->getdept($id);
        //echo "<pre>";print_r($dept);die;
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($dept));
        $this->ajaxReturn(array_values($dept));
    }

    /**
     * 查询某一院系下属所有专业
     */
    public function getmajor()
    {
        $id       = I('request.dept_id');     // 院系id
        $research = new ResearchService;
        $major     = $research->getmajor($id);
        //echo "<pre>";print_r($dept);die;
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($major));
        $this->ajaxReturn(array_values($major));
    }

    /**
     * 查询某一专业下属所有方向
     */
    public function getresearch()
    {
        $id       = I('request.major_id');     // 专业id
        $research = new ResearchService;
        $res      = $research->getresearch($id);
        //echo "<pre>";print_r($dept);die;
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($res));
        $this->ajaxReturn(array_values($res));
    }

    /**
     * 方向查询列表
     */
    public function add_post()
    {   
        if (IS_POST){
            $school_id   = I('post.school_id');     // 学校id
            $school_name = I('post.school_name');   // 学校name
            $school_code = I('post.school_code');   // 学校code
            $area_code   = I('post.area_code');     // 学校学校归属地id
            $dept_id     = I('post.dept_id');       // 院系id
            $dept_name   = I('post.dept_name');     // 院系name
            $dept_code   = I('post.dept_code');     // 院系code
            $major_id    = I('post.major_id');      // 专业id
            $major_name  = I('post.major_name');    // 专业name
            $major_code  = I('post.major_code');    // 专业code
            $rch_name    = I('post.rch_name');      // 方向名称
            $rch_code    = I('post.rch_code');      // 方向code
            $rch_status  = I('post.rch_status');    // 状态

            if ($major_name != '' && $major_code != '' && $rch_name != '' && $rch_code != ''){
                $data        = array('school_id' => $school_id , 'school_name' => $school_name , 'school_code' => $school_code , 'area_code' => $area_code , 'dept_id' => $dept_id , 'dept_name' => $dept_name , 'dept_code' => $dept_code , 'major_id' => $major_id , 'major_name' => $major_name , 'major_code' => $major_code , 'rch_name' => $rch_name , 'rch_code' => $rch_code , 'rch_status' => $rch_status);
                $research = new ResearchService;
                $res   = $research->add_post($data);
                if ($res != false){
                    $this->success("添加成功", U('Research/index'));
                } else {
                    $this->error("添加失败");
                }
            } else {
                $this->error("请填写完整数据");
            }
        } else {
            $this->error("提交数据失败，请重新添加");
        }
    }

    /**
     * 修改方向数据
     */
    public function edit()
    {
        $id       = I('request.id');     // 方向id
        $research = new ResearchService;
        $data     = $research->edit($id);
        // echo "<pre>";print_r($data);die;
        $this->assign('data', $data);
        $this->display();
    }

    /**
     * 修改方向数据
     */
    public function edit_post()
    {
        if (IS_POST){
            $id         = I('request.id');     // 方向id
            $rch_name   = I('request.rch_name');     // 方向name
            $rch_code   = I('request.rch_code');     // 方向code
            $rch_status = I('request.rch_status');     // 方向状态

            if ($rch_name != '' && $rch_code != ''){
                $data   = array('id' => $id, 'rch_name' => $rch_name, 'rch_code' => $rch_code, 'rch_status' => $rch_status);
                $rch    = new ResearchService;
                $res    = $rch->edit_post($data);
                if ($res != false){
                    $this->success("修改成功", U('Research/index'));
                } else {
                    $this->error("修改失败");
                }
            } else {
                $this->error("方向名称或方向编码不能为空");
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
        $del    = new ResearchService;
        $res    = $del->delete($id);
        if ($res) {
            $this->ajaxReturn(['msg'=>'删除成功～','code'=>1]);
        } else {
            $this->ajaxReturn(['msg'=>'删除失败～','code'=>2]);
        }
    }
}