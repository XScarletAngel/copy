<?php
/**
 * 第三方配置文件
 * Created by PhpStorm.
 * User: lixiuran
 * Date: 17/5/10
 * Time: 上午11:40
 *
 * demo: C("QINIU.PDF_BUCKET")
 */

return [
    /*netease 直播*/
    'NETEASE' =>[
        'APP_KEY'=>'12cb6c1a65e8247e9ceee8f20b8ae70a',
        'APP_SECRET'=>'efa63a003a7d',
    ],
    /*七牛 云存储*/
    'QINIU'=>[
        /*上传key*/
        'ACCESS_KEY'=>'cVTkwJcXRD8kuA_K4T3_tF6weAxDUk8UG8Nq5Sd-',
        /*上传secret*/
        'SECRET_KEY'=>'ton1PrC5P2cnk-c_My6PKK1PxQB9PJu9pwqdT2d3',
        /*桶名=>域名*/
        'BUCKET_DOMAIN'=>[
            'xkht-pdf'  => 'http://oozxft3e3.bkt.clouddn.com/',
            'xkht-img'  => 'http://ooylo9ron.bkt.clouddn.com/',
            'xkht-paper'=> 'http://oqfw7pjci.bkt.clouddn.com/',
        ],
        /*桶名=>允许上传文件类型*/
        'ALLOW_EXT'=>[
            'xkht-pdf' => ['pdf','doc','docx','mp4','flv','avi','rm','asp','wmv','mov','mpeg4','3gp','qsv','xls','xlsx'],
            'xkht-img' => ['png','jpg','gif','jpeg'],
        ],
        'PAPER_BUCKET'  => 'xkht-paper',
        'QINIU_UP_HOST' => 'http://up.qiniu.com',
        'QINIU_RS_HOST' => 'http://rs.qbox.me',
        'QINIU_RSF_HOS' => 'http://rsf.qbox.me',
    ],
];