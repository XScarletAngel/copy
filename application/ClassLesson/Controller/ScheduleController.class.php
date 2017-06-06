<?php
namespace ClassLesson\Controller;

use Common\Controller\AdminbaseController;
use Common\Model\UsersModel;
use Common\Service\ClassNoticeService;
use Common\Service\ClassRoomService;
use Common\Service\ClassScheduleService;
use Common\Service\ClassService;
use Common\Service\CourseService;
use Common\Service\LiveService;
use Common\Service\ScheduleService;
use Common\Service\SchoolZoneService;
use Common\Service\Tools;
use Common\Service\UsersService;

class ScheduleController extends AdminbaseController {

    /**
     * 课表管理
     */
    public function index()
    {
        $param = I('request.'); //echo '<pre>'; print_r($param);die;
        $where = [];
        if(!empty($param['sub_zone_id'])){
            $where['sub_zone_id'] = $param['sub_zone_id'];
        }
        if(!empty($param['zone_id'])){
            $where['zone_id'] = $param['zone_id'];
        }
        if(!empty($param['room_id'])){
            $where['id'] = $param['room_id'];
        }
        $check = ClassRoomService::getAllRoom($where);
        foreach ($check as $tp){
            $room_ids[] =$tp['id'];
        }
        $param['firstDay'] = empty($param['firstDay'])?time():strtotime(trim($param['firstDay']));//指定某天的时间戳
        //获得表单抬头的日期
        $headDate =[
            'first'   => date('Y-m-d',$param['firstDay'])."<br> ".$this->wk(date('Y-m-d',$param['firstDay'])),
            'second'  => date('Y-m-d',($param['firstDay'])+86400)." <br>".$this->wk(date('Y-m-d',($param['firstDay'])+86400)),
            'third'   => date('Y-m-d',($param['firstDay'])+172800)." <br>".$this->wk(date('Y-m-d',($param['firstDay'])+172800)),
            'fourth'  => date('Y-m-d',($param['firstDay'])+259200)." <br>".$this->wk(date('Y-m-d',($param['firstDay'])+259200)),
            'fifth'   => date('Y-m-d',($param['firstDay'])+345600)." <br>".$this->wk(date('Y-m-d',($param['firstDay'])+345600)),
            'sixth'   => date('Y-m-d',($param['firstDay'])+432000)." <br>".$this->wk(date('Y-m-d',($param['firstDay'])+432000)),
            'seventh' => date('Y-m-d',($param['firstDay'])+518400)."<br> ".$this->wk(date('Y-m-d',($param['firstDay'])+518400)),
        ];
        //获得表单的七天查询的日期
        $date = [
            0=>date('Y-m-d',$param['firstDay']),
            1=>date('Y-m-d',($param['firstDay'])+86400),
            2=>date('Y-m-d',($param['firstDay'])+172800),
            3=>date('Y-m-d',($param['firstDay'])+259200),
            4=>date('Y-m-d',($param['firstDay'])+345600),
            5=>date('Y-m-d',($param['firstDay'])+432000),
            6=>date('Y-m-d',($param['firstDay'])+518400),
        ];
        //这7天内有哪些教室有课
        $sql ='class_date in ('."'".$date[0]."'".","."'".$date[1]."'".","."'".$date[2]."'".","."'".$date[3]."'".","."'".$date[4]."'".","."'".$date[5]."'".","."'".$date[6]."'".")";
        $rooms = ScheduleService::select($sql,"room_id");
//        echo "<pre>";print_r($rooms);die;
        $existRooms = [];
        foreach($rooms as $ro){
            $existRooms[]=$ro['room_id'];
        }
        //得出有课的房间
        $existRooms = array_values(array_unique($existRooms));
        //遍历得出筛选后的教室
        foreach($existRooms as $k=>$existRoom){
            //判断有课的教室是否在筛选条件范围内
            if(!in_array($existRoom,$room_ids)){
                unset($existRooms[$k]);
            }
        }

        //遍历行（一个教室为一行）
        foreach ($existRooms as $roomKey=>$room_id){
            $res1 = ClassRoomService::find($room_id);
//            print_r($res1);
            $res[$roomKey]['room_name'] = $res1['room_name'];
            $res[$roomKey]['sub_zone_name'] = $res1['sub_zone_name'];
            //遍历同一行的每一列（一个教室的一周课程）
            foreach ($date as $timeKey=>$class_date){
                $where=[
                    'room_id'=>$room_id,
                    'class_date'=>$class_date
                ];
                $res[$roomKey]['data'][$timeKey] = ScheduleService::select($where);
            }
        }

        $param['firstDay'] = date("Y-m-d",$param['firstDay']);

        //获取分校信息
        $class       = new ClassNoticeService();
        $school      = $class->zone();
        $this->assign('school', $school);  // 学校
        //获取全部教室
        $allRooms = ClassRoomService::getAllRoom();

        //直播课所属分校校区的数据
        $area = [];
        if(!empty($param['sub_zone_id'])){
            $area['sub_zone_id'] = $param['sub_zone_id'];
        }
        if(!empty($param['zone_id'])){
            $area['zone_id'] = $param['zone_id'];
        }
        $livesArray = (new LiveService())->getAll($area,'id');
        $livesArray =  array_column($livesArray,'id');
        //获取直播课数据
        $lives = [];
        foreach ($date as $k=>$item) {
            $where=[
                'class_date'=>$item,
                'live_id'=>array('in',implode(',',$livesArray)),
            ];
            $lives[$k] = ScheduleService::select($where);
            if(!empty($lives[$k])){
                foreach ($lives[$k] as $key=>$val) {
                    $classInfo =  ClassService::find($val['class_id']);
                    $lives[$k][$key]['class_name'] = $classInfo['class_name'];
                }
            }
        };
        //数据绑定模板
        $this->assign('lives', $lives);
        $this->assign('data', $res);
        $this->assign('headDate', $headDate);
        $this->assign('allRooms', $allRooms);
        $this->assign('param', $param);  //维持参数
        $this->display();
    }

