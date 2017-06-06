<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Tuolaji <479923197@qq.com>
// +----------------------------------------------------------------------
namespace Portal\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\QiNiuService;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class AdminPostController extends AdminbaseController {
    
	protected $posts_model;
	protected $term_relationships_model;
	protected $terms_model;
	
	function _initialize() {
		parent::_initialize();
		$this->posts_model = D("Portal/Posts");
		$this->terms_model = D("Portal/Terms");
		$this->term_relationships_model = D("Portal/TermRelationships");
	}
	
	// 后台文章管理列表
	public function index(){
		$this->_lists(array("post_status"=>array('neq',3)));
		$this->_getTree();
		$this->display();
	}
	
	// 文章添加
	public function add(){
		$terms = $this->terms_model->order(array("listorder"=>"asc"))->select();
		$term_id = I("get.term",0,'intval');
		$this->_getTermTree();
		$term=$this->terms_model->where(array('term_id'=>$term_id))->find();
		$this->assign("term",$term);
		$this->assign("terms",$terms);
		$this->display();
	}
	
	// 文章添加提交
	public function add_post(){
		if (IS_POST) {
			if(empty($_POST['term'])){
				$this->error("请至少选择一个分类！");
			}
			// print_r($_FILES);die;
			// 视频上传
			if(!empty($_FILES['post']['name']['video'])){
			    $video = array(
			        'video' => array(
			            'name' => $_FILES['post']['name']['video'],
                        'type' => $_FILES['post']['type']['video'],
                        'tmp_name' => $_FILES['post']['tmp_name']['video'],
                        'error' => $_FILES['post']['error']['video'],
                        'size' => $_FILES['post']['size']['video']
                    )
                );

                $file_path = (new QiNiuService())->qiniuUpload($video, 'video', '', true);// 上传文件
                $arr=explode("/", $file_path);
                //获取最后一个/后边的字符
                $name=$arr[count($arr)-1];
                $videoname = $name;

			    if (empty($file_path)) {
			        $this->error("视频上传失败,请重新上传");
			        echo "<script>parent.location.reload();</script>";
			    }
			} else {
				$videoname = '';
			}
//            echo $_FILES['post']['name']['smeta'];
			// 图片上传
			if(!empty($_FILES['post']['name']['smeta'])){
                $smeta = array(
                    'smeta' => array(
                        'name' => $_FILES['post']['name']['smeta'],
                        'type' => $_FILES['post']['type']['smeta'],
                        'tmp_name' => $_FILES['post']['tmp_name']['smeta'],
                        'error' => $_FILES['post']['error']['smeta'],
                        'size' => $_FILES['post']['size']['smeta']
                    )
                );
                $path = (new QiNiuService())->qiniuUpload($smeta, 'smeta', '', true);// 上传文件
                $arr=explode("/", $path);
                //获取最后一个/后边的字符
                $cname=$arr[count($arr)-1];
                $smetaname = $cname;

			    if (empty($path)) {
			        $this->error("图片上传失败,请重新上传");
			        echo "<script>parent.location.reload();</script>";
			    }
			} else {
				$smetaname = '';
			}

			 
			$_POST['post']['post_modified']=date("Y-m-d H:i:s",time());
			$_POST['post']['post_author']=get_current_admin_id();
			$_POST['post']['smeta'] = $smetaname;
			$_POST['post']['video_url'] = $videoname;
			$article=I("post.post");
			// $article['smeta']=json_encode($_POST['smeta']);
			$article['post_content']=htmlspecialchars_decode($article['post_content']);
			$result=$this->posts_model->add($article);
			if ($result) {
				foreach ($_POST['term'] as $mterm_id){
					$this->term_relationships_model->add(array("term_id"=>intval($mterm_id),"object_id"=>$result));
				}
				
				$this->success("添加成功！");
			} else {
				$this->error("添加失败！");
			}
			 
		}
	}
	
