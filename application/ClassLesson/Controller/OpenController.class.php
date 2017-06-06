<?php

namespace ClassLesson\Controller;

use Common\Controller\AdminbaseController;
use Common\Model\ClassScheduleModel;
use Common\Model\UsersModel;
use Common\Service\ClassBookService;
use Common\Service\ClassNoticeService;
use Common\Service\ClassRoomService;
use Common\Service\ClassScheduleService;
use Common\Service\ClassService;
use Common\Service\CourseService;
use Common\Service\LiveService;
use Common\Service\QiNiuService;
use Common\Service\ScheduleService;
use Common\Service\SchoolService;
use Common\Service\SchoolZoneService;
use Common\Service\TeacherService;
use Common\Service\UsersService;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Think\Upload\Driver\Qiniu;

class OpenController extends AdminbaseController
{


    //开班管理首页

    public function show()
    {
        $param = I('request.');
        $where = [];
        if (!empty($param['sub_zone_id'])) {
            $where['sub_zone_id'] = $param['sub_zone_id'];
        }
        if (!empty($param['zone_id'])) {
            $where['zone_id'] = $param['zone_id'];
        }
        if (!empty($param['class_no'])) {
            $where['class_no'] = $param['class_no'];
        }
        if (!empty($param['class_name'])) {
            $where['class_name'] = $param['class_name'];
        }
        if (!empty($param['class_type'])) {
            $where['class_type'] = $param['class_type'];
        }
        if (!empty($param['class_master'])) {
            $where['class_master'] = $param['class_master'];
        }
        if (!empty($param['start_time'])) {
            $where['add_time'][] = ['egt', $param['start_time']];
        }
        if (!empty($param['end_time'])) {
            $where['add_time'][] = ['elt', $param['end_time']];
        }
//        echo "<pre>"; var_dump($param);exit;
        //获取分校信息
        $class       = new ClassNoticeService();
        $school      = $class->zone();
        $this->assign('school', $school);  // 学校
        //获取开班的全部信息
        $data = ClassService::findClass($where);
        $this->assign('data', $data['list']);
        $this->assign('page', $data['page']);
        $this->assign('param', $param);
//        echo "<pre>"; var_dump($data['list']);exit;
        $this->display();
    }

    //更改班级开班状态
    public function chargeStatus()
    {
        $param = I('request.');
        $c = new ClassService();
        $c->update(['id' => $param['id']], ['class_status' => $param['class_status']]);
    }

    //修改班级信息(页面)
    public function backIndex()
    {
        //数据验证
        $id = I('request.class_id');
        if (empty($id)) return false;

        //获取分校信息
        $zone = new SchoolZoneService();
        $zones = $zone->getSubZone();

        //获取课程信息
        $course = new CourseService();
        $courses = $course->getCourse();

        //获取全部教师
        $t = new TeacherService();
        $teachers = $t->select([],'id,user_id,user_name');

        //获取班级信息
        $data = ClassService::find($id);
        $zn = $zone->getZoneById($data['zone_id']);
        //获取校区名称
        $data['zone_name'] = $zn['sz_name'];
        $subN = $zone->getZoneById($data['sub_zone_id']);
        //获取校区分区名称
        $data['sub_zone_name'] = $subN['sz_name'];
        //解析答疑组
        $data['answer_group'] = explode(',', $data['answer_group']);
//        var_dump($data);die;
        $this->assign('classInfo', $data);
        $this->assign('zones', $zones);
        $this->assign('courses', $courses);
        $this->assign('teachers', $teachers);
//        var_dump($teachers);die;
        $this->display();
    }

    //修改班级信息(页面)
    public function edit()
    {
        //数据验证
        $id = I('request.id');
        if (empty($id)) return false;

        //获取分校信息
        $zone = new SchoolZoneService();
        $zones = $zone->getSubZone();

        //获取课程信息
        $course = new CourseService();
        $courses = $course->getCourse();

        //获取全部教师
        $t = new TeacherService();
        $teachers = $t->select(['sign_status'=>1],'id,user_id,user_name');


        //获取班级信息
        $data = ClassService::find($id);
        $zn = $zone->getZoneById($data['zone_id']);
        //获取校区名称
        $data['zone_name'] = $zn['sz_name'];
        $subN = $zone->getZoneById($data['sub_zone_id']);
        //获取校区分区名称
        $data['sub_zone_name'] = $subN['sz_name'];
        //解析答疑组
        $data['answer_group'] = explode(',', $data['answer_group']);
//        var_dump($data);die;
        $this->assign('classInfo', $data);
        $this->assign('zones', $zones);
        $this->assign('courses', $courses);
        $this->assign('teachers', $teachers);
//        var_dump($teachers);die;
        $this->display();
    }


