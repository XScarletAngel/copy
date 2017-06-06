<?php
/**
 * 参    数：
 * 作    者：lhr
 * 功    能：教研管理
 * 修改日期：2017-04-24
 */
namespace Course\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\CourseExerciseService;
use Common\Service\CourseService;
use Common\Service\ExportWordService;
use Common\Service\PaperExerciseService;
use Common\Service\PaperService;
use Common\Service\QiNiuService;
use Qiniu\Auth;

class ExercisesController extends AdminbaseController {

	/**
     * 表单导入习题
     */
	public function inputByForm()
	{
		$course_id = I('request.id');
        $course = new CourseService();
        $res    = $course->outline($course_id);
        $this->assign('res', $res);
        $this->assign('course_id', $course_id);
		$this->display();
	}

    /**
     * User: maChuang
     * @param string $str
     * @return mixed|string
     * 将字符串中base64图片上传七牛云，并替换img的src地址
     */
	public function LookUpBase64png($str = '')
    {
        if(empty($str)) return '';
        $sorts = [];
        //正则匹配取出base64编码
        $str = $this->strReplace($str);
        preg_match_all('/(data:image\/png;base64\,(.*?))"\s+data-latex/s',  $str, $sorts);
        //如果不将html转换，可以使用下面的正则匹配
//        preg_match_all('/(data:image\/png;base64\,(.*?))&quot;\s+data-latex/s',  $str, $sorts);
        if(empty($sorts[1])) return $str;//如果没有base64编码图片，则返回源字符串
        //字符串替换
        $replacStr = $str;
        foreach($sorts[1] as $repkey=>$repvalue){
            //base64图片上传并生成地址
            $req_url = C('QINIU.BUCKET_DOMAIN')['xkht-paper'].$this->base64UpQiniu($sorts[2][$repkey]);
            $replacStr = str_replace($repvalue,$req_url,$replacStr);
        }
        return $replacStr;
    }
    /**
     * User: maChuang
     * 表单导入post处理
     */
	public function store()
    {
        // 接受表单提交数据
        $param = I('request.');
        // 数据验证
        if(empty($param['ce_title']))   $this->error('请填写标题');
        if(empty($param['ce_analyze'])) $this->error('请填写分析');
        if(empty($param['lloptions']))  $this->error('请填写选择项');
        if(empty($param['ce_skill']))   $this->error('请选择技能');
        if(empty($param['score']))      $this->error('请选择分值');
        if(empty($param['answer']))     $this->error('请选择答案');
        // 课程id
        $course_id  = $param['course_id'];
        // 处理提交内容中base64编码图片
        $param['ce_title']   = $this->LookUpBase64png($this->strReplace($param['ce_title']));
        $param['ce_analyze'] = $this->LookUpBase64png($this->strReplace($param['ce_analyze']));
        $param['point_info'] = implode(',',$param['point_info']);
        foreach ($param['lloptions'] as $lk=>$ll){
            if(empty($ll))  $this->error('请完善选项内容');
            $llReq[$lk] = $this->LookUpBase64png($this->strReplace($ll));
        }
        $param['answer'] =  implode(',',$param['answer']);
        // json化选项字段
        $param['options'] = implode('#$#',$llReq);
        // 清理
        unset($param['lloptions']);
        // 添加一道习题
        CourseExerciseService::addData($param);
//        echo M()->getLastSql();die;
        $this->redirect('exercises/inputByForm', array('id' => $course_id), 1, '页面跳转中...');

    }

    /**
     * User: maChuang
     * 修改习题
     */
    public function editExercise()
    {
        $exercise_id = I('request.exercise_id');
        if(empty($exercise_id)) $this->error('未指定习题！');
        $data = CourseExerciseService::find($exercise_id);
        if(empty($data)) $this->error('习题未找到！！');
        $data['options'] = explode('#$#',$data['options']);
        $data['point_info'] = explode(',',$data['point_info']);
        $this->assign('data', $data);
//        echo"<pre>"; print_r($data);die;
        $course_id = $data['course_id'];
        $course = new CourseService();
        $res    = $course->outline($course_id);
        $this->assign('res', $res);
//        echo"<pre>"; print_r($res);die;
        $this->display();
    }

