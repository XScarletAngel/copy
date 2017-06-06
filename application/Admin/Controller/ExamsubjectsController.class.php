<?php
/**
 * 考试科目列表
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\ExamSubjectsService;

class ExamsubjectsController extends AdminbaseController 
{
	/**
     * 方向查询列表
     */
    public function index() 
    {   
        $where      = array();
        $school     = I('request.school');      // 包括院校code,name
        $school_con = I('request.school_con');  // 搜索内容
        $dept       = I('request.dept');        // 包括院系code,name
        $dept_con   = I('request.dept_con');    // 搜索内容
        $major      = I('request.major');       // 包括专业code,name
        $major_con  = I('request.major_con');   // 搜索内容
        $rch_name   = I('request.rch_name');    // 方向名称
        $lang       = I('request.lang');        // 外语code,name
        $lang_con   = I('request.lang_con');    // 搜索内容
        $one        = I('request.one');         // 状态code,name
        $one_con    = I('request.one_con');     // 搜索内容
        $two        = I('request.two');         // 状态code,name
        $two_con    = I('request.two_con');     // 搜索内容
        $es_status  = I('request.es_status');   // 状态
        
        $where      = array('school' => $school, 'school_con' => $school_con, 'dept' => $dept, 'dept_con' => $dept_con, 'major' => $major, 'major_con' => $major_con, 'rch_name' => $rch_name, 'lang' => $lang, 'lang_con' => $lang_con, 'one' => $one, 'one_con' => $one_con, 'two' => $two, 'two_con' => $two_con, 'es_status' => $es_status);
        
        $exams      = new ExamSubjectsService;
        $content    = $exams->getall($where);
        // echo "<pre>";print_r($content['result']);die;
        $this->assign('page', $content['page']);  // 分页数据显示
        $this->assign('lists', $content['result']);  // 数据显示
        // $this->assign('where', $content['where']);  // 数据显示
       	$this->display();
    }
}