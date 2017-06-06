<?php
/**
 * User: maChuang
 * site: martist.cn
 * Date: 2017/5/18
 * Time: 下午6:00
 */
namespace  Common\Service;


class Tools{

    function __construct()
    {

    }

    /**
     * User: maChuang
     * 关闭layer弹窗
     */
    public static function CloseLayer()
    {
        $string = "<script> 
                    var index = parent.layer.getFrameIndex(window.name);
                    parent.layer.close(index);
                  </script>";
        return $string;
    }

    /**
     * 验证手机号是否正确
     * @author honfei
     * @param number $mobile
     */
    public static function isMobile($mobile) {
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
    }

}