    //显示班级信息(页面)
    public function getInfo()
    {
        //数据验证
        $id = I('request.id');
        if (empty($id)) return false;

        //获取分校信息
        $zone = new SchoolZoneService();
        $zones = $zone->getSubZone();

        //获取课程信息
        $course = new CourseService();
        $courses = $course->getCourse();

//        //获取全部教师
//        $tc = new UsersService();
//        $teachers = $tc->getUsers(['user_type' => 'teacher']);
        //获取全部教师
        $t = new TeacherService();
        $teachers = $t->select(['sign_status'=>1],'id,user_id,user_name');

        //获取班级信息
        $data = ClassService::find($id);
        $zn = $zone->getZoneById($data['zone_id']);
        //获取校区名称
        $data['zone_name'] = $zn['sz_name'];
        $subN = $zone->getZoneById($data['sub_zone_id']);
        //获取校区分区名称
        $data['sub_zone_name'] = $subN['sz_name'];
        //解析答疑组
        $data['answer_group'] = explode(',', $data['answer_group']);
//        var_dump($data);die;
        $this->assign('classInfo', $data);
        $this->assign('zones', $zones);
        $this->assign('courses', $courses);
        $this->assign('teachers', $teachers);
//        var_dump($teachers);die;
//        echo "222";die;
        $this->display("get");
    }

    //修改班级信息 POST处理表单
    public function update()
    {
        $file = $_FILES["filess"];
//        echo "<pre>";print_r(I('request.')); die;
//        var_dump($file); die;
        if (!empty($file['name'])) {
            $name = QiNiuService::qiniuUpload($_FILES, 'filess', 'xkht-img');
            $class_cover = $name;
        } else {
            $class_cover = I('request.class_cover');
        }
        $param = I('request.');
        $param['class_cover'] = $class_cover;
        $param['answer_group'] = implode(',', $param['answer_group']);
//        echo "<pre>"; var_dump($param); die;
        $class = D("Class"); // 实例化
        if (!$class->create($param, 1)) { // 指定新增数据
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            $this->error($class->getError());
        } else {

            $class = new ClassService();
            $class->update(['id' => $param['id']], $param);
//            echo "<pre>"; var_dump($res); die;
//                $this->redirect('Open/show');
            if (empty($param['backIndex'])) {
                $this->redirect('Open/show'); //没有回退标志位，则跳转到列表页
            } else {
                $class_type = I('post.class_type');     //有回退标志位，则跳转到创建第二步页面
                if ($class_type == 1) {
                    $url = "Open/secondOffline";
                } elseif ($class_type == 2) {
                    $url = "Open/secondOnline";
                } elseif ($class_type == 3) {
                    $url = "Open/third";
                }
                $this->redirect($url, array('class_id' => $param['id']));
            }
        }

    }


    /**
     * 创建班级第一步（页面）
     */
    public function index()
    {
        $c = I('request.class_id');
        if (!empty($c)) {
            $data = ClassService::find(I('request.class_id'));
            $this->assign('classInfo', $data);
        }
        //获取课程信息
        $course = new CourseService();
        $courses = $course->getCourse();
        //获取全部教师
        $t = new TeacherService();
        $teachers = $t->select(['sign_status'=>1],'id,user_id,user_name');
//        var_dump($teachers);die;
        $class       = new ClassNoticeService();
        $school      = $class->zone();
        $this->assign('school', $school);  // 学校
//        $this->assign('zones', $zones);
        $this->assign('courses', $courses);
        $this->assign('teachers', $teachers);
        $this->display();
    }

    public function getQiNiuToken()
    {
        $token = QiNiuService::getToken(QiNiuService::BUCKET_XKHT_IMG);
        $this->ajaxReturn(['uptoken' => $token]);
    }

    /**
     * 创建班级第一步  POST提交表单
     */
    public function store()
    {

        $file = $_FILES["filess"];
//        echo "<pre>";print_r(I('request.')); die;
//        var_dump($file); die;
        if (!empty($file['name'])) {
            $name = QiNiuService::qiniuUpload($_FILES, 'filess', 'xkht-img');
            $class_cover = $name;
        } else {
            $class_cover = '';
        }
        $class = D("Class");
        $param = I('request.');
//             echo "<pre>";print_r($class_cover); die;
        $param['class_cover'] = $class_cover;
        if (!$class->create($param, 1)) { // 指定新增数据
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            $this->error($class->getError());
        } else {
            $class = new ClassService();
            $id = $class->addData($param);
            // 验证通过 可以进行其他数据操作
            $class_type = I('post.class_type');      // * 班级类型
            if ($class_type == 1) {
//                $url = "Open/secondOffline";
                $url = 'index.php?g=ClassLesson&m=Open&a=secondOffline&class_id='.$id;
            } elseif ($class_type == 2) {
//                $url = "Open/secondOnline";
                $url = 'index.php?g=ClassLesson&m=Open&a=secondOnline&class_id='.$id;
            } elseif ($class_type == 3) {
//                $url = "Open/third";
                $url = 'index.php?g=ClassLesson&m=Open&a=third&class_id='.$id;
            }
//            exit(json_encode(['referer'=>$url]));
            $this->redirect($url);
        }
    }


