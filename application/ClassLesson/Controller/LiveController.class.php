<?php
namespace ClassLesson\Controller;

/**
 * 直播管理
 * @author lixiuran
 */

use Common\Controller\AdminbaseController;
use Common\Service\ChannelsService;
use Common\Service\ClassNoticeService;
use Common\Service\ClassScheduleService;
use Common\Service\ClassService;
use Common\Service\LiveService;
use Common\Service\QiNiuService;
use Common\Service\SchoolZoneService;
use Common\Service\TeacherService;

class LiveController extends AdminbaseController
{

    /**
     * 直播列表页面
     */
    public function index()
    {

        $param = I('request.');
        $where = ['is_del'=>0];

        if(!empty($param['zone_id'])){
            $where['zone_id'] = $param['zone_id'];
        }
        if(!empty($param['sub_zone_id'])){
            $where['sub_zone_id'] = $param['sub_zone_id'];
        }
        if(!empty($param['class_id'])){
            $where['class_id'] = $param['class_id'];
        }
        if(!empty($param['live_status'])){
            $where['live_status'] = $param['live_status'];
        }
        if(!empty($param['chnannel_title'])){
            $where['chnannel_title'] = $param['chnannel_title'];
        }
        if(!empty($param['begin_time'])){
            $where['begin_time'][] = ['egt',$param['begin_time']];
        }
        if(!empty($param['end_time'])){
            $where['end_time'][] = ['elt',$param['end_time']];
        }
        if(!empty($param['chnanel_status'])){
            $where['chnanel_status'] = $param['chnanel_status'];
        }

        if(!empty($param['channel_title'])){
            $where['channel_title'] = ["like","%".trim($param['channel_title'])."%"];
        }
        //获取分校信息
        $class       = new ClassNoticeService();
        $school      = $class->zone();
        $this->assign('school', $school);  // 学校
//        //获取分校信息
//        $zones   = (new SchoolZoneService())->getSubZone();

        //获取开班的全部信息
        $data = (new LiveService())->queryByPage($where);

//        $this->assign('zones', $zones);
        $this->assign('data', $data['data']);
        $this->assign('page', $data['page']);
        $this->assign('param', $param);
        $this->display();
    }

    public function ajaxGetLiveClass()
    {
        $kw = I("request.kw", '');
        $map = ['class_status'=>1,'class_type'=>2];
        if (empty($kw)) {
            $not_open_class = M("class")->field("id,class_name")->where($map)->order("id desc")->limit(10)->select();
        } else {
            $map = array_merge($map, ['class_name'=>['like',"%$kw%"]]);
            $not_open_class = M("class")->field("id,class_name")->where($map)->order("id desc")->select();
        }
        if (!empty($not_open_class)) {
            foreach ($not_open_class as $k=>$v) {
                $not_open_class[$k]['class_name'] = str_replace($kw, '<b color="red">'.$kw.'</b>', $v['class_name']);
            }
        }
        $this->ajaxOk('success',$not_open_class);
    }

    /**
     * 直播添加页面
     */
    public function add()
    {

        $sch = (new TeacherService())->getSignedTeacher();
        $not_open_class = M("class")->field("id,class_name")->where(['class_status'=>1,'class_type'=>2])->select();

        $this->assign("not_open_class", $not_open_class);
        $this->assign("sch", $sch);
        $this->display();
    }

