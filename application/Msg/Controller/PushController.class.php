<?php
namespace Msg\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\ClassServiceService;
use Common\Service\PushMsgService;
use Common\Service\PushTaskService;
use Common\Service\UsersService;
use Common\Service\UserTokenService;
use Course\Controller\CourseController;

/**
 * 推送相关
 * Class PushController
 * @package Dict\Controller
 */

class PushController extends AdminbaseController
{

	public function _initialize()
	{
		parent::_initialize();
	}

	/**
	 * 服务管理列表
	 */
	public function index()
	{
        $param = I('request.');
//        echo "<pre>"; print_r($param); die;
        $where = [];
        if(!empty($param['device_type'])){
            $where['device_type'] = $param['device_type'];
        }
        if(!empty($param['user_type'])){
            $where['user_type'] = $param['user_type'];
        }
        if(!empty($param['is_notice'])){
            $where['is_notice'] = $param['is_notice'];
        }
        if(!empty($param['begin_time'])){
            $where['push_time'][] = array('egt',$param['begin_time']);
        }
        if(!empty($param['end_time'])){
            $where['push_time'][] = array('elt',$param['end_time']);
        }
        $data = PushTaskService::findPushTask($where);
        $this->assign('res', $data);
//        echo "<pre>";print_r($data);die;
        $this->assign('req', $param);
		$this->display();
	}

	/**
	 * 添加页面
	 */
	public function add()
	{
		$this->display();
	}

    /**
     * User: maChuang
     * 新建推送
     */
    public function create()
    {
        $param      = I('request.');
        $title      = $param['title'];                 //标题
        $text       = $param['text'];                   //内容
        $push_url   = ($param['push_url'] == '3')?$param['push_other_url']:$param['push_url'];//落地页
        $device_type= $param['device_type'];//推送平台
        $is_notice  = $param['is_notice'];//是否通知(区别在是否同时发送站内信）
        $user_type  = $param['user_type']; //用户类型
        $send_object= $param['send_object']; //发送对象
        if($send_object == '1'){
            //处理excel文件上传
            move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
            //文件的路径
            $filename = "./upload/" . $_FILES["file"]["name"];
            //文件类型
            $file_types = explode ( ".", $filename );
            $file_type = $file_types [count ( $file_types )-1 ];
            //对文件类型进行判断
            if (strtolower ( $file_type ) != "xls" && strtolower ( $file_type ) != "xlsx"){
                unlink($filename); //删除上传文件
                $this->error ( '不是Excel文件，重新上传' );
            }
            $phone = $this->dealExcel($file_type,$filename);
            //删除文件
            unlink($filename);
        }else{
            //手动输入手机号
            $pdata = explode(',',$param['headPhoneArea']);
            foreach ($pdata as $datum) {
                if(preg_match("/^1[34578]\d{9}$/", $datum)){
                    $phone[]= $datum;
                }
            }
        }
        if(empty($phone)){
            $this->error ( '没有可用的推送用户，请确认输入用户登陆手机号格式正确' );
        }
        $user = new UsersService();
        foreach ($phone as $it){
            //查询user_id
            $user_id = $user->getInfo(['user_login'=>$it]);
            //查询cid
            if(!empty($user_id)){
                $cid = UserTokenService::find(['user_id'=>$user_id['id']]);
                if(!empty($cid)) $cids[]=['cid'=>$cid['pushid'],'user_id'=>$user_id['id']];
            }
        }
//        echo "<pre>"; print_r($phone); die;
        //推送时间
        $time_type = $param['time_type'];
        if($time_type == '1'){
            //立即发送
            $push_time = date('Y-m-d H:i:s');
        }else{
            //选择时间发送
            $push_time = $param['select_time'];
        }
        $session = session();
        //整理数据入库
        $insert = [
            'user_type'     =>  $user_type,
            'is_notice'     =>  $is_notice,
            'device_type'   =>  $device_type,
            'push_title'    =>  $title,
            'push_text'     =>  $text,
            'push_url'      =>  $push_url,
            'push_user'     =>  $session['name'],
            'push_num'      =>  count($phone),
            'push_time'     =>  $push_time,
            'status'        =>  1,
        ];
//        echo "<pre>"; print_r($insert); die;
        //入库，获得任务id
        $taskId = (new PushTaskService())->addData($insert);
        //放入推送队列，或者说有守护进程在后台一直监控task表。
        $pm = new PushMsgService();
//        echo "<pre>"; print_r($cids); die;
        foreach ($cids as $it){
            //插入数据库
            $insertPM = [
                'user_id'           =>  $it['user_id'],
                'user_type'         =>  $user_type,
                'type'              =>  '群推',
                'msg_id'            =>  0,
                'device_type'       =>  0,
                'task_id'           =>  $taskId,
                'status'            =>  1,
                'ref'               =>  1,
                'content'           =>  $text,
                'body'              =>  json_encode(['title'=>$title,'text'=>$text]),
            ];
            $pm->addData($insertPM);
        }
//        echo "<pre>"; print_r($insertPM); die;

        $this->redirect('Push/index');
    }

	/**
	 * 添加数据处理
	 */
	public function dealExcel($file_type,$filename)
	{

        //使用PHPEXECL读取文本
        $excelReader =  new CourseController();
        $data =$excelReader->read ( $file_type,$filename );
        unlink($filename); //删除上传文件
        //对手机号进行正则验证
        $phone = [];
        foreach ($data as $datum) {
            if(preg_match("/^1[34578]\d{9}$/", $datum[0])){
                $phone[]= $datum[0];
            }
        }
        return $phone;
	}

}