	// 文章编辑
	public function edit(){
		$id=  I("get.id",0,'intval');
		
		$term_relationship = M('TermRelationships')->where(array("object_id"=>$id,"status"=>1))->getField("term_id",true);
		$this->_getTermTree($term_relationship);
		$terms=$this->terms_model->select();
		$post=$this->posts_model->where("id=$id")->find();

		$file_ext = end(explode('.', $post['smeta']));
		$file_domain = QiNiuService::findDomainByExt($file_ext);

		$this->assign("post",$post);
		$this->assign("smeta",$file_domain . $post['smeta']);
		$this->assign("video_url",$file_domain . $post['video_url']);
		$this->assign("terms",$terms);
		$this->assign("term",$term_relationship);
		$this->display();
	}
	
	// 文章编辑提交
	public function edit_post(){
		if (IS_POST) {
			if(empty($_POST['term'])){
				$this->error("请至少选择一个分类！");
			}
			$post_id=intval($_POST['post']['id']);
			
			$this->term_relationships_model->where(array("object_id"=>$post_id,"term_id"=>array("not in",implode(",", $_POST['term']))))->delete();
			foreach ($_POST['term'] as $mterm_id){
				$find_term_relationship=$this->term_relationships_model->where(array("object_id"=>$post_id,"term_id"=>$mterm_id))->count();
				if(empty($find_term_relationship)){
					$this->term_relationships_model->add(array("term_id"=>intval($mterm_id),"object_id"=>$post_id));
				}else{
					$this->term_relationships_model->where(array("object_id"=>$post_id,"term_id"=>$mterm_id))->save(array("status"=>1));
				}
			}

			if(!empty($_FILES['post']['name']['video'])){
				$accessKey = 'cVTkwJcXRD8kuA_K4T3_tF6weAxDUk8UG8Nq5Sd-';
			    $secretKey = 'ton1PrC5P2cnk-c_My6PKK1PxQB9PJu9pwqdT2d3';
			    // 构建鉴权对象
			    $auth      = new Auth($accessKey, $secretKey);
			    // 要上传的空间
			    $bucket    = 'xkht-pdf';
			    // 生成上传 Token
			    $token     = $auth->uploadToken($bucket);
			    // 要上传文件的本地路径
			    $filePath  = $_FILES['post']['tmp_name']['video'];
			    // 上传到七牛后保存的文件名
			    $videoname = date('YmdHis', time()).rand(1000000, 9999999);
			    $key       = $videoname;
			    // 初始化 UploadManager 对象并进行文件的上传
			    $uploadMgr = new UploadManager();
			    // 调用 UploadManager 的 putFile 方法进行文件的上传
			    list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
			    if ($err !== null) {
			        $this->error("视频上传失败,请重新上传");
			        echo "<script>parent.location.reload();</script>";
			    } else {
			    	$_POST['post']['video_url'] = $videoname;
			    }
			} else {
				unset($_POST['post']['video_url']);
			}
			// 图片上传
			if(!empty($_FILES['post']['name']['smeta'])){
				$accessKey = 'cVTkwJcXRD8kuA_K4T3_tF6weAxDUk8UG8Nq5Sd-';
			    $secretKey = 'ton1PrC5P2cnk-c_My6PKK1PxQB9PJu9pwqdT2d3';
			    // 构建鉴权对象
			    $auth      = new Auth($accessKey, $secretKey);
			    // 要上传的空间
			    $bucket    = 'xkht-pdf';
			    // 生成上传 Token
			    $token     = $auth->uploadToken($bucket);
			    // 要上传文件的本地路径
			    $filePath  = $_FILES['post']['tmp_name']['smeta'];
			    // 上传到七牛后保存的文件名
			    $smetaname = date('YmdHis', time()).rand(1000000, 9999999);
			    $key       = $smetaname;
			    // 初始化 UploadManager 对象并进行文件的上传
			    $uploadMgr = new UploadManager();
			    // 调用 UploadManager 的 putFile 方法进行文件的上传
			    list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
			    if ($err !== null) {
			        $this->error("图片上传失败,请重新上传");
			        echo "<script>parent.location.reload();</script>";
			    } else {
			    	$_POST['post']['smeta'] = $smetaname;
			    }
			} else {
				unset($_POST['post']['smeta']);
			}
			
			unset($_POST['post']['post_author']);
			$_POST['post']['post_modified']=date("Y-m-d H:i:s",time());
			$article=I("post.post");
			$article['post_content']=htmlspecialchars_decode($article['post_content']);
			$result=$this->posts_model->save($article);
			if ($result!==false) {
				$this->success("保存成功！");
			} else {
				$this->error("保存失败！");
			}
		}
	}
	
