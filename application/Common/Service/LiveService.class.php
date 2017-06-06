<?php
namespace Common\Service;

use Common\Model\LiveChannelsModel;

/**
 * 直播业务相关
 *
 * Class LiveService
 * @package Common\Service
 */
class LiveService
{
	public $live_mod = null;

	public function __construct()
	{
		$this->live_mod = new LiveChannelsModel();
	}


    public static function getInfoByPage($where = [])
    {
        $l  = new LiveChannelsModel();
        $result  = $l->getAll($where);
        return $result;
    }

    public function getAll($where = [],$field = '*')
    {
        return $this->live_mod->get($where,$field);
    }

	/**
	 * 分页查询
	 *
	 * @param array $where
	 * @return array
	 */
	public function queryByPage($where = [])
	{
		$res = $this->live_mod->getAll($where);
		return $res;
	}

	/**
	 * 添加记录
	 *
	 * @param $item
	 * @return int
	 */
	public function addItem($item)
	{
		if (empty($item)) {
			return 0;
		}
		return $this->live_mod->addRow($item);
	}

	/**
	 * 查找单记录
	 *
	 * @param $id
	 * @return array
	 */
	public function getItemById($id)
	{
		if (empty($id)) {
			return [];
		}
		return $this->live_mod->getRow($id);
	}

	/**
	 * 添加多条记录
	 *
	 * @param $item
	 * @return int
	 */
	public function addMultiItem($item)
	{
		if (empty($item)) {
			return 0;
		}
		return $this->live_mod->addAll($item);
	}

	/**
	 * 修改记录
	 *
	 * @param $item
	 * @return int
	 */
	public function saveItem($item)
	{
		if (empty($item['id'])) {
			return 0;
		}
		return $this->live_mod->saveRow($item);
	}

	/**
	 * 修改记录
	 *
	 * @param $where
	 * @param $item
	 * @return int
	 */
	public function updateItem($where, $item)
	{
		if (empty($where)) {
			return 0;
		}
		return $this->live_mod->where($where)->save($item);
	}
}