    /**
     * User: maChuang
     * 更新习题数据
     */
    public function updateExer()
    {
        $param = I('request.');
//        echo "<pre>"; print_r($param);die;
        $where = ['id'=>$param['exercise_id']];
        for($i=(count($param['lloptions'])-1);$i>=0;$i--){
            if(empty($param['lloptions'][$i]))  $this->error('请完善选项内容');
            $llReq[] = $this->strReplace($this->LookUpBase64png($param['lloptions'][$i]));
        }
        // 处理提交内容中base64编码图片
        $param['ce_title']   = $this->LookUpBase64png($param['ce_title']);
        $param['ce_analyze'] = $this->LookUpBase64png($param['ce_analyze']);
        $param['point_info'] = implode(',',$param['point_info']);

        $updata = [
            'ce_type'   =>  $this->strReplace($param['ce_type']),
            'ce_skill'  =>  $this->strReplace($param['diy_ce_skill']),
            'ce_level'  =>  $this->strReplace($param['ce_level']),
            'answer'    =>  $this->strReplace($param['answer']),
            'score'     =>  $this->strReplace($param['score']),
            'options'   =>  implode("#$#",$llReq),
            'ce_analyze'=>  $this->strReplace($param['ce_analyze']),
            'ce_title'  =>  $this->strReplace($param['ce_title']),
            'point_info'=>  $param['point_info']
        ];
//        echo "<pre>";  print_r($updata);die;
        $res = (new CourseExerciseService())->update($where,$updata);
//        echo $res;die;
        if($res){
            echo "<script>history.go(-2);</script>";
        }else{
            $this->error('没有更新信息，请重试！');
        }

    }

    /**
     * User: maChuang
     * @param $str
     * @return mixed
     * 字符串清洗
     */
    public function strReplace($str){
        return str_replace(['<p>','</p>','<br/>','<span>','</span>','&nbsp;'],'',html_entity_decode($str));
    }

    /**
     * User: maChuang
     * 查看习题
     */
    public function showExercise()
    {
        $exercise_id = I('request.exercise_id');
        if(empty($exercise_id)) $this->error('未指定习题！');
        $data = CourseExerciseService::find($exercise_id);
        $this->assign('data', $data);
        $this->display();
    }


    /**
     * User: maChuang
     * 设计试卷
     */
    public function desPaper()
    {
        $course_id = I('request.course_id');
//        if(empty($course_id)) $this->error('设计师卷需先指定课程！');
        $this->assign('course_id', $course_id);
        $this->display();
    }

