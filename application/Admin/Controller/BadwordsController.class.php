<?php
/**
 * 校区／分校菜单列表
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\BadwordsService;

class BadwordsController extends AdminbaseController 
{
	/**
     * 校区分校显示列表
     */
    public function index() 
    {   
		$word       = I('request.word');

		$where      = array('word' => $word);
		$badwords   = new BadwordsService;
        $data       = $badwords->getall($where);
        // echo '<pre>';print_r($categorys);die;
        $this->assign('lists', $data['list']);
        $this->assign('page', $data['page']);
        $this->assign('where', $data['where']);
        $this->display();
    }

    /**
     * 黑名单词添加页面
     */
    public function add()
    {
    	$this->display();
    }

    /**
     * 黑名单词添加数据处理
     */
    public function add_post()
    {
    	$badwords   = new BadwordsService;
    	if (IS_POST){
    		$word   = I('post.word');
    		$num    = strlen($word);
    		if ($word != '' && $num <= 60){
    			$arr = array('word' => $word);
    			$res = $badwords->getone($arr);
    			if (empty($res)){
    				$result = $badwords->add_post($arr);
    				if ($result != false){
    					$this->success("添加成功", U('Badwords/index'));
    				} else {
    					$this->error("添加失败，请重新添加");
    				}
    			} else {
    				$this->error("该黑名单词已存在，请重新添加");
    			}
    		} else {
    			$this->error("黑名单词不得为空且不能多余20个字");
    		}
    	} else {
    		$this->error("提交数据失败，请重新添加");
    	}
    }

    /**
     * 删除黑名单词
     */
    public function delete()
    {
        // var_dump($_REQUEST);die;
    	$id       = I('request.id');
    	$badwords = new BadwordsService;
    	$res      = $badwords->del($id);

        if ($res) {
            $this->ajaxReturn(['msg'=>'删除成功～','code'=>1]);
        } else {
            $this->ajaxReturn(['msg'=>'删除失败～','code'=>2]);
        }
    	
    	// if ($res != false){
    	// 	$this->success("删除成功", U('Badword/index'));
    	// } else {
    	// 	$this->error("删除失败");
    	// }
    }
}