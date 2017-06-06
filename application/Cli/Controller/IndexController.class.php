<?php
namespace Cli\Controller;

use Common\Controller\ClibaseController;

class IndexController extends ClibaseController
{

	public function _initialize()
	{
		parent::_initialize();
	}

	/**
	 * cli 模块执行
	 */
	public function index()
	{
		echo "Cli 模块执行脚本:\n";
		echo "Usage: php cli.php [module]/[controller]/[action] \n";
		die;
	}

}
