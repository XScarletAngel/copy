<?php
/**
 * User: machuang
 * Date: 2017/4/20
 * Time: 上午9:49
 */

namespace SaleOnline\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\ClassNoticeService;
use Common\Service\OrdersService;
use Common\Service\StudentService;
use Common\Service\StusignService;
use Common\Service\UsersService;

class DealController extends AdminbaseController
{


    /**
     * User: maChuang
     * 线上销售班级管理首页
     */
    public function index()
    {

        $param = I('request.');
        //筛选条件逻辑判断
        //交易类型
        if (!empty($param['pay_status'] && !empty($param['pay_status_type']))) {
            if ($param['pay_status'] == '1,2,3') { //支付交易
                if ($param['pay_status_type'] == '4' || $param['pay_status_type'] == '5') {
                    $this->error("筛选支付交易类型，不可同时筛选已退款、未退款的商品！");
                }
            } else {
                if ($param['pay_status_type'] == '1' || $param['pay_status_type'] == '2' || $param['pay_status_type'] == '3') {
                    $this->error("筛选退款交易类型，不可同时筛选未支付，已支付，交易关闭的商品！");
                }
            }

        }
//        echo "<pre>"; print_r($param);die;
        //整理查询条件
        $where = [];
        //订单号
        if (!empty($param['order_no'])) {
            $where['order_no'] = $param['order_no'];
        }
        //学员筛选条件
        if (!empty($param['s1']) && !empty($param['sc1'])) {
            if ($param['s1'] == 'user_login') {
                $where['user_login'] = $param['sc1'];
            } else {
                $userWhere = [$param['s1'] => $param['sc1']];
                $user = StusignService::find($userWhere);
//                echo "<pre>"; print_r($user);die;
                $where['user_id'] = $user['id'];
            }
        }
        //根据地理位置 筛选
        $zoneWhere = [];
        if (!empty($param['zone_id'])) {
            $zoneWhere['zone_id'] = $param['zone_id'];
        }
        if (!empty($param['sub_zone_id'])) {
            $zoneWhere['sub_zone_id'] = $param['sub_zone_id'];
        }
        if (!empty($zoneWhere)) {
            $stu = StudentService::find($zoneWhere);
//            echo "<pre>"; print_r($stu);die;
            if (count($stu) == 0) {
                $this->error("该校区分区下没有订单！");
            }
            foreach ($stu as $temp) {
                $map[] = $temp['user_id'];
            }
            $map = implode(',', $map);
            $where['user_id'] = array('in', $map);
        }
        //下单时间 开始时间
        if (!empty($param['start_time'])) {
            $where['add_time'][] = array('egt', $param['start_time']);
        }
        //下单时间 结束时间
        if (!empty($param['end_time'])) {
            $where['add_time'][] = array('elt', $param['end_time']);
        }
        //支付方式
        if (!empty($param['pay_way'])) {
            $where['pay_way'] = $param['pay_way'];
        }
        //创建方式
        if (!empty($param['order_type'])) {
            $where['order_type'] = array('in', $param['order_type']);
        }
        //状态
        if (!empty($param['pay_status_type'])) {
            $where['pay_status'] = $param['pay_status_type'];
        } else {
            //交易类型
            if (!empty($param['pay_status'])) {
                $where['pay_status'] = array('in', $param['pay_status']);
            }
        }

//        echo "<pre>"; print_r($where);die;


        //获取分校信息
        $class = new ClassNoticeService();
        $school = $class->zone();
        $this->assign('school', $school);  // 学校
        $this->assign('param', $param);
        $data = (new OrdersService())->ordersByPage($where);
        $this->assign('datas', $data['list']);
        $this->assign('page', $data['page']);
//        echo "<pre>"; print_r($data['list']);die;
        $this->display();
    }

    public function add()
    {
        $this->display();
    }

    public function store()
    {
        $param = I('request.');
//        echo "<pre>"; print_r($param);  die;
        if ($param['pay_status'] == 2) {
            $order_type = 2;
        } elseif ($param['pay_status'] == 5) {
            $order_type = 3;
        } else {
            $order_type = 0;
        }

        $countWhere['add_time'][] = array('egt', date('Y-m-d'));
        $countWhere['add_time'][] = array('elt', date('Y-m-d', (strtotime(date('Y-m-d')) + 24 * 3600)));

        $count = OrdersService::count($countWhere);
//        echo "<pre>";print_r($countWhere); print_r($count);  die;
        switch (strlen($count)) {
            case 1:
                $countNo = '0000' . $count;
                break;
            case 2:
                $countNo = '000' . $count;
                break;
            case 3:
                $countNo = '00' . $count;
                break;
            case 4:
                $countNo = '0' . $count;
                break;
            case 5:
                $countNo = $count;
                break;
        }
//        echo $count;die;

        $order_no = date("Ymd") . '0' . $countNo;
        $create_uid = session('ADMIN_ID');
        $insertArray = [
            'order_no' => $order_no,
            'user_id' => $param['user_id'],
            'user_login' => $param['user_login'],
            'user_name' => $param['user_name'],
            'pay_status' => $param['pay_status'],
            'pay_way' => 3,
            'order_price' => $param['order_price'],
            'dct_price' => 0,
            'pay_price' => $param['order_price'],
            'trans_title' => $param['trans_title'],
            'receipt_no' => $param['receipt_no'],
            'order_type' => $order_type,
            'create_uid' => $create_uid,
            'remark' => $param['remark'],
        ];
        (new OrdersService())->addData($insertArray);
        $this->redirect('Deal/index');
    }


    /**
     * User: maChuang
     * @return json
     * 查找用户
     */
    public function findUser()
    {
        $user_login = I('request.user_login');
        if (empty($user_login)) return false;
        $where = ['user_login' => $user_login];
        $userInfo = (new UsersService())->getInfo($where);
//        echo "<pre>"; print_r($userInfo);die;
        if (empty($userInfo)) {
            $msg = ['code' => 400, 'msg' => '不存在该用户！'];
        } else {
            $msg = ['code' => 200, 'msg' => '存在[' . $userInfo['user_name'] . ']用户！', 'user_id' => $userInfo['id'], 'user_name' => $userInfo['user_name']];
        }

        exit(json_encode($msg));

    }


}

//根据地区搜索班级
//            $classes = (new ClassService())->getClasses($zoneWhere,'id'); //可以使用的班级
//            if(count($classes)>0){
//                foreach($classes as $c){
//                    $mapClass[] = $c['id'];
//                }
//                $mapClass = implode(',',$mapClass);
//                $goodsWhere['class_id'] = array('in',$mapClass);
//            }
//            //根据班级搜索商品
//            $goods = GoodsService::getGoods($goodsWhere,'id');
//            if(count($goods)>0) {
//                foreach ($goods as $good) {
//                    $mapgood[] = $good['id'];
//                }
//                $mapgood = implode(',', $mapgood);
//                $where['goods_id'] = array('in', $mapgood);
//            }












