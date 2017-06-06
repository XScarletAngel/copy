<?php
/**
 * 后台首页
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\MajorService;

class MajorController extends AdminbaseController 
{

    /**
     * 显示门类／学科／专业
     */
    public function index() 
    {
        S(array('type'=>'File','expire'=>3600));  // 缓存初始化
        if(S('categorys') == ''){
        	$major     = new MajorService;
        	$categorys = $major->select();
            S('categorys',$categorys);  // 设置缓存
        } else {
            $categorys = S('categorys');  // 从缓存里面读取文件赋给变量
        }
    	$this->assign('categorys', $categorys);
       	$this->display();
    }

    /**
     * 添加子菜单页面
     */
    public function add() 
    {
        $parentid   = I("get.parentid",0,'intval');  // 接受父ID参数
        $major_name = I("get.major_name");  // 接受父类名称
        $this->assign('parentid', $parentid);
        $this->assign('major_name', $major_name);
        $this->display();
    }

    /**
     * 添加子菜单数据处理
     */
    public function add_post() 
    {   
        if (IS_POST){
            $parentid   = I("post.parentid");     // 父id
            $major_name = I("post.major_name");   // 名称
            $level      = I("post.level");        // 类别
            $major_code = I("post.major_code");         // 教育部编码
            if(empty($major_name) || empty($major_code)){
                $this->error("请填写完整信息");
            }
            if ($level != 3){
                $major_type = 0;
            } else {
                $major_type = I("post.major_type");   // 硕士类型
            }
            if ($level > 3){
                $this->error("请添加同级专业");
            }
            if ($level == 1 && strlen(trim($major_code, ' ')) != 2){
                $this->error("类别与教育部编码不符");
            }
            if ($level == 2 && strlen(trim($major_code, ' ')) != 4){
                $this->error("类别与教育部编码不符");
            }
            if ($level == 3 && strlen(trim($major_code, ' ')) != 6){
                $this->error("类别与教育部编码不符");
            }

            $post  = array('parentid' => $parentid, 'major_name' => $major_name, 'major_type' => $major_type, 'level' => $level, 'major_code' => $major_code);
            
            $major = new MajorService;
            $res   = $major->add_post($post);
            if ($res != false){
                $this->success("添加成功", U('Major/index'));
            } else {
                $this->error("添加失败");
            }
        } else {
            $this->error("获取数据错误，请重新添加");
        }
    }

    /**
     * 编辑菜单页面
     */
    public function edit() 
    {
        $id = I("get.id",0,'intval');  // 接受父ID参数
        if (!empty($id)){
            $major = new MajorService;
            $arr   = $major->edit($id);
        } else {
            $this->error("读取数据失败，请重新编辑");
        }
        $this->assign('arr', $arr);
        $this->display();
    }

    /**
     * 编辑菜单数据处理
     */
    public function edit_post() 
    {
        $id         = I("post.id",0,'intval');  // 接受ID参数
        $major_name = I("post.major_name");
        $major_type = I("post.major_type");
        // $level      = I("post.level");          // 门类、学科、专业不予进行修改
        $major_code = I("post.major_code");
        if(empty($major_name) || empty($major_code)){
            $this->error("请填写完整信息");
        }
        $post       = array('major_name' => $major_name, 'major_type' => $major_type, 'major_code' =>          $major_code);

        $major      = new MajorService;
        $res        = $major->edit_post($id, $post);
        
        if ($res != false){
            $this->success("编辑成功", U('Major/index'));
        } else {
            $this->error("编辑失败，请重新编辑");
        }
    }

    /**
     * 删除有parentid的表数据
     */
    public function delete()
    {
        $id     = I('request.id');
        $del    = new MajorService;
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

