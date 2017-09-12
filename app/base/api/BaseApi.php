<?php

/**
 * 基础API
 */

namespace app\base\api;

class BaseApi extends \framework\base\Api {

    public function __construct() {
        parent::__construct();
        define('APP_PATH', ROOT_PATH . 'app' . DIRECTORY_SEPARATOR);
        //引入扩展函数
        require_once(APP_PATH . 'base/util/Function.php');
        $siteConfig = target('admin/Config')->getInfo();
        $this->sys = $siteConfig;
        foreach ($siteConfig as $key => $value) {
            config($key, $value);
        }
		if(config('API_SWITCH')){
			$this->checkLink();
		}
        target('dhcms/TotalVisitor')->addApi();
    }

    /**
     * 检查链接码
     */
    private function checkLink() {
        if (config('API_TOKEN') <> $_SERVER['HTTP_TOKEN']) {
            $this->error('Link Error code', 403);
        }
    }


    /**
     * 分页数据
     * @param $pageLimit
     * @param $list
     * @param $pageData
     * @return array
     */
    protected function pageData($pageLimit, $list, $pageData) {
        return array(
            'limit' => $pageLimit,
            'limit' => count($list),
            'page' => $pageData['page'],
            'totalPage' => $pageData['totalPage']
        );
    }
	
	/**
     * 首页
     */
    public function index(){
		return $this->success("欢迎使用DHCMS API接口");
	}

}