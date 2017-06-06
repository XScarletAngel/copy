<?php
/**
 * 参    数：
 * 作    者：lhr
 * 功    能：教研管理
 * 修改日期：2017-04-24
 */
namespace Course\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\CourseService;
use Common\Service\QiNiuService;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class CourseController extends AdminbaseController {

	/**
     * 教研管理下面课程管理
     */
	public function index()
	{
		$course_no      = I('request.course_no');
		$course_name    = I('request.course_name');
		$subject_type   = I('request.subject_type');    // 适合科目
		$course_version = I('request.course_version');  // 版本号
		$course_status  = I('request.course_status');   // 课程状态

		$course = new CourseService();
		$data   = $course->getall($course_no, $course_name, $subject_type, $course_version, $course_status);
		$this->assign('data', $data['result']);
		$this->assign('page', $data['page']);
		$this->display();
	}

	/*
	 * 课程详情页面
	 */
	public function courseinfo()
	{
		$id = I("get.id");  // 课程ID
		$course = new CourseService();
		$data   = $course->courseinfo($id);
		// $data['course_type'] = explode(',', $data['course_type']);
		$this->assign("id", $id);  // 课程基本信息
		$this->assign("baseinfo", $data);  // 课程基本信息
		$this->display();
	}

	/*
	 * 课程列表删除课程
	 */
	public function delcourse()
	{
		$id     = I('request.id');  // 课程ID
		$course = new CourseService();
    	$res    = $course->delcourse($id);
		header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($res));
        $this->ajaxReturn(array_values($res));
	}

	/*
	 * 课程状态修改
	 */
	public function status()
	{
		$id     = I('request.id');  // 课程ID
		$status = I('request.statu');  // 要修改的状态
		$course = new CourseService();
    	$res    = $course->status($id, $status);
		header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($res));
        $this->ajaxReturn(array_values($res));
	}

	/*
	 * 添加课程页面
	 */
	public function addcourse()
	{
        $edid = I("request.edid");  // 点击返回按钮接收的课程ID的标志
		$id = I("request.id");  // 课程ID
		if(!empty($id)){  // 编辑课程
			$course = new CourseService();
    		$data   = $course->addcourse($id);
            $file_ext = end(explode('.', $data['course_logo']));
            $file_domain = QiNiuService::findDomainByExt($file_ext);
//            echo $file_domain;die;
            $data['course_logo'] = $file_domain.$data['course_logo'];
    		$this->assign("data", $data);
    		$this->assign("id", $id);  // 课程ID传入页面
    		$this->assign("editid", '1');  // 是否修改的参数
		}
        if(!empty($edid)){  // 点击返回再次进行提交时显示的数据
            $course = new CourseService();
            $data   = $course->addcourse($edid);
            $file_ext = end(explode('.', $data['course_logo']));
            $file_domain = QiNiuService::findDomainByExt($file_ext);
//            echo $file_domain;die;
            $data['course_logo'] = $file_domain.$data['course_logo'];
            $this->assign("data", $data);
            $this->assign("edid", $edid);  // 课程ID传入页面
        }
		$this->display();
	}

	/*
	 * 添加课程数据提交
	 */
	public function basic_post()
	{
        if(empty($_POST['edid'])){
            if($_FILES['course_logo']['name'] == ''){
                $this->error("请上传课程封面");
            }
            if(empty($_POST['course_no']) || empty($_POST['course_name']) || empty($_POST['course_num']) || empty($_POST['subject_type']) || empty($_POST['subject_type']) || empty($_POST['course_type']) || empty($_POST['course_intro'])){
                $this->error("请填写完整信息");
            }
            if(empty($_POST['id'])){  // 判断是否是修改操作，ID为空，是添加
                $file_path = (new QiNiuService())->qiniuUpload($_FILES, 'course_logo', '', true);// 上传文件
                if(empty($file_path)) {// 上传错误提示错误信息
                    $this->error("上传失败");
                }else{// 上传成功 获取上传文件信息
                    $data = $_POST;
                    $str  = '';
                    foreach($data['course_type'] as $k=>$v){
                        $str .= $data['course_type'][$k].",";
                    }
                    $data['course_type'] = rtrim($str, ",");

                    $arr=explode("/", $file_path);
                    //获取最后一个/后边的字符
                    $name=$arr[count($arr)-1];
                    $data['course_logo'] = $name;

                    $course = new CourseService();
                    $res    = $course->basic_post($data);
                    if($res){
                        $this->success("保存成功，请进行下一步", U('Course/outline?id='.$res));
                    } else {
                        $this->error("保存失败，请重新保存");
                    }
                }
            } else {
                if(empty($_FILES)){
                    $data = $_POST;
                    $str  = '';
                    foreach($data['course_type'] as $k=>$v){
                        $str .= $data['course_type'][$k].",";
                    }
                    $data['course_type'] = rtrim($str, ",");
                    $course = new CourseService();
                    $res    = $course->basic_post($data);
                    if($res){
                        $this->success("保存成功，请进行下一步", U('Course/outline?id='.$res));
                    } else {
                        $this->error("保存失败，请重新保存");
                    }
                } else {
                    $file_path = (new QiNiuService())->qiniuUpload($_FILES, 'course_logo', '', true);// 上传文件
                    if(empty($file_path)) {// 上传错误提示错误信息
                        $this->error("上传失败");
                    }else{// 上传成功 获取上传文件信息
                        $data = $_POST;
                        $str  = '';
                        foreach($data['course_type'] as $k=>$v){
                            $str .= $data['course_type'][$k].",";
                        }
                        $data['course_type'] = rtrim($str, ",");

                        $arr=explode("/", $file_path);
                        //获取最后一个/后边的字符
                        $name=$arr[count($arr)-1];
                        $data['course_logo'] = $name;

                        $course = new CourseService();
                        $res    = $course->basic_post($data);
                        if($res){
                            $this->success("保存成功，请进行下一步", U('Course/outline?id='.$res));
                        } else {
                            $this->error("保存失败，请重新保存");
                        }
                    }
                }
            }
        } else {
            $this->success("该课程已存在请进行下一步", U('Course/outline?id='.$_POST['edid']));
        }
	}

	/*
	 * 知识点大纲页面
	 */
	public function outline()
	{
		$id     = I('get.id');
		$editid = I('get.editid');
		$course = new CourseService();
    	$res    = $course->outline($id);
		$this->assign('id', $id);
		$this->assign('editid', $editid);
		$this->assign('res', $res);
		$this->display();
	}

	/*
	 * 知识点大纲页面数据提交
	 */
	public function outline_post()
	{
		$id = I('post.id');
		if(!empty($id)){
    		$this->success("保存成功，请进行下一步", U('Course/teachplan?id='.$id));
    	} else {
    		$this->error("保存失败，请重新保存");
    	}
	}

	/*
	 * 知识点大纲添加子节点弹层页面
	 */
	public function node_layer()
	{
		$id  = I('get.id');   // 课程id
		$cid = I('get.cid');  // 当前节点id
		$this->assign("id", $id);
		$this->assign("cid", $cid);
		$this->display();
	}

	/*
	 * 知识点大纲添加子节点弹层页面
	 */
	public function node_layer_post()
	{
		$data = $_POST;
		$course = new CourseService();
        $result = $course->node_layer_post($data);
       
        if($result){
        	$this->success("添加成功");
        	echo "<script>parent.location.reload();</script>";
        } else {
        	$this->error("添加失败,请重新添加");
        	echo "<script>parent.location.reload();</script>";
        }
	}

	/*
	 * 知识点大纲编辑节点弹层页面
	 */
	public function edit_layer()
	{
		$cid         = I('get.cid');  // 当前节点id
		$course      = new CourseService();
        $data        = $course->edit_layer($cid);
        // $data['tag'] = explode('|', $data['tag']);

		$this->assign("data", $data);
		$this->display();
	}

	/*
	 * 知识点大纲编辑节点弹层页面数据提交
	 */
	public function edit_layer_post()
	{
		$data   = $_POST;
		$course = new CourseService();
        $result = $course->edit_layer_post($data);
        if($result){
        	$this->success("编辑成功");
        	echo "<script>parent.location.reload();</script>";
        } else {
        	$this->error("编辑失败,请重新操作");
        	echo "<script>parent.location.reload();</script>";
        }
	}

	/*
	 * 知识点大纲设置试看
	 */
	public function look(){
		$id  = I("request.id");    // 节点ID
		$val = I("request.val");   // 要修改的值
		$course = new CourseService();
        $result = $course->look($id, $val);
        if($result){
        	$res = array('code' => 1, 'msg' => "编辑试看成功");
        } else {
        	$res = array('code' => 0, 'msg' => "编辑试看失败");
        }
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($res));
        $this->ajaxReturn(array_values($res));
	}

	/*
	 * 知识点大纲弹层页面
	 */
	public function outline_layer()
	{
		$id = I('get.id');
		$this->assign("id", $id);
		$this->display();
	}

	/*
	 * 知识点大纲弹层页面提交数据
	 */
	public function outline_layer_post()
	{
		$id = $_POST['courseid'];
		if (! empty ( $_FILES ['inputExcel'] ['name'] )) 
		{
		    $tmp_file = $_FILES ['inputExcel'] ['tmp_name'];
		    $file_types = explode ( ".", $_FILES ['inputExcel'] ['name'] );
		    $file_type = $file_types [count ( $file_types ) - 1];
			
		     /*判别是不是.xls文件，判别是不是excel文件*/
		     if (strtolower ( $file_type ) != "xls" && strtolower ( $file_type ) != "xlsx"){
		          $this->error ( '不是Excel文件，重新上传' );
		     }
		    /*设置上传路径*/
		     $savePath = SITE_PATH . '/upload/avatar/outline/';
            if(file_exists($savePath)):
            else:
                mkdir($savePath,0777);
            endif;
            /*以时间来命名上传的文件*/
		     $str = date ( 'Ymdhis' );
		     $file_name = $str . "." . $file_type;
		     /*是否上传成功*/
		    if (! copy ( $tmp_file, $savePath . $file_name )){
		        $this->error ( '上传失败' );
		    }

		    $data = $this->read ( $file_type,$savePath . $file_name );
		    // echo "<pre>";print_r($data);die;
		   	$course = new CourseService();
    		$result = $course->outline_layer_post($data, $id);
		   	if($result){
		   		$this->success("导入成功！");
		   		echo "<script>parent.location.reload();</script>";
		   	}else{
		   		$this->error ( '导入失败,请查询是否有课件关联' );
		   	}
		}
		
	}

	// 调用PHPexcel

	public function read($file_type,$filename,$encode='utf-8'){
		vendor("PHPExcel");
		Vendor("PHPExcel.PHPExcel.IOFactory");
		if($file_type=="xlsx"){
			$objReader = \PHPExcel_IOFactory::createReader('Excel2007');
		}else{
			$objReader = \PHPExcel_IOFactory::createReader('Excel5');
		}
		$objPHPExcel = new \PHPExcel();
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($filename);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
        $excelData = array();
        for($row = 2; $row <= $highestRow; $row++) {
            for ($col = 0; $col < $highestColumnIndex; $col++) {
				if($objWorksheet->getCellByColumnAndRow(0, $row)->getValue() === null){
					continue;
				}
                $excelData[$row][]=(string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
        }
        return $excelData;
    }

	/*
	 * 教案页面
	 */
	public function teachplan()
	{
		$id     = I('get.id');
		$editid = I('get.editid');
		$course = new CourseService();
    	$res    = $course->teachplan($id);
    	$this->assign('editid', $editid);
		$this->assign('id', $id);
		$this->assign('res', $res);
		$this->display();
	}

	/*
	 * 教案页面数据提交
	 */
	public function teachplan_post()
	{
		$id = I('post.id');
		if(!empty($id)){
    		$this->success("保存成功，请进行下一步", U('Course/ware?id='.$id));
    	} else {
    		$this->error("保存失败，请重新保存");
    	}
	}

	/*
	 * 教案、图文课件、视频课件页面数据删除
	 */
	public function del_plan()
	{
		$id     = I('request.id');
		$course = new CourseService();
    	$res    = $course->del_plan($id);
    	header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($res));
        $this->ajaxReturn(array_values($res));
	}

	/*
	 * 教案弹层页面
	 */
	public function teachplan_layer()
	{
        $id     	= I('get.id');  // 课程大纲表主键ID
        $course_id  = I('get.course_id');  // 课程ID
        if (empty($id) || empty($course_id)) {
            $this->error("参数错误!");
        }
        $course = new CourseService();
        $self = M('course_catalog')->where(["id"=>$id])->find();
        $nbsp = "&nbsp;&nbsp;&nbsp;";

        $option_self = "<option value='".$self['id']."'>" .$nbsp. $self['catalog_name'] . "</option>";

        $option_sub  = $course->get_ware_layer($id, $course_id, $nbsp);

        $option_str  = $option_self . $option_sub;

        $this->assign("option_str", $option_str);
        $this->assign("id", $id);
        $this->display();
	}

	/*
	 * 教案弹层页面提交数据
	 */
	public function teachplan_layer_post()
	{
        $file_path = (new QiNiuService())->qiniuUpload($_FILES, 'teachplan', '', true);// 上传文件
	    if (empty($file_path)) {
	        $this->error("PDF上传失败,请重新上传");
	        echo "<script>parent.location.reload();</script>";
	    } else {
	    	$data = $_POST;

            $arr=explode("/", $file_path);
            //获取最后一个/后边的字符
            $name=$arr[count($arr)-1];

	        $file['file_name'] = $_FILES['teachplan']['name'];
	        //"http://oozxft3e3.bkt.clouddn.com/".
	        $file['file_path'] = $name;
	        $file['tech_type'] = "1";
	        $file['total_num'] = $data['total_num'];

	        $course = new CourseService();
	        $result = $course->teachplan_layer_post($data, $file);
	       
	        if($result){
	        	$this->success("添加成功");
	        	echo "<script>parent.location.reload();</script>";
	        } else {
	        	$this->error("添加失败,请重新添加");
	        	echo "<script>parent.location.reload();</script>";
	        }        
	    }
	}

	/*
	 * 教案文件弹层页面（修改）
	 */
	public function teachplan_file_layer()
	{
        $id     	= I('get.id');  // 课程大纲表主键ID
        $course_id  = I('get.course_id');  // 课程ID
        if (empty($id) || empty($course_id)) {
            $this->error("参数错误!");
        }
        $plan    = M("course_tech_plan");
        $map     = M("catalog_tech_map");
        $maparr  = $map->where("tech_plan_id = ".$id)->select();  // 文件的锚点
        $planarr = $plan->where("id = ".$id)->find();    // 节点上的文件

        $course = new CourseService();
        $self = M('course_catalog')->where(["id"=>$planarr['catalog_id']])->find();
        $nbsp = "&nbsp;&nbsp;&nbsp;";

        $option_self = "<option value='".$self['id']."'>" .$nbsp. $self['catalog_name'] . "</option>";

        $option_sub  = $course->teachplan_file_layer($planarr['catalog_id'], $course_id, $nbsp);
        $option_str  = $option_self . $option_sub;

        $this->assign("option_str", $option_str);
        $this->assign("maparr", $maparr);  // 文件上的锚点
        $this->assign("id", $id);
        $this->display();
	}

	/*
	 * 教案文件弹层页面（修改）数据提交
	 */
	public function teachplan_file_layer_post()
	{
		$data = $_POST;
		$course = new CourseService();
        $result = $course->teachplan_file_layer_post($data);
       
        if($result){
        	$this->success("修改成功");
        	echo "<script>parent.location.reload();</script>";
        } else {
        	$this->error("修改失败,请重新添加");
        	echo "<script>parent.location.reload();</script>";
        }
	}

	/*
	 * 图文课件页面
	 */
	public function ware()
	{
		$id     = I('get.id');
		$editid = I('get.editid');
		$course = new CourseService();
    	$res    = $course->ware($id);
    	$this->assign('editid', $editid);
		$this->assign('id', $id);
		$this->assign('res', $res);
		$this->display();
	}

	/*
	 * 图文课件页面数据提交
	 */
	public function ware_post()
	{
		$id = I('post.id');
		if(!empty($id)){
    		$this->success("保存成功，请进行下一步", U('Course/video?id='.$id));
    	} else {
    		$this->error("保存失败，请重新保存");
    	}
	}

	/*
	 * 图文课件弹层页面
	 */
	public function ware_layer()
	{
		$id     	= I('get.id');  // 课程大纲表主键ID
		$course_id  = I('get.course_id');  // 课程ID
		if (empty($id) || empty($course_id)) {
			$this->error("参数错误!");
		}
		$course = new CourseService();
		$self = M('course_catalog')->where(["id"=>$id])->find();
		$nbsp = "&nbsp;&nbsp;&nbsp;";

		$option_self = "<option value='".$self['id']."'>" .$nbsp. $self['catalog_name'] . "</option>";

    	$option_sub  = $course->get_ware_layer($id, $course_id, $nbsp);

		$option_str  = $option_self . $option_sub;

    	$this->assign("option_str", $option_str);
		$this->assign("id", $id);
		$this->display();
	}

	/*
	 * 图文课件弹层页面提交数据
	 */
	public function ware_layer_post()
	{
        $file_path = (new QiNiuService())->qiniuUpload($_FILES, 'teachplan', '', true);// 上传文件
	    if (empty($file_path)) {
	        $this->error("PDF上传失败,请重新上传");
	        echo "<script>parent.location.reload();</script>";
	    } else {
	    	$data = $_POST;

            $arr=explode("/", $file_path);
            //获取最后一个/后边的字符
            $name=$arr[count($arr)-1];

	        $file['file_name'] = $_FILES['teachplan']['name'];
	        //"http://oozxft3e3.bkt.clouddn.com/".
	        $file['file_path'] = $name;
	        $file['tech_type'] = "2";
	        $file['total_num'] = $data['total_num'];

	        $course = new CourseService();
	        $result = $course->ware_layer_post($data, $file);
	       
	        if($result){
	        	$this->success("添加成功");
	        	echo "<script>parent.location.reload();</script>";
	        } else {
	        	$this->error("添加失败,请重新添加");
	        	echo "<script>parent.location.reload();</script>";
	        }        
	    }
	}

	/*
	 * 图文课件弹层页面（修改）
	 */
	public function ware_file_layer()
	{
        $id     	= I('get.id');  // 课程大纲表主键ID
        $course_id  = I('get.course_id');  // 课程ID
        if (empty($id) || empty($course_id)) {
            $this->error("参数错误!");
        }
        $plan    = M("course_tech_plan");
        $map     = M("catalog_tech_map");
        $maparr  = $map->where("tech_plan_id = ".$id)->select();  // 文件的锚点
        $planarr = $plan->where("id = ".$id)->find();    // 节点上的文件

        $course = new CourseService();
        $self = M('course_catalog')->where(["id"=>$planarr['catalog_id']])->find();
        $nbsp = "&nbsp;&nbsp;&nbsp;";

        $option_self = "<option value='".$self['id']."'>" .$nbsp. $self['catalog_name'] . "</option>";

        $option_sub  = $course->teachplan_file_layer($planarr['catalog_id'], $course_id, $nbsp);
        $option_str  = $option_self . $option_sub;

        $this->assign("option_str", $option_str);
        $this->assign("maparr", $maparr);  // 文件上的锚点
        $this->assign("id", $id);
        $this->display();
	}

	/*
	 * 图文课件弹层页面（修改）数据提交
	 */
	public function ware_file_layer_post()
	{
		$data = $_POST;
		$course = new CourseService();
        $result = $course->ware_file_layer_post($data);
       
        if($result){
        	$this->success("修改成功");
        	echo "<script>parent.location.reload();</script>";
        } else {
        	$this->error("修改失败,请重新添加");
        	echo "<script>parent.location.reload();</script>";
        }
	}

	/*
	 * 视频课件页面
	 */
	public function video()
	{
		$id     = I('get.id');
		$editid = I('get.editid');
		$course = new CourseService();
    	$res    = $course->video($id);
    	$this->assign('editid', $editid);
		$this->assign('id', $id);
		$this->assign('res', $res);
		$this->display();
	}

	/*
	 * 视频课件弹层页面
	 */
	public function video_layer()
	{
        $id     	= I('get.id');  // 课程大纲表主键ID
        $course_id  = I('get.course_id');  // 课程ID
        if (empty($id) || empty($course_id)) {
            $this->error("参数错误!");
        }
        $course = new CourseService();
        $self = M('course_catalog')->where(["id"=>$id])->find();
        $nbsp = "&nbsp;&nbsp;&nbsp;";

        $option_self = "<option value='".$self['id']."'>" .$nbsp. $self['catalog_name'] . "</option>";

        $option_sub  = $course->get_ware_layer($id, $course_id, $nbsp);

        $option_str  = $option_self . $option_sub;

        $this->assign("option_str", $option_str);
        $this->assign("id", $id);
        $this->display();
	}

	/*
	 * 视频弹层页面提交数据
	 */
	public function video_layer_post()
	{
        $file_path = (new QiNiuService())->qiniuUpload($_FILES, 'teachplan', '', true);// 上传文件
	    if (empty($file_path)) {
	        $this->error("PDF上传失败,请重新上传");
	        echo "<script>parent.location.reload();</script>";
	    } else {
	    	$data = $_POST;

            $arr=explode("/", $file_path);
            //获取最后一个/后边的字符
            $name=$arr[count($arr)-1];

	        $file['file_name'] = $_FILES['teachplan']['name'];
	        $file['file_path'] = $name;
	        $file['tech_type'] = "3";
	        $file['total_num'] = $data['total_num'];

	        $course = new CourseService();
	        $result = $course->video_layer_post($data, $file);
	       
	        if($result){
	        	$this->success("添加成功");
	        	echo "<script>parent.location.reload();</script>";
	        } else {
	        	$this->error("添加失败,请重新添加");
	        	echo "<script>parent.location.reload();</script>";
	        }        
	    }
	}

	/*
	 * 视频课件页面数据提交
	 */
	public function video_post()
	{
		$id = I('post.id');
		if(!empty($id)){
    		$this->success("保存成功，请进行下一步", U('Course/exercises?id='.$id));
    	} else {
    		$this->error("保存失败，请重新保存");
    	}
	}

	/*
	 * 视频课件弹层页面（修改）
	 */
	public function video_file_layer()
	{
        $id     	= I('get.id');  // 课程大纲表主键ID
        $course_id  = I('get.course_id');  // 课程ID
        if (empty($id) || empty($course_id)) {
            $this->error("参数错误!");
        }
        $plan    = M("course_tech_plan");
        $map     = M("catalog_tech_map");
        $maparr  = $map->where("tech_plan_id = ".$id)->select();  // 文件的锚点
        $planarr = $plan->where("id = ".$id)->find();    // 节点上的文件

        $course = new CourseService();
        $self = M('course_catalog')->where(["id"=>$planarr['catalog_id']])->find();
        $nbsp = "&nbsp;&nbsp;&nbsp;";

        $option_self = "<option value='".$self['id']."'>" .$nbsp. $self['catalog_name'] . "</option>";

        $option_sub  = $course->teachplan_file_layer($planarr['catalog_id'], $course_id, $nbsp);
        $option_str  = $option_self . $option_sub;

        $this->assign("option_str", $option_str);
        $this->assign("maparr", $maparr);  // 文件上的锚点
        $this->assign("id", $id);
        $this->display();
	}

	/*
	 * 视频课件弹层页面（修改）数据提交
	 */
	public function video_file_layer_post()
	{
		$data = $_POST;
		$course = new CourseService();
        $result = $course->video_file_layer_post($data);
       
        if($result){
        	$this->success("修改成功");
        	echo "<script>parent.location.reload();</script>";
        } else {
        	$this->error("修改失败,请重新添加");
        	echo "<script>parent.location.reload();</script>";
        }
	}

	/*
	 * 习题库页面
	 */
	public function exercises()
	{
		$course_id         = I('request.id');  // 课程ID
        $where = [];//搜索参数
        if(!empty($course_id))             { $where['course_id']    = $course_id; }
        if(!empty(I('request.ce_type')))   { $where['ce_type']    = I('request.ce_type'); }
        if(!empty(I('request.ce_skill')))  { $where['ce_skill']   = I('request.ce_skill'); }
        if(!empty(I('request.ce_level')))  { $where['ce_skill']   = I('request.ce_level'); }
        if(!empty(I('request.point_info'))){ $where['point_info'] = I('request.point_info'); }
		$editid     = I('get.editid');

		$course     = new CourseService();
    	$data       = $course->getExercisesByPage($where);

    	$this->assign('editid', $editid);
		$this->assign('id', $course_id);
		$this->assign('data', $data['list']);
        $this->assign('page', $data['page']);
		$this->display();
	}

	/*
	 *  设计试卷页面
	 */
	public function paper()
	{
		$id     = I('request.id');  // 课程ID
		$editid = I('get.editid');
		$course = new CourseService();
    	$data   = $course->paper($id);
    	$this->assign('editid', $editid);
		$this->assign("id", $id);
		$this->assign("data", $data);
		$this->display();
	}

	/*
	 * 设计试卷页面数据提交
	 */
	public function paper_post()
	{
		$id = I('post.id');
		if(!empty($id)){
    		$this->success("保存成功，请进行下一步", U('Course/distribution?id='.$id));
    	} else {
    		$this->error("保存失败，请重新保存");
    	}
	}

	/*
	 *  设计试卷页面删除数据
	 */
	public function del_paper()
	{
		$id     = I('request.id');  // 试卷ID
		$course = new CourseService();
    	$res    = $course->del_paper($id);
		header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($res));
        $this->ajaxReturn(array_values($res));
	}

	/*
	 * 设计试卷弹层页面(修改试卷信息)
	 */
	public function edit_paper_layer()
	{
		$id     = I('get.id');  // 试卷表主键ID
		$course = new CourseService();
    	$data   = $course->edit_paper_layer($id);
		$this->assign("data", $data);
		$this->display();
	}

	/*
	 * 设计试卷弹层页面(修改试卷信息数据提交)
	 */
	public function edit_paper_layer_post()
	{
		$data   = $_POST;  // 试卷表主键ID
		$course = new CourseService();
    	$res    = $course->edit_paper_layer_post($data);
		if($res){
    		$this->success("添加成功");
    		echo "<script>parent.location.reload();</script>";
    	} else {
    		$this->error("添加失败，请重新添加");
    		echo "<script>parent.location.reload();</script>";
    	}
	}

	/*
	 * 设计试卷弹层页面(添加线下试卷)
	 */
	public function paper_layer()
	{
		$id = I('get.id');  // 课程ID
		$this->assign("id", $id);
		$this->display();
	}

	/*
	 * 设计试卷弹层页面数据提交
	 */
	public function paper_layer_post()
	{
		$data   = $_POST;
		$course = new CourseService();
    	$result = $course->paper_layer_post($data);
    	if($result){
    		$this->success("添加成功");
    		echo "<script>parent.location.reload();</script>";
    	} else {
    		$this->error("添加失败，请重新添加");
    		echo "<script>parent.location.reload();</script>";
    	}
	}

	/*
	 * 试卷分配页面
	 */
	public function distribution()
	{
		$id     = I('get.id');   // 课程ID
		$editid = I('get.editid');
		$course = new CourseService();
    	$res    = $course->distribution($id);
    	$this->assign('editid', $editid);
		$this->assign('id', $id);
		$this->assign('res', $res);
		$this->display();
	}

	/*
	 * 试卷分配页面数据提交
	 */
	public function distribution_post()
	{
		$id = I('post.id');
		if(!empty($id)){
    		$this->success("保存成功，请进行下一步", U('Course/index'));
    	} else {
    		$this->error("保存失败，请重新保存");
    	}
	}

	/*
	 * 试卷分配页面弹层
	 */
	public function distribution_layer()
	{
		$catalogid = I('get.catalogid');  // 分配试卷页面节点id
		$courseid  = I('get.courseid');   // 分配试卷页课程id
		$course    = new CourseService();
    	$data      = $course->distribution_layer($courseid);
		$this->assign('catalogid', $catalogid);  // 选中的节点ID传到弹层
		$this->assign('data', $data);
		$this->display();
	}

	/*
	 * 试卷分配弹层页面数据提交
	 */
	public function distribution_layer_post()
	{
		$data   = $_POST;
		$course = new CourseService();
    	$result = $course->distribution_layer_post($data);
    	if($result){
    		$this->success("添加成功");
    		echo "<script>parent.location.reload();</script>";
    	} else {
    		$this->error("添加失败，请重新添加");
    		echo "<script>parent.location.reload();</script>";
    	}
	}

	/*
	 * 知识点大纲页面(文本)
	 */
	public function outline_text()
	{
		$id     = I('get.id');
		$editid = I('get.editid');
		$course = new CourseService();
    	$res    = $course->outline($id);
		$this->assign('id', $id);
		$this->assign('editid', $editid);
		$this->assign('res', $res);
		$this->display();
	}

	/*
	 * 教案页面(文本)
	 */
	public function teachplan_text()
	{
		$id     = I('get.id');
		$editid = I('get.editid');
		$course = new CourseService();
    	$res    = $course->teachplan($id);
    	$this->assign('editid', $editid);
		$this->assign('id', $id);
		$this->assign('res', $res);
		$this->display();
	}

	/*
	 * 图文课件页面(文本)
	 */
	public function ware_text()
	{
		$id     = I('get.id');
		$editid = I('get.editid');
		$course = new CourseService();
    	$res    = $course->ware($id);
    	$this->assign('editid', $editid);
		$this->assign('id', $id);
		$this->assign('res', $res);
		$this->display();
	}

	/*
	 * 视频课件页面(文本)
	 */
	public function video_text()
	{
		$id     = I('get.id');
		$editid = I('get.editid');
		$course = new CourseService();
    	$res    = $course->video($id);
    	$this->assign('editid', $editid);
		$this->assign('id', $id);
		$this->assign('res', $res);
		$this->display();
	}

	/*
	 * 习题库页面(文本)
	 */
	public function exercises_text()
	{
		$id         = I('request.id');  // 课程ID
		$ce_type    = I('request.ce_type');
		$ce_skill   = I('request.ce_skill');
		$ce_level   = I('request.ce_level');
		$point_info = I('request.point_info');
		$editid     = I('get.editid');

		$course     = new CourseService();
    	$data       = $course->exercises($id, $ce_type, $ce_skill, $ce_level, $point_info);

    	$this->assign('editid', $editid);
		$this->assign('id', $id);
		$this->assign('data', $data);
		$this->display();
	}

	/*
	 *  设计试卷页面(文本)
	 */
	public function paper_text()
	{
		$id     = I('request.id');  // 课程ID
		$editid = I('get.editid');
		$course = new CourseService();
    	$data   = $course->paper($id);
    	$this->assign('editid', $editid);
		$this->assign("id", $id);
		$this->assign("data", $data);
		$this->display();
	}

	/*
	 * 试卷分配页面(文本)
	 */
	public function distribution_text()
	{
		$id     = I('get.id');   // 课程ID
		$editid = I('get.editid');
		$course = new CourseService();
    	$res    = $course->distribution($id);
    	$this->assign('editid', $editid);
		$this->assign('id', $id);
		$this->assign('res', $res);
		$this->display();
	}



}