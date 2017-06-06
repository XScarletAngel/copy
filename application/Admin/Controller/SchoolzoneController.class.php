<?php
/**
 * 校区／分校菜单列表
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\SchoolZoneService;

class SchoolzoneController extends AdminbaseController
{
	/**
     * 校区分校显示列表
     */
    public function index() 
    {   
//        S(array('type'=>'File','expire'=>3600));  // 缓存初始化
//        if(S('szone') == ''){
            $schoolzone = new SchoolZoneService;
            $szone      = $schoolzone->getall();
//            S('szone',$szone);  // 设置缓存
//        } else {
//            $szone      = S('szone');  // 从缓存里面读取文件赋给变量
//        }
        $this->assign('categorys', $szone);
        $this->display();
    }

    /**
     * 校区分校添加页面
     */
    public function add() 
    {   
        if (I('get.parentid') != '' && I('get.sz_name') != ''){
            $parentid = I('get.parentid');
            $sz_name  = I('get.sz_name');
        } else {
            $parentid = '';
            $sz_name  = '作为顶级菜单';
        }
        $this->assign('parentid', $parentid);
        $this->assign('sz_name', $sz_name);
        $this->assign('sz_type', I('get.sz_type'));
        $this->display();
    }

    /**
     * 校区分校添加数据处理
     */
    public function add_post() 
    {   
        if (IS_POST){
            $zone = M("school_zone");
            $sz_name    = I('post.sz_name');
            $sz_code    = I('post.sz_code');
            $sz_address = I('post.sz_address');
            $sz_phone   = I('post.sz_phone');
            $sz_master  = I('post.sz_master');
            $sz_status  = I('post.sz_status');
            $sz_type    = I('post.sz_type');
            if(empty($sz_name) || empty($sz_code) || empty($sz_address) || empty($sz_phone) || empty($sz_master)){
                $this->error("请填写完整数据");
            }
            if (empty(I('post.parentid'))){
                $parentid = '0';
            } else {
                $parentid = I('post.parentid');
                $school  = $zone->where(['id' => $parentid])->find();  // 根据父级ID查询
                $findarr = $zone->where(['sz_type' => $sz_type])->find(); // 根据code查询
                $dataarr = $zone->where(['sz_code' => $sz_code])->find(); // 根据code查询
                // 只有校区分校有编码
                if(!empty($dataarr)){
                    $this->error("此编号已存在，请重新填写");
                }
                if($sz_type == 1){
                    if(!empty($findarr)){
                        $this->error("总校已存在，请选择其他类型");
                    }
                } elseif ($sz_type == 2){
                    if($school['sz_type'] != 1){
                        $this->error("分校必须存在于总校下面，请选择正确的类型");
                    }
                    if(strlen(trim($sz_code)) != 2){
                        $this->error("请填写2位的数字编号，可以以0开头");
                    }
                } elseif ($sz_type == 3){
                    if($school['sz_type'] != 2){
                        $this->error("校区必须存在于分校下面，请选择正确的类型");
                    }
                    if(strlen(trim($sz_code)) != 4){
                        $this->error("请填写4位的数字编号，前两位与所属校区的编号相同");
                    }
                    if(substr(trim($sz_code), 0, 2) != $school['sz_code']){
                        $this->error("请填写4位的数字编号，前两位与所属校区的编号相同");
                    }
                }
            }


            $data       = array('parentid' => $parentid, 'sz_name' => $sz_name, 'sz_code' => $sz_code, 'sz_address' => $sz_address, 'sz_phone' => $sz_phone, 'sz_master' => $sz_master, 'sz_status' => $sz_status, 'sz_type' => $sz_type);

            if($data['sz_type'] == 1 || $data['sz_type'] == 4){
                $data['sz_code'] = '';
            }

            $schoolzone = new SchoolZoneService;
            $result     = $schoolzone->add_post($data);
            if ($result != false){
                $this->success("添加成功", U('Schoolzone/index'));
            } else {
                $this->error("添加失败，请重新添加");
            }
        } else {
            $this->error("提交数据失败，请重新提交");
        }
    }

    /**
     * 校区分校编辑页面
     */
    public function edit() 
    {   
        $id         = I('get.id');
        $schoolzone = new SchoolZoneService;
        $onedata    = $schoolzone->edit($id);
        // echo '<pre>';print_r($categorys);die;
        $this->assign('data', $onedata);
        $this->display();
    }

    /**
     * 校区分校添加数据处理
     */
    public function edit_post() 
    {   
        if (IS_POST){
            $id         = I('post.id');
            $sz_name    = I('post.sz_name');
            $sz_code    = I('post.sz_code');
            $sz_address = I('post.sz_address');
            $sz_phone   = I('post.sz_phone');
            $sz_master  = I('post.sz_master');
            $sz_status  = I('post.sz_status');
            $sz_type    = I('post.sz_type');
            if($sz_type == 2 || $sz_type == 3){
                if(empty($sz_code)){
                    $this->error("请输入编码");
                }
            } else {
                $sz_type = '';
            }

            $data       = array('id' => $id, 'sz_name' => $sz_name, 'sz_code' => $sz_code, 'sz_address' => $sz_address, 'sz_phone' => $sz_phone, 'sz_master' => $sz_master, 'sz_status' => $sz_status, 'sz_type' => $sz_type);

            $schoolzone = new SchoolZoneService;
            $result     = $schoolzone->edit_post($data);
            if ($result != false){
                $this->success("编辑成功", U('Schoolzone/index'));
            } else {
                $this->error("编辑失败，请重新编辑");
            }
        } else {
            $this->error("提交数据失败，请重新编辑");
        }
    }

    /**
     * 删除有parentid的表数据
     */
    public function delete()
    {
        $id     = I('request.id');
        $del    = new SchoolZoneService;
        $res    = $del->delete($id);
        if ($res != 3) {
            if ($res) {
                $this->ajaxReturn(['msg'=>'删除成功～','code'=>1]);
            } else {
                $this->ajaxReturn(['msg'=>'删除失败～','code'=>2]);
            }
        } else {
            $this->ajaxReturn(['msg'=>'删除失败,存在下级菜单～','code'=>3]);
        }
    }
}