    /**
     * User: maChuang
     * 设计师卷提交
     */
    public function paperStore()
    {
        //整理post表单提交参数
        $param = I('request.');
//        echo "<pre>"; print_r($param);die;
        //课程id
        $course_id = $param['course_id'];
        //试卷标题
        $paper_title = $param['paper_title'];
        //考试说明
        $exam_info = $param['exam_info'];
        //题量
        $total_exercise = $param['total_exercise'];
        //level为难的题量
        $diff_num = $param['diff_num'];
        //level为中的题量
        $middle_num = $param['middle_num'];
        //level为易的题量
        $easy_num = $param['easy_num'];
        //总分值
        $total_score = $param['total_score'];
        //限时分钟
        $total_time = $param['total_time'];
        //试卷类型
        $paper_type = $param['paper_type'];

        //如果设计的试卷有单选题
        if(!empty($param['danxmain'])){
            $stems_que_count[$param['danxmain']] = count($param['danxlistId']);//大题题量
            $stems_title[$param['danxmain']-1]     = $param['danxtitle'];//大题标题
            $stems_info[$param['danxmain']-1]      = empty($param['danxinfo'])?"_":$param['danxinfo'];//大题说明
        }
        //如果设计的试卷有多选题
        if(!empty($param['duoxmain'])){
            $stems_que_count[$param['duoxmain']] = count($param['duoxlistId']);//大题题量
            $stems_title[$param['duoxmain']-1]     = $param['duoxtitle'];//大题标题
            $stems_info[$param['duoxmain']-1]      = empty($param['duoxinfo'])?"_":$param['duoxinfo'];//大题说明
        }
        //如果设计的试卷有判断题
        if(!empty($param['pandmain'])){
            $stems_que_count[$param['pandmain']] = count($param['pandlistId']);//大题题量
            $stems_title[$param['pandmain']-1]     = $param['pandtitle'];//大题标题
            $stems_info[$param['pandmain']-1]      = empty($param['pandinfo'])?"_":$param['pandinfo'];//大题说明
        }
        //如果设计的试卷有不定选题
        if(!empty($param['budxmain'])){
            $stems_que_count[$param['budxlistId']] = count($param['budxlistId']);//大题题量
            $stems_title[$param['budxmain']-1]       = $param['budxtitle'];//大题标题
            $stems_info[$param['budxmain']-1]        = empty($param['budxinfo'])?"_":$param['budxinfo'];//大题说明
        }
        //对数组单元按照键名从低到高进行排序
        ksort($stems_title);
        ksort($stems_info);
        //试卷编号
        $paper_no = date('Ymd');
        $ce_level_count = ["low"=>$easy_num,"middle"=>$middle_num,"high"=>$diff_num];
        $paperData = [
            'course_id'         =>  $course_id,
            'paper_title'       =>  $paper_title,
            'exam_info'         =>  $exam_info,
            'total_time'        =>  $total_time,
            'paper_type'        =>  $paper_type,
            'paper_no'          =>  $paper_no,
            'total_score'       =>  $total_score,
            'total_exercise'    =>  $total_exercise,
            'ce_level_count'    =>  json_encode($ce_level_count),
            'stem'              =>  json_encode($stems_title),
            'stem_info'         =>  json_encode($stems_info),
        ];
//        echo "<pre>";  print_r($param['danxlistId']); print_r($stems_que_count);die;

        //添加试卷信息
        $paper_id = PaperService::addData($paperData);
//        echo "<pre>";  print_r($paper_id);die;
        //单选题
        if(!empty($param['danxlistId'])){
            foreach ($param['danxlistId'] as $dk=>$dv){

                $ship[] = [
                    'course_id' =>  $course_id,
                    'paper_id'  =>  $paper_id,
                    'ce_id'     =>  $dv,
                    'ce_level'  =>  $param['danxlistDiff'][$dk],
                    'score'     =>  $param['danxlistScore'][$dk],
                    'sort'      =>  ($param['danxmain'] == 1)?$dk:($dk+($stems_que_count[$param['danxmain']-1])),
                    'order'     =>  ($param['danxmain']-1)
        ];
            }
        }
//        echo "<pre>";  print_r($ship);die;
        //多选题
        if(!empty($param['duoxlistId'])){
            foreach ($param['duoxlistId'] as $dxk=>$dxv){
                $ship[] = [
                    'course_id' =>  $course_id,
                    'paper_id'  =>  $paper_id,
                    'ce_id'     =>  $dxv,
                    'ce_level'  =>  $param['duoxlistDiff'][$dxk],
                    'score'     =>  $param['duoxlistScore'][$dxk],
                    'sort'      =>  ($param['duoxmain'] == 1)?$dxk:($dxk+($stems_que_count[$param['duoxmain']-1])),
                    'order'     =>  ($param['duoxmain']-1)
                ];
            }
        }
        //不定选题
        if(!empty($param['budxlistId'])){
            foreach ($param['budxlistId'] as $bk=>$bv){
                $ship[] = [
                    'course_id' =>  $course_id,
                    'paper_id'  =>  $paper_id,
                    'ce_id'     =>  $bv,
                    'ce_level'  =>  $param['budxlistDiff'][$bk],
                    'score'     =>  $param['budxlistScore'][$bk],
                    'sort'      =>  ($param['budxmain'] == 1)?$bk:($bk+($stems_que_count[$param['budxmain']-1])),
                    'order'     =>  ($param['budxmain']-1)
                ];
            }
        }
        //判断题
        if(!empty($param['pandlistId'])){
            foreach ($param['pandlistId'] as $pk=>$pv){
                $ship[] = [
                    'course_id' =>  $course_id,
                    'paper_id'  =>  $paper_id,
                    'ce_id'     =>  $pv,
                    'ce_level'  =>  $param['pandlistDiff'][$pk],
                    'score'     =>  $param['pandlistScore'][$pk],
                    'sort'      =>  ($param['pandmain'] == 1)?$pk:($pk+($stems_que_count[$param['pandmain']-1])),
                    'order'     =>  ($param['pandmain']-1)
                ];
            }
        }
//        echo "<pre>";  print_r($ship);die;
        foreach ($ship as $s){
            $res = PaperExerciseService::addData($s);
        }
        if($res){
           echo "<script>window.history.go(-2);</script>";
        }else{
            $this->error('试卷生成失败，请重新再试！');
        }
    }