    public function addPost()
    {
        // 1.创建频道 2.拿到网易云通信ID 3.创建聊天室 4.获取聊天室地址
        $d['channel_title'] = I("post.channel_title", '');
        $d['channel_desc']  = I("post.channel_desc", '');
        $d['begin_time']    = I("post.begin_time", '');
        $d['end_time']      = I("post.end_time", '');
        $d['live_tch_id']   = I("post.live_tch_id", '');
        $d['live_teacher']  = I("post.live_teacher", '');

        if (strtotime($d['begin_time']) < time()) {
            $this->error("直播开始时间必须大于当前时间!");
        }

        if ( (strtotime($d['end_time']) - strtotime($d['begin_time'])) <= 0) {
            $this->error("直播结束时间不正确,需大于开始时间");
        }

        /***文件上传**/
        $d['attach_file'] = QiNiuService::qiniuUpload($_FILES, 'attach_file');
        if (strrpos($d['attach_file'], '.pdf') === false) {
            $this->error("直播附件必须为pdf格式!");
        }

        //班级
        $class_id   = I("post.class_id", '');
        $class_name = I("post.class_name", '');

        if (empty($d['channel_title'])) {
            $this->error("直播间标题必填");
        }

        // 通过教师的id把网易云通信ID拿到
        $user_obj = M("users");
        $tea_obj  = M("teacher");
        $tea  = $tea_obj->where(["id"=>$d['live_tch_id']])->find();
        $user_info = $user_obj->where(["id"=>$tea['user_id']])->find();
        if (empty($user_info['im_accid'])) {
            // 创建一个网易云信ID，账号作为accid，网易云通信ID昵称
            $ret = (new ChannelsService())->createID($user_info['id'], hide_phone($user_info['user_login']));
            if (200 == $ret['code']) {
                $u['im_accid'] = $d['im_creator'] = $ret['info']['accid'];
                $u['im_token'] = $ret['info']['token'];
                $u['im_name']  = $ret['info']['name'];
                $user_res = $user_obj->where(["id"=>$user_info['id']])->save($u);
            } else {
                $this->error("网易云通信ID创建失败");
            }
        } else {
            // 直接使用已经有的，users表里面的
            $d['im_creator'] = $user_info['im_accid'];
        }

        //$this->debug($d);die;

        // 直播间添加数据
        if (empty($class_id)) {  // 没有班级
            $pk_id   = (new LiveService())->addItem($d);  // 添加直播基础数据，返回主键ID
            $two_res = $this->changeChannel($pk_id);  // 添加直播第三方数据，推流拉流地址
            if ($two_res){
                // 创建聊天室    数据库里面有数据
                $room = (new ChannelsService())->creatRoom($d['channel_title'], $d['channel_desc'], $two_res['rtmp_pull_url'], $d['im_accid']);

                if (200 == $room['code']) {
                    // 创建的roomid
                    $room_data['im_roomid'] = $room['chatroom']['roomid'];
                } else {
                    $this->error("聊天室创建失败");
                }

                // 获取聊天室地址
                $address = (new ChannelsService())->getAddress($room_data['im_roomid'], $d['im_creator']);
                if (200 == $address['code']) {
                    $room_data['im_addr'] = json_encode($address['addr']);  // 获取room地址
                } else {
                    $this->error("获取聊天室地址失败");
                }
                $newchannels = M("live_channels")->where(['id'=>$pk_id])->save($room_data);
                if ($newchannels) {
                    $this->success("直播创建成功!");
                } else {
                    $this->success("直播创建成功,第三方创建失败!");
                }
            } else {
                $this->success("直播创建成功,第三方创建失败!");
            }
        } else {
            //1. 为多个班级创建直播,首先获取一个直播间
            $third_info = (new ChannelsService())->createChannel();
            if (200 == $third_info['code']) {
                $d2 = [
                    'cid' => $third_info['ret']['cid'],
                    'ctime' => $third_info['ret']['ctime'],
                    'channel_name' => $third_info['ret']['name'],
                    'push_url' => $third_info['ret']['pushUrl'],
                    'rtmp_pull_url' => $third_info['ret']['rtmpPullUrl'],
                    'http_pull_url' => $third_info['ret']['httpPullUrl'],
                    'hls_pull_url' => $third_info['ret']['hlsPullUrl'],
                ];
               $d = array_merge($d, $d2);
            } else {
                $this->error("第三方创建直播失败!");
            }

            // 创建聊天室，现在数据库还没有数据，直接放到$d里面
            $room = (new ChannelsService())->creatRoom($d['channel_title'], $d['channel_desc'], $third_info['ret']['rtmpPullUrl'], $d['im_creator']);
            if(200 == $room['code']){
                // 创建的roomid
                $d['im_roomid'] = $room['chatroom']['roomid'];
            } else {
                $this->error("聊天室创建失败");
            }

            // 获取聊天室地址
            $address = (new ChannelsService())->getAddress($room['chatroom']['roomid'], $d['im_creator']);
            if (200 == $address['code']) {
                // 获取room地址
                $d['im_addr'] = json_encode($address['addr']);
            } else {
                $this->error("获取聊天室地址失败");
            }

            // 批量写入数据库
            $multi_arr = [];
            foreach ($class_id as $k=>$v) {
                $d['class_id'] = $v;
                $d['class_name'] = $class_name[$k];
                $multi_arr[] = $d;
            }
            //$int = (new LiveService())->addMultiItem($multi_arr);

            M()->startTrans();
            foreach ($multi_arr as $k=>$v) {
                //获取班级所属的校区和分校
                $cur_class = (new ClassService())->find($v['class_id']);
                $v['zone_id']       =  $cur_class['zone_id'];
                $v['sub_zone_id']   =  $cur_class['sub_zone_id'];
                $v['zone_name']     =  $cur_class['zone_name'];
                $v['sub_zone_name'] =  $cur_class['sub_zone_name'];
                $pk_id = (new LiveService())->addItem($v);

                if (!$pk_id) {
                    $bool_back = true;
                }
                $classSchdule = [
                    'class_id'=>$v['class_id'],
                    'tch_id'=>$v['live_tch_id'],
                    'tch_name'=>$v['live_teacher'],
                    'class_date'=>date("Y-m-d",strtotime($v['begin_time'])),
                    'start_time'=>end(explode(' ',$v['begin_time'])),
                    'end_time'=>end(explode(' ',$v['end_time'])),
                    'hour_num'=>round((strtotime($v['end_time']) - strtotime($v['begin_time']))/60, 2),
                    'course_title'=>$v['channel_title'],
                    'live_id'=>$pk_id,
                ];
                $pk_id2 = (new ClassScheduleService())->addData($classSchdule);
                if (!$pk_id2) {
                    $bool_back = true;
                }
            }
            if ($bool_back) {
                M()->rollback();
                $this->error("创建失败~~~");
            } else {
                M()->commit();
                $this->error("创建直播成功,创建课表成功!");
            }

        }
    }