	// 文章排序
	public function listorders() {
		$status = parent::_listorders($this->term_relationships_model);
		if ($status) {
			$this->success("排序更新成功！");
		} else {
			$this->error("排序更新失败！");
		}
	}
	
	/**
	 * 文章列表处理方法,根据不同条件显示不同的列表
	 * @param array $where 查询条件
	 */
	private function _lists($where=array()){
		$term_id=I('request.term',0,'intval');
		
		$where['post_type']=array(array('eq',1),array('exp','IS NULL'),'OR');
		
		if(!empty($term_id)){
		    $where['b.term_id']=$term_id;
			$term=$this->terms_model->where(array('term_id'=>$term_id))->find();
			$this->assign("term",$term);
		}
		
		$start_time=I('request.start_time');
		if(!empty($start_time)){
		    $where['post_date']=array(
		        array('EGT',$start_time)
		    );
		}
		
		$end_time=I('request.end_time');
		if(!empty($end_time)){
		    if(empty($where['post_date'])){
		        $where['post_date']=array();
		    }
		    array_push($where['post_date'], array('ELT',$end_time));
		}
		
		$recommended=I('request.recommended');   // 是否推荐
		if($recommended != ''){
		    $where['recommended']=array('EQ', $recommended);
		}

		$istop=I('request.istop');   // 是否置顶
		if($istop != ''){
		    $where['istop']=array('EQ', $istop);
		}
		// $where['is_del'] = array('EQ', '1');
		$this->posts_model
		->alias("a")
		->where($where);
		
		if(!empty($term_id)){
		    $this->posts_model->join("__TERM_RELATIONSHIPS__ b ON a.id = b.object_id");
		}
		
		$count=$this->posts_model->count();
			
		$page = $this->page($count, 25);
			
		$this->posts_model
		->alias("a")
		->join("__ADMIN_USERS__ c ON a.post_author = c.id")
		->where($where)
		->limit($page->firstRow , $page->listRows)
		->order("a.post_date DESC");
		if(empty($term_id)){
		    $this->posts_model->field('a.*,c.user_login,c.user_nicename');
		}else{
		    $this->posts_model->field('a.*,c.user_login,c.user_nicename,b.listorder,b.tid');
		    $this->posts_model->join("__TERM_RELATIONSHIPS__ b ON a.id = b.object_id");
		}
		$posts=$this->posts_model->select();
		
		$this->assign("page", $page->show('Admin'));
		$this->assign("formget",array_merge($_GET,$_POST));
		$this->assign("posts",$posts);
	}
	
	// 获取文章分类树结构 select 形式
	private function _getTree(){
		$term_id=empty($_REQUEST['term'])?0:intval($_REQUEST['term']);
		$result = $this->terms_model->order(array("listorder"=>"asc"))->select();
		
		$tree = new \Tree();
		$tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
		$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		foreach ($result as $r) {
			$r['str_manage'] = '<a href="' . U("AdminTerm/add", array("parent" => $r['term_id'])) . '">添加子类</a> | <a href="' . U("AdminTerm/edit", array("id" => $r['term_id'])) . '">修改</a> | <a class="js-ajax-delete" href="' . U("AdminTerm/delete", array("id" => $r['term_id'])) . '">删除</a> ';
			$r['visit'] = "<a href='#'>访问</a>";
			$r['taxonomys'] = $this->taxonomys[$r['taxonomy']];
			$r['id']=$r['term_id'];
			$r['parentid']=$r['parent'];
			$r['selected']=$term_id==$r['term_id']?"selected":"";
			$array[] = $r;
		}
		
		$tree->init($array);
		$str="<option value='\$id' \$selected>\$spacer\$name</option>";
		$taxonomys = $tree->get_tree(0, $str);
		$this->assign("taxonomys", $taxonomys);
	}
	
