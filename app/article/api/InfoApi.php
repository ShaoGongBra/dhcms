<?php
/**
 * 文章信息
 */
namespace app\article\api;
use \app\base\api\BaseApi;

class InfoApi extends BaseApi {
	/**
     * 文章内容
     */
    public function index()
    {
    	$data = $this->data;
        $contentId =$data['content_id'];
        if(empty($contentId)) {
            $this->error('文章不存在',404);
        }
        $model = target('ContentArticle');
        //获取内容信息
        if(!empty($contentId)){
            $contentInfo=$model->getInfo($contentId);
        }else{
            $this->error('文章不存在',404);
        }
        $contentId = $contentInfo['content_id'];
        //信息判断
        if (!is_array($contentInfo)){
            $this->error('文章显示错误',404);
        }
        if(!$contentInfo['status']){
            $this->error('文章受保护',404);
        }
        //获取栏目信息
        $modelCategory = target('CategoryArticle');
        $categoryInfo=$modelCategory->getInfo($contentInfo['class_id']);
        if (!is_array($categoryInfo)){
            $this->error('文章栏目获取出错',404);
        }
        if($categoryInfo['app']<>APP_NAME){
            $this->error('系统错误',404);
        };
        //更新访问计数
        $where = array();
        $where['content_id'] = $contentId;
        target('dhcms/Content')->where($where)->setInc('views');

        $contentInfo['content'] = html_out($contentInfo['content']);
        //扩展模型
        if($categoryInfo['fieldset_id']){
            $extInfo = target('dhcms/FieldsetExpand')->getDataInfo($categoryInfo['fieldset_id'],$contentId);
            $contentInfo = array_merge($contentInfo , (array)$extInfo);
        }
        //上一篇
        $prevWhere = array();
        $prevWhere['A.status'] = 1;
        $prevWhere[] = 'A.time < '.$contentInfo['time'];
        $prevWhere['C.class_id'] = $categoryInfo['class_id'];
        $prevInfo=$model->getWhereInfo($prevWhere,' A.time DESC,A.content_id DESC');
        //下一篇
        $nextWhere = array();
        $nextWhere['A.status'] = 1;
        $nextWhere[] = 'A.time > '.$contentInfo['time'];
        $nextWhere['C.class_id'] = $categoryInfo['class_id'];
        $nextInfo=$model->getWhereInfo($nextWhere,' A.time ASC,A.content_id ASC');

        $contentInfo['time'] = date('Y-m-d',$contentInfo['time']);

        $data = array();
        $data['info'] = $contentInfo;
        $data['cate'] = $categoryInfo;
        $data['next'] = $nextInfo;
        $data['prev'] = $prevInfo;

        return $this->success($data);

    }
}