    /**
     * 直播添加页面
     */
    public function edit()
    {
        $id = I("request.id", 0);
        if (empty($id)) $this->error("参数错误!");
        $item = (new LiveService())->getItemById($id);
        $sch  = (new TeacherService())->getSignedTeacher();

        $this->assign("sch", $sch);
        $this->assign("item", $item);
        $this->display();
    }

    /**
     * 直播添加页面
     */
    public function preview()
    {
        $id = I("request.id", 0);
        if (empty($id)) $this->error("参数错误!");
        $item = (new LiveService())->getItemById($id);
        $sch  = (new TeacherService())->getSignedTeacher();

        $this->assign("sch", $sch);
        $this->assign("item", $item);
        $this->display();
    }

    public function editPost()
    {
        $d['id']            = I("post.id", '');
        $d['channel_title'] = I("post.channel_title", '');
        $d['channel_desc']  = I("post.channel_desc", '');
        $d['begin_time']    = I("post.begin_time", '');
        $d['end_time']      = I("post.end_time", '');
        $d['live_tch_id']   = I("post.live_tch_id", '');
        $d['live_teacher']  = I("post.live_teacher", '');

        if (empty($d['id']) || !is_numeric($d['id'])) {
            $this->error("参数错误!");
        }
        if (empty($d['channel_title'])) {
            $this->error("直播间标题必填");
        }
        if (strtotime($d['begin_time']) < time()) {
            $this->error("直播开始时间必须大于当前时间!");
        }
        if ( (strtotime($d['end_time']) - strtotime($d['begin_time'])) <= 0) {
            $this->error("直播结束时间不正确,需大于开始时间");
        }

        /***文件上传**/
        if ($_FILES['attach_file']) {
            $d['attach_file'] = QiNiuService::qiniuUpload($_FILES, 'attach_file');
            if (strrpos($d['attach_file'], '.pdf') === false) {
                $this->error("直播附件必须为pdf格式!");
            }
        }

        //班级
        //$class_id   = I("post.class_id", '');
        //$class_name = I("post.class_name", '');

        //先修改直播,然后修改课表
        M()->startTrans();
        $upd_live = (new LiveService())->saveItem($d);

        $classSchdule = [
            'tch_id'=>$d['live_tch_id'],
            'tch_name'=>$d['live_teacher'],
            'class_date'=>date("Y-m-d",strtotime($d['begin_time'])),
            'start_time'=>end(explode(' ',$d['begin_time'])),
            'end_time'=>end(explode(' ',$d['end_time'])),
            'hour_num'=>round((strtotime($d['end_time']) - strtotime($d['begin_time']))/60, 2),
            'course_title'=>$d['channel_title'],
        ];
        $upd_schdule = (new ClassScheduleService())->update(['live_id'=>$d['id']],$classSchdule);

        if (false !== $upd_live && false !== $upd_schdule) {
            M()->commit();
            $this->success("修改直播及课表成功!");
        } else {
            M()->rollback();
            $this->success("修改失败!");
        }
    }

