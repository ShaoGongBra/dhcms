<?php
namespace app\page\api;
use app\base\api\baseApi;
/**
 * 单页面
 */
class InfoApi extends baseApi {

	/**
     * 栏目页
     */
    public function index(){
        $classId = $this->data['class_id'];
        //获取栏目信息
        $model = target('Page/CategoryPage');
        $categoryInfo=$model->getInfo($classId);
        //信息判断
        if (!is_array($categoryInfo)){
            $this->error('栏目不存在',200);
        }
        if($categoryInfo['app']<>APP_NAME){
            $this->error('栏目信息错误',200);
        }
        $categoryInfo['content'] = html_out($categoryInfo['content']);
    	return $this->success($categoryInfo);
    }
}