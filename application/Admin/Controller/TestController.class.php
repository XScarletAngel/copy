<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\ApiService;

/**
 * 测试控制器
 * Class TestController
 * @package Admin\Controller
 */
class TestController extends AdminbaseController
{
    
	public function index()
    {
        $ret = ApiService::call('member.notice', [
            'mobile' => '18612226443',
            'content' => "您已成功注册星空海天账号，登录账号为【18612226443】密码为【88888888】,请点【链接地址】下载APP，或访问【网站地址】登录。"
        ]);

        var_dump($ret);die;
    }
	
}