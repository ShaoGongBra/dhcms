<?php
/**
 * 站点信息
 */
namespace app\home\api;

use \app\base\api\BaseApi;

class SiteApi extends BaseApi {

    /**
     * 获取系统信息
     */
    public function index() {
        $siteConfig = target('admin/Config')->getInfo();
        $data = array();
        foreach($siteConfig as $key=>$value) {
            $data[$key] = $value;
        }
        $this->success($data);
    }
}
