<?php


namespace Common\Service;


use Curl\Curl;
use Curl\MultiCurl;
use Common\Lib\Crypt\Mcrypt;

class ApiService
{


    const METHOD_GET = 'get';
    const METHOD_POST = 'post';




    const ERR_OK = 0;

    private static $multiResponse = [];


    private static $multiCalling = false;

    /**
     *  获取指定路径的uri
     *
     * @param $action    模块.操作
     * @return array/null
     */
    public static function getRequest($action)
    {
        if (strpos($action, '.') === false) {
            return null;
        }

        $uri =  C('API.uri');
        $action = explode('.', $action);

        if (isset($uri[$action[0]][$action[1]])) {
            $conf = $uri[$action[0]][$action[1]];
            return [
                'method'    => $conf[0],
                'url'       => C('API.url' ). $conf[1],
            ];
        }

        return null;
    }



    public static function getDefaultHeader()
    {
        /*
            header 字段

            字段	            类型	        空	默认	注释
            device_name	    varchar(20)	否	pc	设备名称
            device_type	    varchar(20)	否	4	设备类型,ios：2,android：3,pc：4
            system_version	varchar(20)	否	pc	系统版本号
            timestamp	    int	        否		客户端时间戳
            device_id	    varchar(64)	否	pc	设备ID
            version_no	    varchar(20)	否		版本号
            version_id	    int	        否		升级版本号
            channel_id	    varchar(50)	否	pc	渠道ID
            security	    varchar(64)	否		字段签名-验证访问合法性

            token           用户信息api调取令牌（登录之后由登录api服务返回）
         */

        $defaultHeader = [
            'device_name' => 'pc',
            'device_type' => '4',
            'system_version' => 'pc',
            'timestamp' => time(),
            'device_id' => 'pc',
            'version_no' => '1.0.0',
            'channel_id' => 'pc',
        ];
        if( $user = AuthService::userInfo()) {
            $defaultHeader['token'] = $user['token'];
        }

        $defaultHeader['security'] = self::getSecurity($defaultHeader);

        return $defaultHeader;
    }


    /**
     * @param $action
     * @param array $args
     * @param array $header
     * @param array $cookie
     * @param int $timeout 超时设置，缺省3秒
     * @return bool|mixed
     * @throws \Exception
     */
    public static function call($action, array $args = [], array $header = [], array $cookie=[], $timeout = 2)
    {
        $request = self::getRequest($action);

        if (!$request) {
            throw new \Exception('Call invalid action');
        }

        $curl = new Curl();
        $curl->setHeaders($header + self::getDefaultHeader());
        $curl->setCookies($cookie);
        $curl->setTimeout($timeout);

        if (! empty($args)) {
            $curl->{$request['method']}($request['url'], [
                'data' => Mcrypt::encrypt(API_AES_KEY, json_encode($args)),
            ]);
        } else {
            $curl->{$request['method']}($request['url']);
        }

        if ($curl->error) {
           // dd($curl->response);
           //TODO  add-log
            return $curl->curlErrorMessage;
        } else {
            $data = json_decode($curl->response, true);
            if (! is_array($data)) {
                echo __LINE__;
                //TODO add-log
                //dd($curl->response);
                return false;
            }

            return $data;
        }
    }


    public static function getSecurity(array $data)
    {
        ksort($data);
        $str = "";
        foreach($data as $k=>$v){
            $str .= sprintf('%s=%s&', $k, $v);
        }
        $str .= C('API.security_key');

        return md5($str);
    }


    /**
     * 并行api请求方式
     *
     * @param array $request
     * @param array $header
     * @param array $cookie
     * @param int $timeout
     * @return array
     * @throws \Exception
     */
    public static function multiCall(array $request, array $header = [], array $cookie = [], $timeout = 5)
    {
        if (self::$multiCalling) {
            throw new \Exception('Multi Calling!');
        }

        self::$multiCalling = true;
        self::$multiResponse = [];

        $multiCurl = new MultiCurl();
        $multiCurl->success(function($instance) use ($request) {
//            echo 'call to "' . $instance->url . '" was successful.' . "\n";
//            echo 'response:' . "\n";
//            var_dump($instance->response);

            $data =  json_decode($instance->response, true);

            if (! is_array($data)) {
                //todo
            } else {
                self::$multiResponse[$request[$instance->id]['action']] = $data;
            }
        });

        $multiCurl->error(function($instance) use ($request) {
            echo 'call to "' . $instance->url . '" was unsuccessful.' . "<br />";
            echo 'error code: ' . $instance->errorCode . "<br />";
            echo 'error message: ' . $instance->errorMessage . "<br />";
        });

        $multiCurl->complete(function($instance) {
//            echo 'call completed' . "\n";
        });

        $multiCurl->setHeaders($header + self::getDefaultHeader());
        $multiCurl->setCookies($cookie);
        $multiCurl->setTimeout($timeout);


        foreach ($request as $item) {

            $req = self::getRequest($item['action']);

            if (!$req) {
                throw new \Exception('Call invalid action');
            }

            $args = isset($item['args']) && is_array($item['args']) ? $item['args'] : [];
            if ($req['method'] == self::METHOD_GET) {
                $multiCurl->addGet($req['url'],
                    empty($args) ? [] : ['data' => Mcrypt::encrypt(API_AES_KEY, json_encode($args))]);
            } else {
                $multiCurl->addPost($req['url'],
                    empty($args) ? [] : ['data' => Mcrypt::encrypt(API_AES_KEY, json_encode($args))]);
            }
        }

        $multiCurl->start();

        self::$multiCalling= false;

        return self::$multiResponse;
    }


    /**
     *
     *
     * @param $response
     * @return array
     */
    public static function response2data($response)
    {
        if (is_array($response)
            && $response['error_code'] == self::ERR_OK
            && isset($response['data'])
        ) {
            return $response['data'];
        }

        return [];
    }

    public static function multiResponse2data($action, array $response)
    {
        if (isset($response[$action])) {
            return self::response2data($response[$action]);
        }

        return [];
    }


}