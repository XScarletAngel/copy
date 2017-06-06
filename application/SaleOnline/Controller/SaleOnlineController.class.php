<?php
/**
 * User: machuang
 * Date: 2017/4/20
 * Time: 上午9:49
 */
namespace SaleOnline\Controller;

use Common\Controller\AdminbaseController;
use Common\Model\GoodsModel;
use Common\Service\ClassNoticeService;
use Common\Service\ClassService;
use Common\Service\GoodsService;
use Common\Service\QiNiuService;
use Common\Service\SchoolZoneService;


class SaleOnlineController extends AdminbaseController {


    /**
     * User: maChuang
     * 线上销售班级管理首页
     */
    public function index()
    {

        $param = I('request.');
//        echo "<pre>"; print_r($param);die;
        //整理查询条件
        $where = [];
        if(!empty($param['s1']) && !empty($param['sc1'])){
            $where[$param['s1']] = $param['sc1'];
        }
        if(!empty($param['shelf_status'])){
            $where['shelf_status'] = $param['shelf_status'];
        }
        if(!empty($param['zone_id'])){
            $where['zone_id'] = $param['zone_id'];
        }
        if(!empty($param['sub_zone_id'])){
            $where['sub_zone_id'] = $param['sub_zone_id'];
        }
//        echo "<pre>"; print_r($param);die;

        //获取分校信息
        $class       = new ClassNoticeService();
        $school      = $class->zone();
        $this->assign('school', $school);  // 学校
        $this->assign('param', $param);
        //获取全部线上销售商品的信息
        $goods = new GoodsService();
        $data  = $goods->findGoods($where);
        $this->assign('data', $data['list']);
//        echo "<pre>"; print_r($data['list']);die;
        $this->assign('page', $data['page']);
        $this->display();
    }


    /**
     * User: maChuang
     * 线上销售班级管理首页
     */
    public function create()
    {

        //获取分校信息
        $class = new ClassService();
        //可供选择的班级： 总校且状态为未结课的录播班与直播班。
        $where =[
            'class_type'=>['in','2,3'],
            'class_status'=>['in','1,2']
        ];
        $classes = $class->getClasses($where);
//        echo "<pre>"; print_r($classes);die;
        $this->assign('classes', $classes);
        $this->display();
    }

    /**
     * 处理POST提交表单
     */
    public function store()
    {
        $goods = D("Goods"); // 实例化
        $param = I('request.');
        //处理上传文件
        $file = $_FILES["filess"];
        if (!empty($file['name'])) {
            $name = QiNiuService::qiniuUpload($_FILES, 'filess', 'xkht-img');
            $goods_cover = $name;
        } else {
            $goods_cover = '';
        }
//        echo "<pre>"; print_r($goods_cover);die;
        $param['goods_cover'] = $goods_cover;
        $param['goods_info']  = $_POST['goods_info'];
        if (!$goods->create($param, 1)) { // 指定新增数据
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            $this->error($goods->getError());
        } else {
            $goods = new GoodsService();
            $id  = $goods->addData($param);
            $this->redirect('SaleOnline/index');
        }
    }

    //更改班级开班状态
    public function changeStatus()
    {
        $param = I('request.');

        $c = new GoodsService();
        $where = ['id' => $param['id']];
        $data  = ['shelf_status' => $param['shelf_status']];
//        print_r($where);print_r($data);die;
        $res = $c->update($where,$data);
        $this->ajaxReturn(['result'=>$res]);
    }

    public function show()
    {
        $id = I('request.id');
        $data = GoodsService::find($id);
//        echo "<pre>"; print_r($data);die;
        $this->assign('data', $data);
        $this->display();
    }

    public function edit()
    {
        $id = I('request.id');
        $data = GoodsService::find($id);
//        echo "<pre>"; print_r($data);die;
        $this->assign('data', $data);
        //获取分校信息
        $class = new ClassService();
        //可供选择的班级： 总校且状态为未结课的录播班与直播班。
        $where =[
            'class_type'=>['in','2,3'],
            'class_status'=>['in','1,2']
        ];
        $classes = $class->getClasses($where);
//        echo "<pre>"; print_r($classes);die;
        $this->assign('classes', $classes);
        $this->display();
    }

    public function update()
    {
        //处理上传文件
        $file = $_FILES["filess"];
        if (!empty($file['name'])) {
            $name = QiNiuService::qiniuUpload($_FILES, 'filess', 'xkht-img');
            $goods_cover = $name;
        } else {
            $goods_cover = I('request.goods_cover');
        }
//        echo "<pre>"; print_r($goods_cover);die;
        $param = I('request.');
//        echo "<pre>"; print_r($param);die;
        $param['goods_cover'] = $goods_cover;
        $param['goods_info'] = $_POST['goods_info'];
        $where = ['id'=>$param['goods_id']];
        $res = (new GoodsService())->update($where,$param);
        $this->redirect('SaleOnline/index');

    }



}