    public function wk($date1) {
        $datearr = explode("-",$date1);     //将传来的时间使用“-”分割成数组
        $year = $datearr[0];       //获取年份
        $month = sprintf('%02d',$datearr[1]);  //获取月份
        $day = sprintf('%02d',$datearr[2]);      //获取日期
        $hour = $minute = $second = 0;   //默认时分秒均为0
        $dayofweek = mktime($hour,$minute,$second,$month,$day,$year);    //将时间转换成时间戳
        $shuchu = date("w",$dayofweek);      //获取星期值
        $weekarray=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
        return $weekarray[$shuchu];
    }

    public function getRoomInfo()
    {
        $sub_zone_id = I("request.sub_zone_id");
        $zone_id = I("request.zone_id");
        $where = [];
        if(!empty($sub_zone_id)){
            $where['sub_zone_id'] = $sub_zone_id;
        }
        elseif(!empty($zone_id)){
            $where['zone_id'] = $zone_id;
        }else{
            $this->ajaxReturn(['code'=>0,'msg'=>'缺少参数！']);
        }
        $data = ClassRoomService::getClassRoom($where);
        $this->ajaxReturn(['code'=>1,'msg'=>'success', 'data'=>$data['list']]);
    }


    /**
     * User: maChuang
     */
    public function excel()
    {
        //获取分校信息
        $zone    = new SchoolZoneService();
        $zones   = $zone->getSubZone();
        //获取全部教室
        $allRooms = ClassRoomService::getAllRoom();
        $this->assign('zones', $zones);
        $this->assign('allRooms', $allRooms);
        $this->display();
    }

    public function getExcel()
    {
        //接收参数
        $param = I('request.');
        //整理查询条件
        $classwhere = [];
        if(!empty($param['sub_zone_id'])){
            $classwhere['sub_zone_id'] = $param['sub_zone_id'];
        }
        if(!empty($param['zone_id'])){
            $classwhere['zone_id'] = $param['zone_id'];
        }
        //获得指定分校和校区条件下的班级class_id
        $classSelect = ClassService::getAll($classwhere);
        foreach($classSelect as $ro){  $ss[]=$ro['id'];  }
        //整理查询条件
        $where = [];
        if(!empty($ss)){
            $where['class_id'] = array('in',implode(',',$ss));
        }
        if(!empty($param['room_id'])){
            $where['room_id'] = $param['room_id'];
        }
        if(!empty($param['start_time']) && !empty($param['end_time']) ){
            $where['class_date'][] = array('egt',$param['start_time']);
            $where['class_date'][] = array('elt',$param['end_time']);
        }else{
            $this->error("请完善时间区间！");
        }

        //获得全部课表数据
        $data = (new ClassScheduleService())->getClassSchedule($where);
        //补充数据相关参数
        foreach ($data as $k=>$temp){
            foreach ($classSelect as $x=>$c) {
                    if($temp['class_id'] == $c['id']){
                        $data[$k]['class_name']     = $c['class_name'];
                        $data[$k]['zone_id']        = $c['zone_id'];
                        $data[$k]['zone_name']      = $c['zone_name'];
                        $data[$k]['sub_zone_id']    = $c['sub_zone_id'];
                        $data[$k]['sub_zone_name']  = $c['sub_zone_name'];
                    }
            }
        }
        //获得搜索条件的全部天数
        $dateEvery = $this->getEveryDayData($param['end_time'],$param['start_time']);
//        echo "<pre>";print_r($dateEvery);die;
        //按照日期对课表数据进行分组
        foreach ($dateEvery as $key => $value) {
            $dataByDate[$value] = [];
            foreach ($data as $dk =>$dy){
                if($dy['class_date'] == $value){
                    $dataByDate[$value][]=$dy;
                }
            }
        }
        return $this->mkExc($dataByDate,$dateEvery);
    }



