<?php

/**
 * 公共API
 */

namespace framework\base;

class Api {

    protected $data = array();

    /**
     * Api constructor.
     */
    public function __construct() {
        //解决跨域问题
        header('Access-Control-Allow-Origin:'.$_SERVER["HTTP_ORIGIN"]);
        header('Access-Control-Allow-Headers:'.$_SERVER["HTTP_ACCESS_CONTROL_REQUEST_HEADERS"]);
        $request = $this->request();
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);
        $data = $data ? $data : array();
        $this->data = array_merge($request, $data);
    }
    /**
     * 获取请求数据
     * @param string $method
     * @param string $key
     * @param string $default
     * @param string $function
     * @return array|mixed|string
     */
    public function request($method = '', $key = '', $default = '', $function = '') {
        $method = strtolower($method);
        switch ($method) {
            case 'get':
                $data = $_GET;
                break;
            case 'post':
                $data = $_POST;
                break;
            case 'input':
                $data = file_get_contents('php://input');
                if($data) {
                    $data = json_decode($data, true);
                    $data = $data ? $data : [];
                }else {
                    $data = [];
                }
                break;
            default:
                $input = file_get_contents('php://input');
                $input = json_decode($input, true);
                $input = $input ? $input : [];
                $data = array_merge($_GET, $_POST, $input);
        }
        if ($key) {
            $data = $data[$key];
            if ($function) {
                $data = call_user_func($function, $data);
            }
            if (!empty($default) && empty($data)) {
                $data = $default;
            }
            if(is_string($data)) {
                $data = trim($data);
                if($data == 'null' || $vo == 'undefined') {
                    $data = null;
                }
                if($data == 'true') {
                    $data = true;
                }
                if($data == 'false') {
                    $data = false;
                }
            }
        }else {
            foreach ($data as $key => $vo) {
                if(is_string($vo)) {
                    $vo = trim($vo);
                    if($vo == 'null' || $vo == 'undefined') {
                        $data[$key] = null;
                    }
                    if($vo == 'true') {
                        $data[$key] = true;
                    }
                    if($vo == 'false') {
                        $data[$key] = false;
                    }
                }
            }
        }
        return $data;
    }
    /**
     * 返回成功数据
     * @param string $msg
     * @param array $data
     */
    public function success($data = array(), $msg = '') {
        if (empty($msg)) {
            $msg = 'ok';
        }
        $data = array(
            'code' => 200,
            'message' => $msg,
            'result' => $data
        );
        $this->returnData($data);
    }

    /**
     * 返回错误数据
     * @param int $code
     * @param string $msg
     */
    public function error($msg = '', $code = 500) {
        if (empty($msg)) {
            $msg = 'error';
        }
        $data = array(
            'code' => $code,
            'message' => $msg,
        );
        $this->returnData($data);
    }

    /**
     * 数据不存在
     * @param string $msg
     */
    public function error404($msg = '记录不存在') {
        $this->error($msg, 404);
    }

    /**
     * 返回数据
     * @param $data
     * @param string $type
     */
    public function returnData($data, $type = 'json') {
        $format = $this->request('', 'format');
        if (empty($format)) {
            $format = $type;
        }
        $callback = request('', 'callback');
        $format = strtolower($format);
        $charset = $this->data['charset'] ? $this->data['charset'] : 'utf-8';

        switch ($format) {
            case 'jsonp' :
                call_user_func_array(array($this, 'return' . ucfirst($format)), array($data, $callback, $charset));
                break;
            case 'json':
            default:
                call_user_func_array(array($this, 'return' . ucfirst($format)), array($data, $charset));
        }
    }

    /**
     * 返回JSON数据
     * @param array $data
     * @param string $charset
     */
    public function returnJson($data = array(), $charset = "utf-8") {
        header("Content-Type: application/json; charset={$charset};");
        echo json_encode($data);
        exit;
    }

    /**
     * 返回JSONP数据
     * @param array $data
     * @param string $callback
     */
    public function returnJsonp($data = array(), $callback = 'q', $charset = "utf-8") {
        header("Content-Type: application/javascript; charset={$charset};");
        echo $callback . '(' . json_encode($data) . ');';
        exit;
    }

}