	// 获取文章分类树结构 
	private function _getTermTree($term=array()){
		$result = $this->terms_model->order(array("listorder"=>"asc"))->select();
		
		$tree = new \Tree();
		$tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
		$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		foreach ($result as $r) {
			$r['str_manage'] = '<a href="' . U("AdminTerm/add", array("parent" => $r['term_id'])) . '">添加子类</a> | <a href="' . U("AdminTerm/edit", array("id" => $r['term_id'])) . '">修改</a> | <a class="js-ajax-delete" href="' . U("AdminTerm/delete", array("id" => $r['term_id'])) . '">删除</a> ';
			$r['visit'] = "<a href='#'>访问</a>";
			$r['taxonomys'] = $this->taxonomys[$r['taxonomy']];
			$r['id']=$r['term_id'];
			$r['parentid']=$r['parent'];
			$r['selected']=in_array($r['term_id'], $term)?"selected":"";
			$r['checked'] =in_array($r['term_id'], $term)?"checked":"";
			$array[] = $r;
		}
		
		$tree->init($array);
		$str="<option value='\$id' \$selected>\$spacer\$name</option>";
		$taxonomys = $tree->get_tree(0, $str);
		$this->assign("taxonomys", $taxonomys);
	}
	
	// 文章删除
	public function delete(){
		if(isset($_GET['id'])){
			$id = I("get.id",0,'intval');
			if ($this->posts_model->where(array('id'=>$id))->save(array('post_status'=>3)) !==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
		
		if(isset($_POST['ids'])){
			$ids = I('post.ids/a');
			
			if ($this->posts_model->where(array('id'=>array('in',$ids)))->save(array('post_status'=>3))!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
	}
	
	// 文章审核
	public function check(){
		if(isset($_POST['ids']) && $_GET["check"]){
		    $ids = I('post.ids/a');
			
			if ( $this->posts_model->where(array('id'=>array('in',$ids)))->save(array('post_status'=>1)) !== false ) {
				$this->success("审核成功！");
			} else {
				$this->error("审核失败！");
			}
		}
		if(isset($_POST['ids']) && $_GET["uncheck"]){
		    $ids = I('post.ids/a');
		    
			if ( $this->posts_model->where(array('id'=>array('in',$ids)))->save(array('post_status'=>0)) !== false) {
				$this->success("取消审核成功！");
			} else {
				$this->error("取消审核失败！");
			}
		}
	}
	
	// 文章置顶
	public function top(){
		if(isset($_POST['ids']) && $_GET["top"]){
			$ids = I('post.ids/a');
			
			if ( $this->posts_model->where(array('id'=>array('in',$ids)))->save(array('istop'=>1))!==false) {
				$this->success("置顶成功！");
			} else {
				$this->error("置顶失败！");
			}
		}
		if(isset($_POST['ids']) && $_GET["untop"]){
		    $ids = I('post.ids/a');
		    
			if ( $this->posts_model->where(array('id'=>array('in',$ids)))->save(array('istop'=>0))!==false) {
				$this->success("取消置顶成功！");
			} else {
				$this->error("取消置顶失败！");
			}
		}
	}
	
	// 文章推荐
	public function recommend(){
		if(isset($_POST['ids']) && $_GET["recommend"]){
			$ids = I('post.ids/a');
			
			if ( $this->posts_model->where(array('id'=>array('in',$ids)))->save(array('recommended'=>1))!==false) {
				$this->success("推荐成功！");
			} else {
				$this->error("推荐失败！");
			}
		}
		if(isset($_POST['ids']) && $_GET["unrecommend"]){
		    $ids = I('post.ids/a');
		    
			if ( $this->posts_model->where(array('id'=>array('in',$ids)))->save(array('recommended'=>0))!==false) {
				$this->success("取消推荐成功！");
			} else {
				$this->error("取消推荐失败！");
			}
		}
	}
	
	// 文章批量移动
	public function move(){
		if(IS_POST){
			if(isset($_GET['ids']) && $_GET['old_term_id'] && isset($_POST['term_id'])){
			    $old_term_id=I('get.old_term_id',0,'intval');
			    $term_id=I('post.term_id',0,'intval');
			    if($old_term_id!=$term_id){
			        $ids=explode(',', I('get.ids/s'));
			        $ids=array_map('intval', $ids);
			         
			        foreach ($ids as $id){
			            $this->term_relationships_model->where(array('object_id'=>$id,'term_id'=>$old_term_id))->delete();
			            $find_relation_count=$this->term_relationships_model->where(array('object_id'=>$id,'term_id'=>$term_id))->count();
			            if($find_relation_count==0){
			                $this->term_relationships_model->add(array('object_id'=>$id,'term_id'=>$term_id));
			            }
			        }
			        
			    }
			    
			    $this->success("移动成功！");
			}
		}else{
			$tree = new \Tree();
			$tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
			$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
			$terms = $this->terms_model->order(array("path"=>"ASC"))->select();
			$new_terms=array();
			foreach ($terms as $r) {
				$r['id']=$r['term_id'];
				$r['parentid']=$r['parent'];
				$new_terms[] = $r;
			}
			$tree->init($new_terms);
			$tree_tpl="<option value='\$id'>\$spacer\$name</option>";
			$tree=$tree->get_tree(0,$tree_tpl);
			 
			$this->assign("terms_tree",$tree);
			$this->display();
		}
	}
	
	// 文章批量复制
	public function copy(){
	    if(IS_POST){
	        if(isset($_GET['ids']) && isset($_POST['term_id'])){
	            $ids=explode(',', I('get.ids/s'));
	            $ids=array_map('intval', $ids);
	            $uid=sp_get_current_admin_id();
	            $term_id=I('post.term_id',0,'intval');
	            $term_count=$terms_model=M('Terms')->where(array('term_id'=>$term_id))->count();
	            if($term_count==0){
	                $this->error('分类不存在！');
	            }
	            
	            $data=array();
	            
	            foreach ($ids as $id){
	                $find_post=$this->posts_model->field('post_keywords,post_source,post_content,post_title,post_excerpt,smeta')->where(array('id'=>$id))->find();
	                if($find_post){
	                    $find_post['post_author']=$uid;
	                    $find_post['post_date']=date('Y-m-d H:i:s');
	                    $find_post['post_modified']=date('Y-m-d H:i:s');
	                    $post_id=$this->posts_model->add($find_post);
	                    if($post_id>0){
	                        array_push($data, array('object_id'=>$post_id,'term_id'=>$term_id));
	                    }
	                }
	            }
	            
	            if ( $this->term_relationships_model->addAll($data) !== false) {
	                $this->success("复制成功！");
	            } else {
	                $this->error("复制失败！");
	            }
	        }
	    }else{
	        $tree = new \Tree();
	        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
	        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
	        $terms = $this->terms_model->order(array("path"=>"ASC"))->select();
	        $new_terms=array();
	        foreach ($terms as $r) {
	            $r['id']=$r['term_id'];
	            $r['parentid']=$r['parent'];
	            $new_terms[] = $r;
	        }
	        $tree->init($new_terms);
	        $tree_tpl="<option value='\$id'>\$spacer\$name</option>";
	        $tree=$tree->get_tree(0,$tree_tpl);
	
	        $this->assign("terms_tree",$tree);
	        $this->display();
	    }
	}
	
	// 文章回收站列表
	public function recyclebin(){
		$this->_lists(array('post_status'=>array('eq',3)));
		$this->_getTree();
		$this->display();
	}
	
	// 清除已经删除的文章
	public function clean(){
		if(isset($_POST['ids'])){
			$ids = I('post.ids/a');
			$ids = array_map('intval', $ids);
			$status=$this->posts_model->where(array("id"=>array('in',$ids),'post_status'=>3))->delete();
			$this->term_relationships_model->where(array('object_id'=>array('in',$ids)))->delete();
			
			if ($status!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}else{
			if(isset($_GET['id'])){
				$id = I("get.id",0,'intval');
				$status=$this->posts_model->where(array("id"=>$id,'post_status'=>3))->delete();
				$this->term_relationships_model->where(array('object_id'=>$id))->delete();
				
				if ($status!==false) {
					$this->success("删除成功！");
				} else {
					$this->error("删除失败！");
				}
			}
		}
	}
	
	// 文章还原
	public function restore(){
		if(isset($_GET['id'])){
			$id = I("get.id",0,'intval');
			if ($this->posts_model->where(array("id"=>$id,'post_status'=>3))->save(array("post_status"=>"1"))) {
				$this->success("还原成功！");
			} else {
				$this->error("还原失败！");
			}
		}
	}
	
}