    /**
     * 获得开始时间到结束时间的全部天数
     * User: maChuang
     * @param $end
     * @param $start
     */
    public function getEveryDayData($end,$start){
        $dateEvery = [];
        $betDay =(strtotime($end)-strtotime($start))/(24*3600);
        for($i=0;$i<=$betDay;$i++){
            $dateEvery[] = date("Y-m-d",(strtotime($start)+$i*24*3600));
        }
        return $dateEvery;
    }





    /**
     * phpexcel输出
     * User: maChuang
     * @param $datas
     * @param $dateEvery
     */
    public function mkExc($datas,$dateEvery){
        vendor("PHPExcel");
        Vendor("PHPExcel.PHPExcel.IOFactory");
        // 创建一个excel
        $objPHPExcel = new \PHPExcel();
        // 设置文件属性
        $objPHPExcel->getProperties()
            ->setCreator("xkht")
            ->setLastModifiedBy("xkht")
            ->setTitle("xkht-schedule")
            ->setSubject("schedule")
            ->setDescription("schedule-by-selected-date")
            ->setKeywords("schedule")
            ->setCategory("xkht");
        foreach ($dateEvery as $k=>$day){
            $column = \PHPExcel_Cell::stringFromColumnIndex($k);
            //设置当前活动的sheet
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($column.'1',$day);
            $excelColumn[$day] = $column; //日期的列号
        }
        //设置sheet名字
        $objPHPExcel->getActiveSheet()->setTitle('课表' . date('Y-m-d'));
        //将活动表索引设置为第一个表，因此Excel将其作为第一个表打开
        $objPHPExcel->setActiveSheetIndex(0);
        //设置默认行高
        $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(40);
        // 固定第一行
        $objPHPExcel->getActiveSheet()->freezePane('A2');
        //存入数据
        foreach($datas as $datakey=>$data){
            $lie = $excelColumn[$datakey];
            foreach($data as $j=>$temp){
                if(!empty($temp)){
                    $simpleData =(($temp['live_id'] != 0)?'直播':'线下课')." : ". $temp['zone_name']." , ".$temp['sub_zone_name']." , ".$temp['room_name']." , ".$temp['start_time']."-".$temp['end_time']." , ".$temp['tch_name']." , ".$temp['course_title'];
                    $objPHPExcel->getActiveSheet()->setCellValue($lie . (2+$j), $simpleData);
                }
            }
        }
        //设置单元格宽度
        foreach ($excelColumn as $ec){
            $objPHPExcel->getActiveSheet()->getColumnDimension($ec)->setWidth(25);
        }
        //定义文件名
        $filename = '课表'.$dateEvery[0].'-'.end($dateEvery);
        //清除缓冲区,避免乱码
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
        $objWriter->save('php://output');
    }



}






//                        if($temp['live_id'] != 0){
//                            //直播
//
//                        }else{
//                            //线下课
//
//                        }

//          将列的数字序号转成字母使用,代码如下:
//          PHPExcel_Cell::stringFromColumnIndex($i); // 从o开始
//          将列的字母转成数字序号使用,代码如下:
//          PHPExcel_Cell::columnIndexFromString('AA');

//        $zone_name = array_column($data, 'zone_name');
//        $sub_zone_name = array_column($data, 'sub_zone_name');
//        $room_name = array_column($data, 'room_name');
//        echo "<pre>";print_r($room_name);print_r($sub_zone_name);die;
//        //定义新的data数组
//        $checkData = [
//            'lesson'=>[
//                'zone_name'=>[
//                    'sub_zone_name'=>[
//                        'room_name'=>[
//
//                        ]
//                    ]
//                ]
//            ],
//            'live'=>[
//
//            ]
//
//        ];
//        foreach($zone_name as $zn){
//            $checkData[]=$zn;
//            foreach($sub_zone_name as $szn){
//
//            }
//        }

//        foreach ($data as $temp){
//           //课程分类（线下，直播）
//         if($temp['live_id'] == 0 && empty($temp['live_id'])){
//             //线下排课
//             if($temp['zone_name'] == 1){}
//         }else{
//             //直播课
//
//         }
//
//        }


//更新表数据
//$zone = M('school_zone')->select();
//$class = M('class')->select();
//foreach ($class as $c) {
//    foreach ($zone as $v) {
//        if($c['sub_zone_id'] == $v['id']){
//            M('class')->where(['id' =>$c['id']])->save(['sub_zone_name'=>$v['sz_name']]);
//        }
//    }
//}
//die;
