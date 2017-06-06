<?php
/**
 * 市场活动列表
 */
namespace Market\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\MarketService;
use Common\Service\SchoolZoneService;

class ActivityController extends AdminbaseController
{
    public $market_srv = null;
    public $zone_srv   = null;
    public $mod_activiy   = null;

    public function __construct()
    {
        parent::__construct();
        $this->market_srv = new MarketService();
        $this->zone_srv   = new SchoolZoneService();
        $zone = $this->zone_srv->getSubZone(0);

        $this->assign('zone', $zone);
        $this->assign('req', $_REQUEST);
    }

    /**
     * 市场活动查询列表
     */
    public function index() 
    {
        $zone_id      = I('request.zone_id','0');
        $act_name     = I('request.act_name', '');
        $begin_time   = I('request.act_begin_time', '');
        $end_time     = I('request.act_end_time', '');
        $act_status    = I('request.act_status', '');
        $res          = $this->market_srv->queryActivityByPage($zone_id, $act_name, $begin_time, $end_time, $act_status);

        $zone_id_arr  = array_column($res['data'], 'zone_id');
        $zones = $this->zone_srv->getZoneByIds(join(',',$zone_id_arr));

        if (!empty($zones)) {
            foreach ($zones as $k=>$v) {
                $zones[$v['id']] = $v;
                unset($zones[$k]);
            }
        }

    	$this->assign('zones', $zones);
    	$this->assign('page', $res['page']);
        $this->assign('data', $res['data']);
       	$this->display();
    }

    public function ajaxGetSubZone()
    {
        $pid = I("request.pid", '0');
        if (empty($pid)) {
            $this->ajaxReturn(['code'=>0,'msg'=>'参数错误']);
        }
        $data = $this->zone_srv->getSubZone($pid);
        $this->ajaxReturn(['code'=>1,'msg'=>'success', 'data'=>$data]);
    }

    /**
     * 添加市场活动页面
     */
    public function add() 
    {
        $this->display();
    }

    /**
     * 添加市场活动数据处理
     */
    public function addPost()
    {   
        if (IS_POST){
            $data = $_POST['post'];
            $res = $this->market_srv->addActivity($data);
            if ($res['code'] == 1) {
                $this->success($res['msg'], U("Activity/index"));
            } else {
                $this->error($res['msg'], U("Activity/add"));
            }
        } else {
            $this->error("提交数据失败，请重新提交");
        }
    }

    /**
     * 市场活动编辑页面
     */
    public function edit() 
    {   
        $id  = I('get.id');
        if (empty($id)) {
            $this->error("参数错误!");
        }
        $item = $this->market_srv->queryActById($id);
        $zone = $this->zone_srv->getZoneById($item['zone_id']);

        $this->assign('item', $item);
        $this->assign('item_zone', $zone);
        $this->display();
    }

    /**
     * 编辑市场活动数据处理
     */
    public function editPost()
    {
        if (IS_POST){
            $data = $_POST['post'];
            $res = $this->market_srv->editActivity($data);
            if ($res['code'] == 1) {
                $this->success($res['msg'], U("Activity/index"));
            } else {
                $this->error($res['msg'], U("Activity/edit?id=".$data['id']));
            }
        } else {
            $this->error("提交数据失败，请重新提交");
        }
    }

    /**
     * 市场活动编辑页面
     */
    public function delete()
    {
        $id  = I('request.id');
        if (empty($id)) {
            $this->error("参数错误!");
        }
        $is_del = $this->market_srv->deleteAct($id);
        if (false !== $is_del) {
            $this->success("删除成功!", U("Activity/index"));
        } else {
            $this->error("删除失败!");
        }

    }
}