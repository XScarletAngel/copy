<?php
namespace ClassLesson\Controller;

use Common\Controller\AdminbaseController;
use Common\Model\ClassRoomModel;
use Common\Service\ClassNoticeService;
use Common\Service\ClassRoomService;
use Common\Service\SchoolZoneService;

class ClassRoomController extends AdminbaseController {

    /**
     * 教学点设置
     */
    public function index()
    {
//        echo '<pre>'; var_dump(I('request.'));die;
        $param = I('request.');
        $where = [];
        if(!empty($param['sub_zone_id'])){
            $where['sub_zone_id'] = $param['sub_zone_id'];
        }
        if(!empty($param['zone_id'])){
            $where['zone_id'] = $param['zone_id'];
        }
        //获取分校信息
        $class       = new ClassNoticeService();
        $school      = $class->zone();
        $this->assign('school', $school);  // 学校
        //获取全部教室
        $roomInfo = ClassRoomService::getClassRoom($where);
        $this->assign('data',$roomInfo['list']);// 赋值数据集
        $this->assign('page',$roomInfo['page']);// 赋值分页输出
        $this->assign('param', $param);
        $this->display();
    }

    /**
     * 获取相应的教室
     */
    public function getRoom()
    {
        $zone_id  = I('request.zone_id');     // 分校id
        $sub_zone_id = I('request.sub_zone_id');     // 校区id
        $where =[
            'zone_id'=>$zone_id,
            'sub_zone_id'=>$sub_zone_id
        ];
        $sResult = ClassRoomService::getClassRoom($where);
//        var_dump($sResult);
        $this->ajaxReturn(['code'=>0,'data'=>$sResult]);
    }

    public function create()
    {
        //获取分校信息
        $class       = new ClassNoticeService();
        $school      = $class->zone();
        $this->assign('school', $school);  // 学校
        $this->display();
    }

    public function store()
    {
//        echo '<pre>'; var_dump(I('request.'));die;
        $zone_id  = I('request.zone_id');                // 分校id
        $sub_zone_id  = I('request.sub_zone_id');        // 校区id
        $zone_name  = I('request.zone_name');            // 分校名称
        $sub_zone_name  = I('request.sub_zone_name');    // 校区名称
        $room_name  = I('request.room_name');            // 教室名称
        $room_addr  = I('request.room_addr');            // 教室地址
        $seat_num  = I('request.seat_num');              // 座位总数
        $rows  = I('request.rows');                      // 座位行数
        $columns  = I('request.columns');                // 座位列数
        $room_status  = I('request.status');             // 教室状态
        $is_default = 1;                                 // 1为教室
        if(empty($zone_id) || empty($zone_name) || empty($room_name) || empty($room_addr) || empty($seat_num) || empty($rows) || empty($columns) || empty($room_status)){
            $this->error("教师相关信息，请填写完整！");
        }
        $data = [
            'zone_id'=>$zone_id,
            'sub_zone_id'=>$sub_zone_id,
            'zone_name'=>$zone_name,
            'sub_zone_name'=>$sub_zone_name,
            'room_name'=>$room_name,
            'room_addr'=>$room_addr,
            'seat_num'=>$seat_num,
            'rows'=>$rows,
            'columns'=>$columns,
            'room_status'=>$room_status,
            'is_default'=>$is_default,
        ];
        $cr = new ClassRoomService();
        $res = $cr->addRoom($data);
        if($res){
            $this->success("添加教室成功!", U("ClassRoom/create"));
        }else{
            $this->error("添加教室失败!");
        }
    }

    public function edit()
    {
        $id  = I('request.id');                // 教室id
        $where = ['id'=>$id];
        $data = ClassRoomService::getClassRoom($where);
//        echo '<pre>'; print_r($data);die;
        $this->assign('data', $data['list']);
        //获取分校信息
        $class       = new ClassNoticeService();
        $school      = $class->zone();
        $this->assign('school', $school);  // 学校
//        echo '<pre>';print_r($data); print_r($school);die;
        $this->display();
    }


    public function update()
    {
        $zone_id  = I('request.zone_id');                // 分校id
        $sub_zone_id  = empty(I('request.sub_zone_id'))?0:I('request.sub_zone_id');        // 校区id
        $zone_name  = I('request.zone_name');            // 分校名称
        $sub_zone_name  = empty(I('request.sub_zone_name'))?'总校':I('request.sub_zone_name');    // 校区名称
        $room_name  = I('request.room_name');            // 教室名称
        $room_addr  = I('request.room_addr');            // 教室地址
        $seat_num  = I('request.seat_num');              // 座位总数
        $rows  = I('request.rows');                      // 座位行数
        $columns  = I('request.columns');                // 座位列数
        $room_status  = I('request.status');             // 教室状态
        $id = I('request.id');                           // 教室id
        if(empty($id) || empty($zone_id) || empty($zone_name) || empty($room_name) || empty($room_addr) || empty($seat_num) || empty($rows) || empty($columns) || empty($room_status)){
            $this->error("教室相关信息缺失，请填写完整！");
        }
        $data = [
            'zone_id'=>$zone_id,
            'sub_zone_id'=>$sub_zone_id,
            'zone_name'=>$zone_name,
            'sub_zone_name'=>$sub_zone_name,
            'room_name'=>$room_name,
            'room_addr'=>$room_addr,
            'seat_num'=>$seat_num,
            'rows'=>$rows,
            'columns'=>$columns,
            'room_status'=>$room_status,
            'id'=>$id,
        ];
//        echo '<pre>'; print_r($data);die;

        $cr = new ClassRoomService();
        $res = $cr->update($data);
        if($res){
            $this->success("更新教室信息成功!", U("ClassRoom/index"));
        }else{
            $this->error("更新失败，请稍后再试!");
        }
    }

    public function delete()
    {
        $id  = I('request.id');
        if (empty($id)) {
            $this->error("参数错误!");
        }
        $cr = new ClassRoomService();
        $is_del = $cr->delete($id);
        if (false !== $is_del) {
            $this->success("删除成功!", U("ClassRoom/index"));
        } else {
            $this->error("删除失败!");
        }

    }





}
