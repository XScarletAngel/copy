<?php
/**
 * User: machuang
 * Date: 2017/4/17
 * Time: 下午6:49
 */
namespace Teacher\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\ChannelsService;
use Common\Service\QiNiuService;
use Common\Service\SchoolService;
use Common\Service\TeacherService;
use Common\Service\Tools;
use Common\Service\UsersService;
use Think\Upload\Driver\Qiniu;

class TeacherController extends AdminbaseController {



    /**
     * User: maChuang
     * 教师管理首页
     */
    public function index()
    {
        //整理查询条件
        $param = I('request.');
        $where = [];
        if(!empty($param['s1']) && !empty($param['sc1'])){
            $where[$param['s1']] = $param['sc1'];
        }
        if(!empty($param['s2']) && !empty($param['sc2'])){
            $where[$param['s2']] = $param['sc2'];
        }
        if(!empty($param['s3']) && !empty($param['sc3'])){
            $where[$param['s3']] = $param['sc3'];
        }
        if(!empty($param['sign_status'])){
            $where['sign_status'] = $param['sign_status'];
        }
        //按照分页获取教师
        $tc = new TeacherService();
        $data = $tc->getDataByPage($where);
        $this->assign('data', $data['list']);
        $this->assign('page', $data['page']);
        $this->assign('param', $param);
        $this->display();
    }

    /**
     * User: maChuang
     * 添加教师页面
     */
    public function create()
    {
        $schools = new SchoolService();
        $schoolData = $schools->getSchools();
        $this->assign('schools', $schoolData);
        $this->display();
    }

    //处理添加教师POST操作
    public function store()
    {
        $user_login = I('request.user_login');
        if(!Tools::isMobile($user_login)){
            $this->error('手机号格式不正确');
        }
        //判断教师是否存在
        $tc = new TeacherService();
        $tcExist = $tc->select(['user_login'=>$user_login]);

        if(count($tcExist) > 0){
            $this->error('教师已经存在！');
            $user_id = $tcExist[0]['id'];
        }
        //处理上传文件
        $file = $_FILES["filess"];
        if (!empty($file['name'])) {
            $name = QiNiuService::qiniuUpload($_FILES, 'filess', 'xkht-img');
            $avatar = $name;
        } else {
            $avatar = I('request.avatar');
        }
        //判断用户表账号是否存在
        $user = new UsersService();
        $userExist = $user->getInfo(['user_login'=>$user_login]);
        if(count($userExist) == 0){
            //users创建教师用户
            $data = [
                'user_login'    =>  I('request.user_login'),
                'user_name'     =>  I('request.user_name'),
                'avatar'        =>  $avatar,
                'sex'           =>  I('request.sex'),
                'birthday'      =>  I('request.birthday'),
                'last_login_ip' =>  get_client_ip(0),
                'create_time'   =>  date("Y-m-d H:i:s",time()),
                'user_status'   =>  2,
                'user_type'     =>  2,
                'mobile'        =>  I('request.user_login'),
                'source'        =>  1,
                'province'      =>  I('request.cmbProvince'),
                'city'          =>  I('request.cmbCity'),
                'county'        =>  I('request.cmbArea'),
            ];
            $user_id = $user->add($data);
            if(!$user_id){
                $this->error('添加失败，请重试！');
            }

            /*埋点 检测用户的im账号 begin*/
            $create_info = (new ChannelsService())->createID($user_id, hide_phone(I('request.user_login')));
            if (200 == $create_info['code']) {
                M("users")->where(['id'=>$user_id])->save([
                    'im_accid'=>$create_info['info']['accid'],
                    'im_token'=>$create_info['info']['token'],
                    'im_name' =>$create_info['info']['name'],
                ]);
            }
            /*埋点 检测用户的im账号 end*/
        }else{
            $user_id = $userExist['id'];
        }
        //生成教师编号
        $tch_no =  substr(date('Y'), -2).mt_rand(10000,99999);
        //教师表插入数据
        $teacherData = [
            'user_id'         =>    $user_id,                       //用户user_id关联Users表
            'user_login'      =>    I('request.user_login'),        //教师手机号
            'user_name'       =>    I('request.user_name'),         //教师姓名
            'tch_no'          =>    $tch_no,                        //教师编号
            'sign_date'       =>    I('request.sign_date'),         //签约日期
            'sign_status'     =>    I('request.sign_status'),       //签约状态
            'graduate_time'   =>    I('request.graduate_time'),     //读研时间
            'school'          =>    I('request.school'),            //读研学校
            'school_dept'     =>    I('request.school_dept'),       //读研院系
            'school_major'    =>    I('request.school_major'),      //读研专业
            'school_research' =>    I('request.school_research'),   //读研方向
            'school_code'     =>    I('request.school_code'),       //读研学校code
            'school_dept_code'=>    I('request.dept_code'),         //读研院系code
            'school_major_code'=>   I('request.major_code'),        //读研专业code
            'school_research_code'=>I('request.school_research_code'),//读研方向code
            'school_id'       =>    I('request.school_id'),         //读研学校code
            'school_dept_id'  =>    I('request.dept_id'),           //读研院系code
            'school_major_id' =>    I('request.major_id'),          //读研专业code
            'school_research_id'=>  I('request.school_research_id'),//读研方向code
            'remark'          =>    I('request.remark'),            //备注
            'avatar'          =>    $avatar,                        //头像（冗余）
            'add_time'        =>    date("Y-m-d H:i:s",time()),//添加时间
        ];

        $result = $tc->addData($teacherData);
        if($result){
            $this->redirect('Teacher/Teacher/index');
        }else{
            $this->error('添加失败，请重试！');
        }
    }

