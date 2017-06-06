<?php

namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\CourseExerciseService;
use Common\Service\Paper;
use Common\Service\PaperExerciseService;
use Common\Service\PaperService;


class WordController extends AdminbaseController {

    //上传word
    public function index(){

        //课程id
        $course_id = I('request.id');
        $this->assign('course_id',$course_id);
        $this->display();
    }

    //处理上传
    public function getContent(){
        //上传文件处理
        move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
        //文件地址
        $filename = "./upload/" . $_FILES["file"]["name"];
        //antiword执行
        $content = shell_exec('antiword -mUTF-8 '.$filename." 2>&1");
        //判断结果是否解析成功 , 如果不能解析则使用python解析
        if(strpos($content, 'is not a Word Document') !== false  || empty($content)) {
            $content = shell_exec('python ./vendor/python/parseDoc.py  '.$filename);
        }
        unlink($filename); //删除文件
        if(empty($content)){
            $this->error('WORD未成功解析，请重试！');
        }
//        //实例化paper类
        $data = (new Paper($content))->get();
        //测试
//        $data = (new Paper(''))->get();
        if(!empty($data['title']['error'])){
            $this->error('WORD未打标签，请确认！');
        }
        //试卷是否之前上传过
        $where = ['paper_title'  => $data['title']];
        $exist = (new PaperService())->getPapers($where);

//        if(count($exist) != 0){
//            exit("<script>alert('该试卷已经上传过，请确认！'); parent.location.reload();</script>");
//        }
        //课程id
        $course_id = I('request.course_id');
        //模板渲染
        $this->assign('result',$data);
        $this->assign('course_id',$course_id);
        $this->display();
    }

    //保存试卷内容
    public function save(){
        $param = I('request.');
        $data = $param['result'];
        $course_id = $param['course_id'];
//        echo "<pre>"; print_r($param);die;
        //开启事务
        $paperTrans=D('paper');
        $courseExerciseTrans=D('course_exercise');
        $paperExerciseTrans=D('paper_exercise');
        $paperTrans->startTrans();

        //整理数据(insert paper表)
        $totalscore = 0;             //总分数
        $total_exercise = 0;         //总题目数
        $ce_level_count=['low'=>0,'middle'=>0,'high'=>0];     //各个 易中难 程度的习题数量{ low:1,middle:1,high:2}
        foreach ($data['problems'] as $temp){
            $totalscore = $totalscore + ($temp['score']*$temp['amount']);
            $total_exercise = $total_exercise + $temp['amount'];
            foreach ($temp['case'] as $case){
                if(!empty($case['level'])){
//                    echo "<pre>"; print_r($case);die;
                    switch ($case['level'])
                    {
                        case '难':
                            $ce_level_count['high'] = $ce_level_count['high']+1;
                            break;
                        case '中':
                            $ce_level_count['middle'] = $ce_level_count['middle']+1;
                            break;
                        case '易':
                            $ce_level_count['low'] = $ce_level_count['low']+1;
                            break;
                    }
                }
            }
            //大题题干json
            $stems[] = $temp['title'];
        }
        $ce_level_count = json_encode($ce_level_count);
//        echo "<pre>"; print_r($data['problems']);die;

        $paperData = [
            'paper_title'       =>$data['title'],
            'total_time'        =>$data['time'],
            'paper_type'        =>$data['type'],
            'paper_no'          =>time(),
            'total_score'       =>$totalscore,
            'total_exercise'    =>$total_exercise,
            'ce_level_count'    =>$ce_level_count,
            'course_id'         =>$course_id,
            'stem'              =>json_encode($stems),
        ];
        $paperId = PaperService::addData($paperData);
//        echo "<pre>"; var_dump($id); die;
        if(empty($paperId))  $this->error('试卷基础信息未正常存储，请稍后重试！');
        //整理数据(insert course_exercise表)

        foreach ($data['problems'] as $order=>$temp) {

            //类型（单选，多选，判断）
            if(!empty($temp['type'])) {
                $type = 0;
                switch ($temp['type']) {
                    case 'radio':
                        $type = 1;
                        break;
                    case 'checkbox':
                        $type = 2;
                        break;
                    case 'drift':
                        $type = 3;
                        break;
                    case 'judge':
                        $type = 4;
                        break;
                }
            }

            foreach ($temp['case'] as $case) {
                $level = 0;
                //难易程度
                if(!empty($case['level'])){
                    switch ($case['level'])
                    {
                        case '难':
                            $level = 1;
                            break;
                        case '中':
                            $level = 2;
                            break;
                        case '易':
                            $level = 3;
                            break;
                    }
                }
            $courseExerciseData[] = [
                'ce_title' => $case['question'],
                'ce_no'    => $paperId,
                'ce_type'  => $type,
                'ce_level' => $level,
                'ce_skill' => $case['skill'],
                'ce_analyze'=>$case['exp'],
                'options'  => implode(" #$# ",$case['option']),
                'answer'   => $case['answer'],
                'score'    => $temp['score'],
                'sort'     => $case['sort'],
                'course_id'=> $course_id,
                'order'    => $order,
             ];
            }
        }
        //整理题与试卷关系并入库
        foreach ($courseExerciseData as $courseExerciseDatum) {
            $exeId = CourseExerciseService::addData($courseExerciseDatum);
            //处理题与试卷的关系
            $ship = [
                'paper_id'  => $paperId,
                'ce_id'     => $exeId,
                'ce_level'  => $courseExerciseDatum['ce_level'],
                'score'     => $courseExerciseDatum['score'],
                'sort'      => $courseExerciseDatum['sort'],
                'order'     => $courseExerciseDatum['order'],
                'course_id' => $course_id,
            ];
            $shipId = PaperExerciseService::addData($ship);
        }

        if(!empty($shipId)){

            $paperTrans->commit();//成功则提交
//            echo "<pre>"; print_r($shipId); die;
        }else{
            $paperTrans->rollback();//不成功，则回滚
        }
        echo "<script>parent.location.reload();</script>";

    }

    public function rules()
    {
        $rules = Paper::rules();
        exit($rules);
    }


}
