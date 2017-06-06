<?php

namespace Common\Service;

use Qiniu;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Processing\PersistentFop;
use Qiniu\Storage\BucketManager;
use Think\Exception;

/**
 * 七牛Services
 *
 * Class QiNiuService
 * @package App\Services
 * @author machuang
 */
class QiNiuService
{

    public static $auth_obj      = null;
    public static $allow_ext     = [];
    public static $bucket_domain = [];

    public static function _init()
    {
        // 构建鉴权对象
        self::$auth_obj = new Auth(C("QINIU.ACCESS_KEY"), C("QINIU.SECRET_KEY"));
        // 根据后缀路由bucket
        self::$allow_ext = C("QINIU.ALLOW_EXT");
        self::$bucket_domain = C("QINIU.BUCKET_DOMAIN");
    }

    /**
     * 根据文件后缀返回 bucket
     *
     * @param $ext
     * @return string
     */
    public static function findBucket($ext)
    {
        self::_init();
        if (empty($ext)) return '';
        foreach (self::$allow_ext as $k=>$v){
            $ret = array_search($ext, $v);
            if (false !== $ret) {
                return $k;
            }
        }
        return '';
    }

    /**
     * 返回七牛upToken
     *
     * @return mixed
     * @author machuang
     */
    public static function getToken($bucket)
    {
        self::_init();
        if (empty($bucket)) return '';
        // 生成上传Token
        $token = self::$auth_obj->uploadToken($bucket);
        return $token;
    }

    /**
     * 七牛上传文件
     *
     * @param $files        $_FILES数组
     * @param $file_key     $_FILES数组的key
     * @param $bucket  string 动态指定bucket
     * @param $is_url bool  是否返回全路径
     * @throws \Exception
     * @return string       上传后的文件名 || 全路径
     */
    public static function qiniuUpload($files, $file_key, $bucket = '', $is_url = false)
    {
        if (!is_array($files) || empty($file_key) || !array_key_exists($file_key, $files)) {
            return '';
        }
        self::_init();
        // 要上传文件的本地路径
        $filePath  = $files[$file_key]['tmp_name'];
        $file_ext = end(explode('.', $files[$file_key]['name']));
        // 根据文件后缀查找bucket
        if (empty($bucket)) {
            $bucket = self::findBucket($file_ext);
        }

        if (empty($bucket)) {
            throw new \Exception("未知的文件类型:".$file_ext);
        }
        // 生成上传 Token
        $token     = self::getToken($bucket);

        // 上传到七牛后保存的文件名
        $name = $key = date('YmdHis', time()).rand(1000000, 9999999).".".$file_ext;
        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        if ($err !== null) {
            return '';
        }
        //第三方全路径
        $file_third_url = self::$bucket_domain[$bucket] . $name;
        return $is_url ? $file_third_url :$name;
    }

    /**
     * User: maChuang
     * @param $newname
     * @param $localWordPath
     * @return string
     * 上传生成的word，保持业务服务器整洁
     */
    public static function uploadWord($newname,$localWordPath)
    {
        self::_init();
        // 后缀名
        $file_ext = end(explode('.', $localWordPath));
        // 生成上传 Token
        $token     = self::getToken('xkht-paper');
        // 上传到七牛后保存的文件名
        $name = $key = $newname.".".$file_ext;
        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list($ret, $err) = $uploadMgr->putFile($token, $key, $localWordPath);
        if ($err !== null) {
            return 'upload word error!';
        }
        //第三方全路径
        $file_third_url = self::$bucket_domain['xkht-paper'] . $name;
        return $file_third_url;
    }

    /**
     * 根据后缀返回域名
     *
     * @param $ext
     * @return mixed|string
     */
    public static function findDomainByExt($ext)
    {
        $bucket = self::findBucket($ext);
        if (empty($bucket)) return '';
        return self::$bucket_domain[$bucket];
    }
}