    //创建班级第二步  分 线上 线下 跳转页面
    public function second()
    {
        $class_id = I('request.class_id');
        $res = ClassService::find($class_id);
        $class_type = $res['class_type'];
        if ($class_type == 1) {
            $url = "Open/secondOffline";
        } elseif ($class_type == 2) {
            $url = "Open/secondOnline";
        } elseif ($class_type == 3) {
            $url = "Open/third";
        }
        $this->redirect($url, array('class_id' => $class_id));
    }


    //创建班级第二步（线下班）页面
    public function secondOffline()
    {
        // * 班级id
        $class_id = I('request.class_id');
        $this->assign('class_id', $class_id);
        //获取课表
        $scheduleData = ScheduleService::getScheduleByPage(['class_id' => $class_id]);
        $this->assign('data', $scheduleData['list']);// 赋值数据集
        $this->assign('page', $scheduleData['page']);// 赋值分页输出
        $this->display();
    }

    //创建班级第二步（线下班）   添加课次弹窗页面
    public function addLesson()
    {
        $class_id = I('request.class_id');
        $data = ClassService::find($class_id);
        $this->assign('data', $data);
//        echo "<pre>";  print_r($data); die;
        $this->assign('class_id', $class_id);
        $this->display();
    }


    //创建班级第二步（线下班）   添加课次弹窗页面
    public function addLive()
    {
        $request = I('request.');
        $where = [];
        if (!empty($request['channel_title'])) {
            $where['channel_title'] = $request['channel_title'];
        }
        if (!empty($request['live_teacher'])) {
            $where['live_teacher'] = $request['live_teacher'];
        }
        if (!empty($param['begin_time'])) {
            $where['begin_time'] = array('egt', $param['begin_time']);
        } else {
//            $where['begin_time'] = array('egt',time());
        }
        if (!empty($param['end_time'])) {
            $where['end_time'] = array('elt', $param['end_time']);
        }

        $lives = LiveService::getInfoByPage($where);
//        echo "<pre>";  print_r($lives);  die;
        //class_id传递到html模板
        $this->assign('class_id', I('request.class_id'));
        //维持参数
        $this->assign('req', $request);
        foreach ($lives['data'] as $i => $t) {
            $lives['data'][$i]['hour_num'] = ceil((strtotime($t['end_time']) - strtotime($t['begin_time'])) / (45 * 60));
        }
        //live直播传递到模板
        $this->assign('data', $lives['data']);// 赋值数据集
        $this->assign('page', $lives['page']);// 赋值分页输出

        $this->display();
    }

    public function createLiveOnline()
    {
        $request = I('request.');
        if (empty($request['live_id'])) echo "<script>parent.location.reload();</script>";
        $class_id = $request['class_id'];
        foreach ($request['live_id'] as $v) {
            $live = M('live_channels')->find($v);
            $in = [
                'class_id' => $class_id,
                'tch_id' => $live['live_tch_id'],
                'tch_name' => $live['live_teacher'],
                'class_date' => date("Y-m-d", strtotime($live['begin_time'])),
                'start_time' => date("H:i:s", strtotime($live['begin_time'])),
                'end_time' => date("H:i:s", strtotime($live['end_time'])),
                'hour_num' => ceil((strtotime($live['end_time']) - strtotime($live['begin_time'])) / (45 * 60)),
                'course_title' => $live['channel_title'],
                'sign_code' => rand(1000, 9999),
                'live_id' => $live['id'],

            ];
            (new ClassScheduleService())->addData($in);
        }
//      echo "<pre>";  var_dump($live); die;
        echo "<script>parent.location.reload();</script>";
    }


    public function delLiveOnline()
    {
        $schedule_id = I('request.schedule_id');
//        echo "<pre>";  var_dump($schedule_id); die;
        (new ClassScheduleService())->delete($schedule_id);
        exit();
    }