    /**
     * User: maChuang
     * 设计师卷ajax更新提交
     */
    public function paperAjaxSave()
    {
        $param = I('request.');
        $where = ['id'=>$param['paper_id']];
        $ce_level_count = [
            'low'           =>  $param['easy_num'],
            'middle'        =>  $param['middle_num'],
            'high'          =>  $param['diff_num'],
        ];
        $upData = [
            'paper_title'       =>  $param['paper_title'],
            'exam_info'         =>  $param['exam_info'],
            'total_exercise'    =>  $param['total_exercise'],
            'total_score'       =>  $param['total_score'],
            'paper_type'        =>  $param['paper_type'],
            'ce_level_count'    =>  json_encode($ce_level_count),
        ];
        $res = (new PaperService())->update($where,$upData);
        exit($res);
    }



    /**
     * User: maChuang
     * 习题管理列表
     */
    public function  showExer()
    {
        $param = I('request.');
        $where = [];
        if (!empty($param['ce_type'])) {
            $where['ce_type'] = $param['ce_type'];
        }
        if (!empty($param['ce_skill'])) {
            $where['ce_skill'] = $param['ce_skill'];
        }
        if (!empty($param['ce_level'])) {
            $where['ce_level'] = $param['ce_level'];
        }
        if (!empty($param['point_info'])) {
            $where['point_info'] = $param['point_info'];
        }
        $res = CourseExerciseService::findCourseExercise($where);
        $this->assign('data',$res['list']);// 赋值数据集
        $this->assign('page',$res['page']);// 赋值分页输出
        $this->assign('param', $param);    // 搜索参数维持
        $this->display();
    }



    /**
     * User: maChuang
     * 试卷导出word
     */
    public function export()
    {
//        $url = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

        //接受参数，试卷id
        $paper_id = I('request.paper_id');
        if(empty($paper_id)) return $this->error('未正确选择需要导出的试卷！');
        //取出试卷基本信息
        $paper=PaperService::find($paper_id);
        if(!$paper) return $this->error('该试卷不存在！');
        //取出与试卷有关联关系的习题
        $ship = (new PaperExerciseService())->getPaperExercises(['paper_id'=>$paper_id],'ce_id,sort,order');
        if(!$ship) return $this->error('该试卷不含有习题！');
        foreach ($ship as $k=>$ti){
            $exers[$k] =  CourseExerciseService::find($ti['ce_id']);
            $exers[$k]['options'] = explode('#$#',$exers[$k]['options']);
            $exers[$k]['sort'] = $ti['sort'];  //习题在大题中的序号
            $exers[$k]['order'] = $ti['order'];//习题属于哪一道大题
        }

        //试卷全部元数据
        $data=[
            'title'=>$paper['paper_title'],
            'total_score'=>$paper['total_score'],
            'total_time'=>$paper['total_time'],
            'stems'=>json_decode($paper['stem']),
            'questions'=>$exers,
        ];
//        echo "<pre>";  print_r($data);die;

        //拼接word基本信息
        $wordhtml = '<style>
                        h1{text-align: center;margin: 10px auto;}h5{text-align: center;margin: 10px auto;}.word-content{margin: 10px 20px;}
                     </style>
                        <div class="word-content">
                        <h1>'.$data["title"].'</h1>
                        <h5>总分：'.$data["total_score"].'分&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;限时：'.$data["total_time"].'分钟</h5>';
        //拼接word大题部分
        foreach ($data['stems'] as $skey=>$stem) {
            $wordhtml.= '<h4>'.$stem.'</h4>';
            foreach ($data['questions'] as $qkey=>$qo) {
                if($qo['order']==$skey){
                    $wordhtml.= '<p>'.$qo['ce_title'].'</p>';
                    foreach ($qo['options'] as $opt){
                        $wordhtml.= '<p>'.$opt.'</p>';
                    }
                }
            }
        }
        $wordhtml.='</div>';
        //实例化word导出类
        $word = new ExportWordService();
        $word->start();
        //word命名
        $newname = $paper['paper_title'];
        echo $wordhtml;
        //生成文件路径
        $localWordPath = './upload/word/'.$newname.".doc";
        $word->save($localWordPath);
        ob_flush();//每次执行前刷新缓存
        flush();
        //文件上传七牛云
        $QiniuWordPath = QiNiuService::uploadWord($newname,$localWordPath);
        //删除本地文件
        unlink($localWordPath);
        return redirect($QiniuWordPath);
//      此处需要下载后，删除word，后期补上该功能
    }