    /**
     * User: maChuang
     * 详情页
     */
    public function show()
    {
        $id = I('request.id');
        $teacherData = TeacherService::find($id);
        if(empty($teacherData)){
            $this->error('用户信息不存在！');
        }
        $userData = UsersService::find($teacherData['user_id']);
        $this->assign('tcData', $teacherData);
        $this->assign('userData', $userData);

        $this->display();
    }

    /**
     * User: maChuang
     * 信息修改页
     */
    public function edit()
    {
        if(!Tools::isMobile(I('request.user_login'))){
            $this->error('手机号格式不正确');
        }
        $id = I('request.id');
        $teacherData = TeacherService::find($id);
        if(empty($teacherData)){
            $this->error('用户信息不存在！');
        }
        $userData = UsersService::find($teacherData['user_id']);
        $this->assign('tcData', $teacherData);
        $this->assign('userData', $userData);
        $schools = new SchoolService();
        $schoolData = $schools->getSchools();
        $this->assign('schools', $schoolData);

        $this->display();
    }

    /**
     * User: maChuang
     * 修改信息后，更新post提交处理
     */
    public function update()
    {
        $param = I('request.');
        if(empty($param)) return false;
        $file = $_FILES["filess"];
        if (!empty($file['name'])) {
            $name = QiNiuService::qiniuUpload($_FILES, 'filess', 'xkht-img');
            $avatar = $name;
        } else {
            $avatar = I('request.avatar');
        }
        $userData = [
            'user_name'     =>  I('request.user_name'),
            'avatar'        =>  $avatar,
            'user_email'    =>  I('request.user_email'),
            'user_url'      =>  I('request.user_url'),
            'sex'           =>  I('request.sex'),
            'signature'     =>  I('request.signature'),
            'create_time'   =>  I('request.create_time'),
            'user_status'   =>  I('request.user_status'),
            'birthday'      =>  I('request.birthday'),
            'score'         =>  I('request.score'),
            'user_type'     =>  I('request.user_type'),
            'mobile'        =>  I('request.mobile'),
            'source'        =>  I('request.source'),
            'coin'          =>  I('request.coin'),
            'no_talk'       =>  I('request.no_talk'),
            'province'      =>  I('request.cmbProvince'),
            'city'          =>  I('request.cmbCity'),
            'county'        =>  I('request.cmbArea'),
        ];
        $teacherData = [
            'user_id'         =>    I('request.user_id'),       //用户user_id关联Users表
            'user_login'      =>    I('request.user_login'),    //教师手机号
            'user_name'       =>    I('request.user_name'),     //教师姓名
            'tch_no'          =>    I('request.tch_no'),        //教师编号
            'sign_date'       =>    I('request.sign_date'),     //签约日期
            'sign_status'     =>    I('request.sign_status'),   //签约状态
            'graduate_time'   =>    I('request.graduate_time'), //读研时间
            'school'          =>    I('request.school'),        //读研学校
            'school_dept'     =>    I('request.school_dept'),   //读研院系
            'school_major'    =>    I('request.school_major'),  //读研专业
            'school_research' =>    I('request.school_research'),//读研方向
            'school_code'     =>    I('request.school_code'),        //读研学校code
            'school_dept_code'=>    I('request.school_dept_code'),   //读研院系code
            'school_major_code'=>   I('request.school_major_code'),  //读研专业code
            'school_research_code'=>I('request.school_research_code'),//读研方向code
            'remark'          =>    I('request.remark'),        //备注
            'avatar'          =>    I('request.avatar'),
            'upd_time'        =>    date("Y-m-d H:i:s",time()),//添加时间
        ];
        //查询条件
        $where= ['user_login'=>I('request.user_login')];
        $user = new UsersService();
        $res1 = $user->update($where,$userData);
        $tc = new TeacherService();
        $res1 = $tc->update($where,$teacherData);
        $this->redirect('Teacher/Teacher/index');
    }

    /**
     * User: maChuang
     * @return bool
     * ajax 获取教师信息，users表和market表（潜在用户信息）,业务逻辑验证
     */
    public function getInfo()
    {
        //验证数据
        $user_login = I('request.user_login');
        if(empty($user_login)) return false;
        //判断教师是否存在
        $tc = new TeacherService();
        $exist = $tc->select(['user_login'=>$user_login]);
        if(count($exist)>0){
            exit(json_encode(['code'=>0]));
        }
        $users = new UsersService();
        $res1 = $users->getInfo(['user_login'=>$user_login]);
        header('Content-Type:application/json; charset=utf-8');
        if(!empty($res1)){
            exit(json_encode(['code'=>1,'data'=>$res1]));
        }else{
            exit(json_encode(['code'=>1]));
        }
    }

    /**
     * User: maChuang
     * 由school_code（学校编码）获取其所有的院系
     */
    public function getDept()
    {
        $school_code = I('request.school_code');
        $where = ['school_code'=>$school_code];
        $depts = SchoolService::getDepts($where);
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($depts));
    }

    /**
     * User: maChuang
     * 由dept_id（院系id）获取其所有的专业
     */
    public function getmajors()
    {
        $dept_id = I('request.dept_id');
        $school_id = I('request.school_id');
        $where = ['dept_id'=>$dept_id,'school_id'=>$school_id];
        $depts = SchoolService::getmajors($where);
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($depts));
    }

    /**
     * User: maChuang
     * 由school_id(学校id),dept_id（院系id）,major_id（专业id）获取其所有的方向
     */
    public function getResearchs()
    {
        $dept_id = I('request.dept_id');
        $school_id = I('request.school_id');
        $major_id = I('request.major_id');
        $where = ['dept_id'=>$dept_id,'school_id'=>$school_id,'major_id'=>$major_id];
        $getResearchs = SchoolService::getResearchs($where);
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($getResearchs));
    }
}