    //创建班级第二步（线下班）   提交表单
    public function offlinePost()
    {
        $request = I('request.');
//        echo "<pre>";  var_dump($request); die;
        //可用的天数
        $days = [];
        foreach ($request['start_time'] as $k => $startDay) {
            if (!empty($startDay)) {
                //转换成unix时间戳
                $startDay = strtotime($startDay);
                $request['end_time'][$k] = strtotime($request['end_time'][$k]);
                // 计算日期段内有多少天
                $mo = ($request['end_time'][$k] - $startDay) / 86400 + 1;
                for ($i = 0; $i < $mo; $i++) {
                    $days[] = date('Y-m-d', $startDay + (86400 * $i));
                }
            }
        }
//        echo "<pre>";  var_dump($days);die;
        //每周的周几上课进行筛选
        foreach ($days as $key => $day) {
            if (!in_array(date("w", strtotime($day)), $request['weekCheck'])) {
                unset($days[$key]);
            }
        }
        $lessonTime = [];
        //每天上课时间
        foreach ($request['lesson_start_hour'] as $hk => $hour) {
            $lessonTime[$hk]['startTime'] = $hour . ":" . $request['lesson_start_minutes'][$hk] . ":00";
            $lessonTime[$hk]['endTime'] = $request['lesson_end_hour'][$hk] . ":" . $request['lesson_end_minutes'][$hk] . ":00";
            $lessonTime[$hk]['hour_num'] = ceil((60 * ($request['lesson_end_hour'][$hk] - $hour) + $request['lesson_end_minutes'][$hk] - $request['lesson_start_minutes'][$hk]) / 45);
        }

//                echo "<pre>";  var_dump($days,$lessonTime);die;
        $classInfo = ClassService::find($request['class_id']);

        foreach ($days as $insertDay) {
            foreach ($lessonTime as $insertTime) {
                $classInfo['room_addr'] = empty($request['room_addr']) ? '' : $request['room_addr'];
                $course_no = time() . $insertDay . $insertTime['startTime'];
                $param = [
                    'class_id' => $request['class_id'],
                    'tch_id' => $classInfo['tch_id'],
                    'tch_name' => $classInfo['tch_name'],
                    'room_id' => $classInfo['room_id'],
                    'room_name' => $classInfo['room_name'],
                    'room_addr' => $classInfo['room_addr'],
                    'class_date' => $insertDay,
                    'start_time' => $insertTime['startTime'],
                    'end_time' => $insertTime['endTime'],
                    'hour_num' => $insertTime['hour_num'],
                    'add_time' => date("Y-m-d H:i:s", time()),
                    'upd_time' => date("Y-m-d H:i:s", time()),
                    'sign_code' => rand(1000, 9999),
                    'title' => '',
                    'course_no' => $course_no,
                ];
                ScheduleService::addData($param);
            }
        }
        echo "<script>parent.location.reload();</script>";

    }

    //创建班级第二步（线下班） 表单中删除课次
    public function delLesson()
    {
        $lesson_id = I('request.lesson_id');
        if (empty($lesson_id)) return false;
        (new SchoolService())->delLesson($lesson_id);
        $this->ajaxReturn(['code' => 1]);
    }

    //创建班级第二步（线下班） 表单中修改课次
    public function editLesson()
    {
        $lesson_id = I('request.lesson_id');
        if (empty($lesson_id)) return false;
        //获取该课次的数据
        $data = (new ClassScheduleService())->find($lesson_id);
        //分割开始时间和结束时间
        $data['start_time'] = explode(':', $data['start_time']);
        $data['end_time'] = explode(':', $data['end_time']);
        $data['lesson_id'] = $lesson_id;
        //获取全部教室
        $rooms = ClassRoomService::getAllRoom();
        //获取全部教师
        $tc = new UsersService();
        $teachers = $tc->getUsers(['user_type' => 'teacher']);
        $this->assign('data', $data);
        $this->assign('rooms', $rooms);
        $this->assign('teachers', $teachers);
//        echo "<pre>";  print_r($data);die;
        $this->display();
    }

