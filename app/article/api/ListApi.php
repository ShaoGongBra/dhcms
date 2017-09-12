<?php
namespace app\article\api;
use app\base\api\baseApi;
/**
 * 文章
 */
class ListApi extends baseApi {

	/**
     * 文章列表
     */
    public function index(){
    	$data = $this->data;
        $classId = $data['class_id'];
        $pageNum = $data['page'];
        if (empty($classId)) {
            $this->error();
        }
        //获取栏目信息
        $model = target('CategoryArticle');
        if(!empty($classId)){
            $categoryInfo=$model->getInfo($classId);
        }else{
            $this->error();
        }
        $classId = $categoryInfo['class_id'];
        //信息判断
        if (!is_array($categoryInfo)){
            $this->error();
        }
        if(strtolower($categoryInfo['app'])<>APP_NAME){
            $this->error();
        }
        //设置查询条件
        $where='';
        if ($categoryInfo['type'] == 0) {
            $classIds = target('dhcms/Category')->getSubClassId($classId);
        }
        if(empty($classIds)){
            $classIds = $categoryInfo['class_id'];
        }
        $where['A.status'] = 1;
        $where[] = 'C.class_id in ('.$classIds.')';

        //分页参数
        $size = intval($categoryInfo['page']);
        if (empty($size)) {
            $listRows = 20;
        } else {
            $listRows = $size;
        }
        //查询内容数据
        $modelContent = target('ContentArticle');
        if(!empty($categoryInfo['content_order'])){

            $categoryInfo['content_order'] = $categoryInfo['content_order'].',';
        }
        $_REQUEST['page'] = $pageNum;
        $pageList = $modelContent->page($listRows)->loadList($where,$limit,$categoryInfo['content_order'].'A.time desc,A.content_id desc',$categoryInfo['fieldset_id']);
        $this->pager = $modelContent->pager;
        $pagerInfo= $modelContent->pager;
        $info = array();
        foreach ($pageList as $key => $value) {
            $info[$key] =$value;
            $info[$key]['time'] = date('Y-m-d',$value['time']);
        }
        $list = array();
        $list['Info'] = $categoryInfo;
        $list['List'] = $info;

    	return $this->success($list);
    }
}