    /**
     * 请求第三方,修改直播信息
     *
     * @param $pk_id
     * @return array
     */
    public function changeChannel($pk_id)
    {
        if (empty($pk_id)) {
            return [];
        }
        //调用第三方接口创建直播,并返回地址
        $third_info = (new ChannelsService())->createChannel();
        if (200 == $third_info['code']) {
            $save_data = [
                'id' => $pk_id,
                'cid' => $third_info['ret']['cid'],
                'ctime' => $third_info['ret']['ctime'],
                'channel_name' => $third_info['ret']['channelName'],
                'push_url' => $third_info['ret']['pushUrl'],
                'rtmp_pull_url' => $third_info['ret']['rtmpPullUrl'],
                'http_pull_url' => $third_info['ret']['httpPullUrl'],
                'hls_pull_url' => $third_info['ret']['hlsPullUrl'],
            ];
            $bool_save = (new LiveService())->saveItem($save_data);
            if ($bool_save) return $save_data;
        }
        return [];
    }

    /**
     * 直播发布
     */
    public function release()
    {
        $id = I("request.id");
        if (empty($id)) $this->ajaxError("参数错误");

        $item = (new LiveService())->getItemById($id);
        if (strtotime($item['begin_time']) < time()) {
            $this->ajaxError("当天时间大于直播开始时间");
        }
        if ($item['live_status'] != 0) {
            $this->ajaxError("只有未发布的直播才可以发布");
        }

        $release_st = (new LiveService())->saveItem(['id'=>$id, 'live_status'=>1]);
        if (false !== $release_st) {
            $this->ajaxOk("发布成功!");
        } else {
            $this->ajaxError("发布失败!");
        }
    }

    /**
     * 查看直播地址
     */
    public function address()
    {
        $id = I("request.id", 0);
        if (empty($id)) $this->error("参数错误");
        $item = (new LiveService())->getItemById($id);

        $this->assign("item", $item);
        $this->display();
    }

    /**
     * 设置录制状态
     */
    public function changeRecord()
    {
        $cid         = I("request.cid", 0);
        $need_record = I("request.need_record");
        if (empty($cid)) $this->ajaxError();

        //调用接口
        $bool_set   = (new ChannelsService())->setAlwaysRecord($cid, $need_record);

        //更改数据库
        $set_record = (new LiveService())->updateItem(['cid'=>$cid],['need_record'=>$need_record]);

        if (false !== $bool_set && false !== $set_record) {
            $this->ajaxOk("设置成功!");
        } else {
            $this->ajaxError("设置失败!");
        }
    }
}
