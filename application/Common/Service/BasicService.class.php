<?php
namespace Common\Service;

/**
 * service 基类
 * Class BasicService
 * @package Common\Service
 */

class BasicService
{
	private $pageNum = 25;

	public function __construct()
	{

	}

	/**
	 * 分页查询
	 * @param $model
	 * @param $where
	 * @return array
	 */
	public function queryByPage($model, $where)
	{
		$count    = $model->where($where)->count();

		$Page     = new \Think\Page($count, $this->getPageNum());

		//分页跳转的时候保证查询条件
		if (is_array($where) && !empty($where)){
			foreach($where as $key=>$val) {
				$Page->parameter[$key]   =   urlencode($val);
			}
		}

		// 分页显示输出$page->show('Admin')
		$show     = $Page->show();
		$result   = $model->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		return ['data' => $result, 'page' => $show];
	}

	/**
	 * @return int
	 */
	public function getPageNum()
	{
		return $this->pageNum;
	}

	/**
	 * @param int $pageNum
	 */
	public function setPageNum($pageNum)
	{
		$this->pageNum = $pageNum;
	}

}