    //创建班级第二步（线下班） 表单中更新课次信息
    public function updateLesson()
    {
        $param = I('request.');

        //整理参数
        if (!empty($param['room_addr'])) {
            $updateData['room_addr'] = $param['room_addr'];
        }
        if (!empty($param['room_id'])) {
            $updateData['room_id'] = $param['room_id'];
        }
        $updateData['course_title'] = $param['course_title'];
        $updateData['course_no'] = $param['course_no'];
        $updateData['class_date'] = $param['class_date'];
        $updateData['tch_id'] = $param['tch_id'];
        $updateData['tch_name'] = $param['tch_name'];
        $updateData['room_name'] = $param['room_name'];
        $updateData['start_time'] = $param['lesson_start_hour'] . ":" . $param['lesson_start_minutes'] . ":00";
        $updateData['end_time'] = $param['lesson_end_hour'] . ":" . $param['lesson_end_minutes'] . ":00";
        //条件
        $where = ['id' => $param['lesson_id']];
//            echo "<pre>";  print_r($param);die;
        $res = (new ClassScheduleService())->update($where, $updateData);

        if ($res) {
            echo "<script>parent.location.reload();</script>";
        } else {
            echo "<script>alert('信息没有更新，请重试！');parent.location.reload();</script>";
        }
    }


    /**
     * 创建班级第二步（线上班）
     */
    public function secondOnline()
    {
        // * 班级id
        $class_id = I('request.class_id');
        $this->assign('class_id', $class_id);
        //获取课表
        $scheduleData = ScheduleService::getScheduleByPage(['class_id' => $class_id]);
        $this->assign('data', $scheduleData['list']);// 赋值数据集
        $this->assign('page', $scheduleData['page']);// 赋值分页输出
        $this->display();
    }

    //创建班级第三步（资料管理）页面
    public function third()
    {
        $class_id = I('request.class_id');
        $books = ClassBookService::findClassBook(['class_id' => $class_id]);
        $this->assign('class_id', $class_id);
        $this->assign('books', $books['list']);// 赋值数据集
        $this->assign('page', $books['page']);// 赋值分页输出
        $this->display();
    }

    //创建班级第三步 添加资料弹框页面
    public function addBook()
    {
        $class_id = I('request.class_id');
        $this->assign('class_id', $class_id);
        $this->display();
    }


    //创建班级第三步 添加资料  提交表单
    public function addBookPost()
    {
        $book_no = time() . I('request.class_id');
        $param = [
            'class_id' => I('request.class_id'),
            'book_name' => I('request.book_name'),
            'book_status' => I('request.book_status'),
            'book_no' => $book_no
        ];
        $res = ClassBookService::addData($param);
        if ($res) {
            echo "<script>parent.location.reload();</script>";
        } else {
            echo "<script>alert('添加失败，请重试！');parent.location.reload();</script>";
        }
    }

    //创建班级第三步 修改资料页面
    public function editBook()
    {
        $id = I('request.book_id');
        $Bookdata = ClassBookService::find($id);
//        var_dump($Bookdata); die;
        $this->assign('Bookdata', $Bookdata);
        $this->display();
    }


    //创建班级第三步 修改资料提交表单
    public function editBookPost()
    {
        $param = [
            'book_name' => I('request.book_name'),
            'book_status' => I('request.book_status'),
        ];
        $where = ['id' => I('request.id')];
        ClassBookService::update($where, $param);
        echo "<script>parent.location.reload();</script>";

    }

    //创建班级第三步    ajax更改状态
    public function chargeBookStatus()
    {
        $id = I('request.book_id');
        $info = ClassBookService::find($id);
        if ($info['book_status'] == 1) {
            $res = ClassBookService::update(['id' => $id], ['book_status' => 2]);
        } else {
            $res = ClassBookService::update(['id' => $id], ['book_status' => 1]);
        }
        if ($res) {
            return true;
        }
        return false;
    }


    /**
     * 查询分校下属所有校区
     */
    public function getSubZone()
    {
        $id = I('request.zone_id');     // 学校id
        $zone = new SchoolZoneService();   //实例化分区类
        $subZones = $zone->getSubZone($id);
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($subZones));
//        $this->ajaxReturn(array_values($dept));
    }

    //获取某分校校区下的全部教室
    public function getRoom()
    {
        $c = I('request.zone_id');
        if (empty($c)) return false;
        $where = [];
        $rr = I('request.zone_id');
        if (!empty($rr)) {
            $where['zone_id'] = I('request.zone_id');
        }
        $sub_zone_id =  I('request.sub_zone_id');
        if (!empty($sub_zone_id)) {
            $where['sub_zone_id'] = I('request.sub_zone_id');
        }

        $r = new ClassRoomService();
        $rooms = $r->getAllRoom($where);
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($rooms));
    }

    //修改班级排课信息
    public function editClassLesson()
    {
        $class_id = I('request.id');
        $this->assign('class_id', $class_id);
        //获取课表
        $scheduleData = ScheduleService::getScheduleByPage(['class_id' => $class_id]);
        $this->assign('data', $scheduleData['list']);// 赋值数据集
        $this->assign('page', $scheduleData['page']);// 赋值分页输出
        $this->display();
    }


}