    /**
     * 七牛云上传base64格式图片
     * User: maChuang
     * @param string $str
     * @return mixed
     */
    public function base64UpQiniu($str= '')
    {
        header('Access-Control-Allow-Origin:*');
        $bucket    = C("QINIU.PAPER_BUCKET");
        $accessKey = C("QINIU.ACCESS_KEY");
        $secretKey = C("QINIU.SECRET_KEY");
        $auth = new Auth($accessKey, $secretKey);
        $upToken = $auth->uploadToken($bucket, null, 3600);//获取上传所需的token
//        $str = "Qk22EgAAAAAAADYAAAAoAAAAIAAAACUAAAABACAAAAAAAIASAAASCwAAEgsAAAAAAAAAAAAA/wAA//4AAP/5AAD/9QAA//EAAP/sAAD/5wAA/+MAAP/fAAD/2gAA/9YAAP/RAAD/zQAA/8gAAP/EAAD/wAAA/7sAAP+3AAD/sgAA/64AAP+pAAD/pQAA/6EAAP+cAAD/lwAA/5MAAP+OAAD/igAA/4YAAP+BAAD/fQAA/3gAAP//AAD//gAA//kAAP/0AAD/8QAA/+wAAP/oAAD/4wAA/98AAP/aAAD/1gAA/9IAAP/NAAD/yAAA/8QAAP/AAAD/uwAA/7YAAP+yAAD/rgAA/6kAAP+lAAD/oAAA/5wAAP+XAAD/kwAA/44AAP+KAAD/hQAA/4EAAP99AAD/eAAA//8AAP/+AAD/+QAA//UAAP/wAAD/7AAA/+gAAP/jAAD/3gAA/9oAAP/WAAD/0QAA/80AAP/IAAD/xAAA/8AAAP+7AAD/twAA/7MAAP+uAAD/qQAA/6UAAP+hAAD/nAAA/5cAAP+TAAD/jwAA/4oAAP+GAAD/ggAA/30AAP94AAD//wAA//0AAP/6AAD/9QAA//AAAP/sAAD/5wAA/+MAAP/fAAD/2gAA/9UAAP/RAAD/zQAA/8gAAP/EAAD/vwAA/7sAAP+3AAD/sgAA/64AAP+pAAD/pQAA/6AAAP+cAAD/mAAA/5MAAP+PAAD/igAA/4YAAP+BAAD/fQAA/3gAAP//AAD//gAA//kAAP/0AAD/8QAA/+wAAP/nAAD/4wAA/94AAP/aAAD/1gAA/9IAAP/NAAD/yAAA/8QAAP+/AAD/uwAA/7cAAP+yAAD/rgAA/6kAAP+lAAD/oQAA/5wAAP+YAAD/kwAA/44AAP+KAAD/hgAA/4EAAP99AAD/eAAA//8AAP/+AAD/+gAA//UAAP/wAAD/7AAA/+gAAP/jAAD/3gAA/9oAAP/WAAD/0gAA/80AAP/JAAD/xAAA/8AAAP+7AAD/twAA/7IAAP+uAAD/qQAA/6UAAP+hAAD/nAAA/5gAAP+TAAD/jgAA/4oAAP+GAAD/ggAA/3wAAP94AAD//wAA//0AAP/5AAD/9QAA//AAAP/sAAD/5wAA/+MAAP/NAED/sQCf/6EA3/+ZAP//mQD//5kA//+ZAP//mQD//5kA//+ZAP//mQD//5kA//+ZAP//mwDP/5wAj/+cACD/mAAA/5MAAP+PAAD/igAA/4YAAP+CAAD/fQAA/3gAAP//AAD//gAA//kAAP/1AAD/8QAA/+wAAP/iABD/rAC//5kA//+ZAP//mQD//5kA//+ZAP//mQD//5kA//+ZAP//mQD//5kA//+ZAP//mQD//5kA//+ZAP//mQD//5kA//+YAI//kwAQ/48AAP+KAAD/hgAA/4EAAP98AAD/eAAA//8AAP/+AAD/+QAA//UAAP/wAAD/3AAw/54A7/+ZAP//mQD//5kA//+ZAP//mQD//5kA//+ZAP//mQD//5kA//+ZAP//mQD//5kA//+ZAP//mQD//5kA//+ZAP//mQD//5kA//+YAM//kAAw/4oAAP+FAAD/gQAA/30AAP94AAD//wAA//4AAP/6AAD/9QAA/+sAEP+jAN//mQD//5kA//+ZAP//mQD//5kA//+ZAP//oADf/6UAv/+kAL//owC//6IAv/+gAL//nwC//5wA3/+ZAP//mQD//5kA//+ZAP//mQD//5kA//+YAO//iwAQ/4UAAP+BAAD/fAAA/3gAAP//AAD//gAA//oAAP/0AAD/ugCf/5kA//+ZAP//mQD//5kA//+hAN//vwBg/80AEP/MAAD/yQAA/8QAAP+/AAD/uwAA/7cAAP+yAAD/rQAA/6kAEP+gAGD/mgDf/5kA//+ZAP//mQD//5kA//+VAL//hgAA/4EAAP99AAD/eAAA//8AAP/+AAD/+gAA/+kAIP+ZAP//mQD//5kA//+ZAP//swCf/9YAEP/VAAD/vABg/7MAgP+wAID/sQBw/7sAIP+4ACD/qACA/6UAgP+jAID/owBg/6UAAP+gABD/mgCf/5kA//+ZAP//mQD//5kA//+LAED/gQAA/30AAP94AAD//wAA//0AAP/6AAD/wQCP/5kA//+ZAP//mQD//6IA3//aABD/zgAw/6EA3/+ZAP//mQD//5kA//+ZAP//mQD//5kA//+ZAP//mQD//5kA//+ZAP//mwDP/6AAEP+cABD/mQDf/5kA//+ZAP//mQD//5EAn/+BAAD/fQAA/3gAAP//AAD//gAA//kAAP+qAM//mQD//5kA//+ZAP//xwBg/94AAP+lAM//mQD//5kA//+ZAP//mQD//5kA//+ZAP//mQD//5kA//+ZAP//mQD//5kA//+ZAP//mwC//5wAAP+YAGD/mQD//5kA//+ZAP//lwDf/4EAAP99AAD/eAAA//8AAP/+AAD/+QAA/5kA//+ZAP//mQD//5kA///eABD/yQBQ/5kA//+ZAP//mQD//5kA//+ZAP//mQD//5kA//+ZAP//mQD//5kA//+ZAP//mQD//5kA//+ZAP//mwBQ/5gAEP+ZAP//mQD//5kA//+ZAP//ggAA/30AAP95AAD//wAA//4AAP/5AAD/mQD//5kA//+ZAP//owDf/+MAAP+7AID/mQD//5kA//+ZAP//owDP/64Aj/+ZAP//mQD//5kA//+ZAP//pACP/50Az/+ZAP//mQD//5kA//+aAID/mAAA/5gA3/+ZAP//mQD//5kA//+CAAD/fAAA/3gAAP//AAD//gAA//oAAP+ZAP//mQD//5kA//+tAL//4wAA/7wAgP+ZAP//mQD//5kA//+zAID/yAAA/5kA//+ZAP//mQD//5kA//+yAAD/owCA/5kA//+ZAP//mQD//5oAgP+YAAD/lwC//5kA//+ZAP//mQD//4EAAP99AAD/eAAA//8AAP/9AAD/+QAA/5kA//+ZAP//mQD//60Av//jAAD/vACA/5kA//+ZAP//mQD//7IAgP/IAAD/mQD//5kA//+ZAP//mQD//7IAAP+jAID/mQD//5kA//+ZAP//mgCA/5cAAP+XAL//mQD//5kA//+ZAP//gQAA/30AAP95AAD//wAA//0AAP/5AAD/3gBA/9oAQP/XAED/2AAw/+MAAP+7AID/mQD//5kA//+ZAP//swCA/8gAAP+ZAP//mQD//5kA//+ZAP//sgAA/6MAgP+ZAP//mQD//5kA//+aAID/lwAA/5QAMP+SAED/jwBA/4sAQP+BAAD/fQAA/3kAAP//AAD//gAA//oAAP/dAED/2gBA/9cAQP/ZADD/4wAA/7wAgP+ZAP//mQD//5kA//+zAID/yAAA/5kA//+ZAP//mQD//5kA//+zAAD/owCA/5kA//+ZAP//mQD//5oAgP+YAAD/lAAw/5IAQP+OAED/iwBA/4EAAP98AAD/eQAA//8AAP/+AAD/+gAA/5kA//+ZAP//mQD//60Av//kAAD/vACA/5kA//+ZAP//mQD//7MAgP/JAAD/xAAA/78AAP+7AAD/twAA/7IAAP+jAID/mQD//5kA//+ZAP//mgCA/5cAAP+XAL//mQD//5kA//+ZAP//gQAA/3wAAP94AAD//wAA//4AAP/5AAD/mQD//5kA//+ZAP//rQC//+MAAP+8AID/mQD//5kA//+ZAP//swCA/8gAAP/EAAD/wAAA/7sAAP+2AAD/sgAA/6MAgP+ZAP//mQD//5kA//+aAID/lwAA/5cAv/+ZAP//mQD//5kA//+BAAD/fQAA/3gAAP//AAD//gAA//kAAP+ZAP//mQD//5kA//+jAN//4wAA/7wAgP+ZAP//mQD//5kA//+zAID/yAAA/8QAAP/AAAD/uwAA/7YAAP+yAAD/owCA/5kA//+ZAP//mQD//5oAgP+XAAD/mADf/5kA//+ZAP//mQD//4EAAP99AAD/eQAA//8AAP/9AAD/+QAA/5kA//+ZAP//mQD//5kA///eABD/yQBQ/5kA//+ZAP//mQD//7MAgP/IAAD/xAAA/78AAP+7AAD/twAA/7IAAP+jAID/mQD//5kA//+ZAP//mwBA/5cAEP+ZAP//mQD//5kA//+ZAP//gQAA/30AAP94AAD//wAA//4AAP/5AAD/qgDP/5kA//+ZAP//mQD//8cAYP/fAAD/pQDP/5kA//+ZAP//swCA/8kAAP/EAAD/vwAA/7sAAP+2AAD/swAA/6MAgP+ZAP//mQD//5sAv/+bAAD/mABg/5kA//+ZAP//mQD//5cA3/+BAAD/fQAA/3kAAP//AAD//gAA//kAAP/BAI//mQD//5kA//+ZAP//ogDf/9sAEP/WABD/pADP/5kA//+yAID/yAAA/8QAAP/AAAD/uwAA/7YAAP+yAAD/owCA/5kA//+bAM//oAAQ/5wAEP+ZAN//mQD//5kA//+ZAP//kgCf/4EAAP99AAD/eQAA//8AAP/+AAD/+gAA/+kAIP+ZAP//mQD//5kA//+ZAP//swCf/9YAEP/VAAD/wABQ/8AAQP/IAAD/xAAA/78AAP+7AAD/tgAA/7IAAP+pAED/pABQ/6QAAP+gABD/mgDP/5kA//+ZAP//mQD//5kA//+LAED/gQAA/30AAP94AAD//wAA//4AAP/5AAD/9QAA/7oAn/+ZAP//mQD//5kA//+ZAP//oQDf/78AYP/RAAD/zQAA/8kAAP/EAAD/wAAA/7sAAP+2AAD/sgAA/64AAP+oABD/oABg/5oA3/+ZAP//mQD//5kA//+ZAP//lQC//4UAAP+BAAD/fQAA/3kAAP//AAD//gAA//kAAP/1AAD/6wAQ/6MA3/+ZAP//mQD//5kA//+ZAP//mQD//5kA//+gAN//pQC//6QAv/+jAL//ogC//6AAv/+gAL//nADf/5kA//+ZAP//mQD//5kA//+ZAP//mQD//5gA7/+LABD/hgAA/4IAAP99AAD/eAAA//8AAP/+AAD/+gAA//UAAP/xAAD/3AAw/54A7/+ZAP//mQD//5kA//+ZAP//mQD//5kA//+ZAP//mQD//5kA//+ZAP//mQD//5kA//+ZAP//mQD//5kA//+ZAP//mQD//5kA//+YAM//kQAw/4oAAP+GAAD/gQAA/30AAP95AAD//wAA//4AAP/5AAD/9QAA//EAAP/sAAD/3QAg/6wAv/+ZAP//mQD//5kA//+ZAP//mQD//5kA//+ZAP//mQD//5kA//+ZAP//mQD//5kA//+ZAP//mQD//5kA//+ZAP//mACP/5MAEP+OAAD/igAA/4YAAP+BAAD/fAAA/3gAAP//AAD//QAA//kAAP/1AAD/8AAA/+wAAP/nAAD/4wAA/80AQP+xAJ//oQDf/5kA//+ZAP//mQD//5kA//+ZAP//mQD//5kA//+ZAP//mQD//5kA//+bAM//nQCP/5sAIP+XAAD/kwAA/44AAP+KAAD/hgAA/4IAAP98AAD/eAAA//8AAP/+AAD/+QAA//UAAP/wAAD/7AAA/+gAAP/kAAD/3wAA/9oAAP/VAAD/0gAA/80AAP/IAAD/xAAA/78AAP+7AAD/twAA/7IAAP+uAAD/qQAA/6UAAP+gAAD/nAAA/5cAAP+TAAD/jgAA/4oAAP+GAAD/gQAA/30AAP95AAD//wAA//4AAP/6AAD/9QAA//EAAP/sAAD/6AAA/+MAAP/eAAD/2wAA/9UAAP/RAAD/zQAA/8gAAP/EAAD/vwAA/7sAAP+2AAD/sgAA/64AAP+pAAD/pAAA/6AAAP+cAAD/mAAA/5MAAP+OAAD/igAA/4YAAP+BAAD/fQAA/3gAAP//AAD//gAA//kAAP/1AAD/8AAA/+wAAP/oAAD/4wAA/98AAP/aAAD/1QAA/9EAAP/MAAD/yAAA/8QAAP/AAAD/uwAA/7YAAP+zAAD/rgAA/6kAAP+lAAD/oAAA/5wAAP+XAAD/kwAA/44AAP+KAAD/hQAA/4EAAP99AAD/eAAA//8AAP/+AAD/+QAA//UAAP/xAAD/7AAA/+gAAP/jAAD/3wAA/9oAAP/WAAD/0QAA/8wAAP/JAAD/xAAA/78AAP+7AAD/tgAA/7MAAP+uAAD/qQAA/6UAAP+hAAD/mwAA/5cAAP+TAAD/jwAA/4oAAP+GAAD/gQAA/30AAP94AAD//wAA//4AAP/5AAD/9QAA//EAAP/sAAD/5wAA/+MAAP/fAAD/2gAA/9YAAP/RAAD/zQAA/8kAAP/DAAD/wAAA/7sAAP+2AAD/sgAA/64AAP+pAAD/pQAA/6AAAP+cAAD/lwAA/5MAAP+OAAD/igAA/4YAAP+CAAD/fQAA/3gAAP8=";
        $curlData = $this->request_by_curl('http://upload.qiniu.com/putb64/-1',$str,$upToken);
        $filename = json_decode($curlData);
        //返回文件名，没有后坠
        return  $filename->key;
    }


    /**
     * curl请求
     * User: maChuang
     * @param $remote_server
     * @param $post_string
     * @param $upToken
     * @return mixed
     */
    public function request_by_curl($remote_server,$post_string,$upToken) {

        $headers = array();
        $headers[] = 'Content-Type:image/png';
        $headers[] = 'Authorization:UpToken '.$upToken;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$remote_server);
        //curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER ,$headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }


//$sql = "UPDATE xkht_course_exercise SET ce_title=REPLACE(ce_title, '&nbsp;', ''); ";
//$voList = M()->query($sql);


}
