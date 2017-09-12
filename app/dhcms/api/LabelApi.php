<?php
/**
 * 表单信息
 */
namespace app\dhcms\api;
use \app\base\api\BaseApi;

class LabelApi extends BaseApi {
    /**
     * 栏目列表
     */
    public function categoryList(){
        $data = $this->data;
        $where='';
        //上级栏目
        if(isset($data['parent_id'])){
            $where['parent_id'] = $data['parent_id'];
        }
        if (isset($data['get_parent'])) {
        	$model = target('Page/CategoryPage');
        	$categoryInfo=$model->getInfo($data['class_id']);
        	$where['parent_id'] = $categoryInfo['parent_id'];
        }else{
			//指定栏目
	        if(!empty($data['class_id'])){
	            $where[] = 'class_id in ('.$data['class_id'].')';
	        }
        }
        //栏目属性
        if(isset($data['type'])){
            if($data['type']){
                $where['type'] = 1;
            }else{
                $where['type'] = 0;
            }
        }
        //其他条件
        if(!empty($data['where'])){
            $where[] = $data['where'];
        }
        //其他属性
        $where['show'] = 1;
        $model = target('dhcms/Category');
        $info = $model->loadData($where,$data['limit']);

        return $this->success($info);
    }
    /**
     * 内容列表
     */
    public function contentList(){
        $data = $this->data;
        $where=array();
        //指定栏目内容
        if(!empty($this->data['class_id'])){
            $classWhere = 'A.class_id in ('.$data['class_id'].')';
        }
        //指定栏目下子栏目内容
        if ($data['sub']&&!empty($data['class_id'])) {
            $classIds = target('dhcms/Category')->getSubClassId($data['class_id']);
            if(!empty($classIds)){
                $classWhere = "A.class_id in ({$classIds})";
            }
        }
        if(!empty($classWhere)){
            $where[] = $classWhere;
        }
        //是否带形象图
        if (isset($data['image'])) {
            if($data['image'] == true)
            {
                $where[] = 'A.image <> ""';
            }else{
                $where['A.image'] = '';
            }
        }
        //调用APP内容
        if(!empty($data['module'])){
            $where['B.app'] = $data['module'];
        }
        //排除ID
        if(!empty($data['not_id'])){
            $where[] = 'A.content_id not in('.$data['not_id'].')';
        }
        //调用推荐位
        if(!empty($data['pos_id'])){
            $where[] = 'find_in_set('.$data['pos_id'].',A.position) ';
        }
        //其他条件
        if (!empty($data['where'])) {
            $where[] = $data['where'];
        }
        //调用数量
        if (empty($data['limit'])) {
            $data['limit'] = 10;
        }
        //内容排序
        if(empty($data['order'])){
            $data['order']='A.time DESC,A.content_id DESC';
        }
        //其他属性
        $where['status'] = 1;
        $info = target('dhcms/Content')->loadList($where,$data['limit'],$data['order']);
        $data = array();
 		//格式化数据
        if(!empty($info)){
            foreach ($info as $key => $value) {
            	$data[$key] = $value;
                $data[$key]['time'] = date('Y-m-d',$value['time']);
            }
        }

        return $this->success($data);
    }
	
	/**
     * 表单token
     */
    public function formToken(){
        $data = $this->data;
		$where=array();
        if(empty($data['table'])){
            return ;
        }
        $where = array();
        $where['table'] = $data['table'];
        $formInfo = target('dhcms/FieldsetForm')->getWhereInfo($where);
        if(empty($formInfo)){
            return ;
        }
		$data = array();
		$data['token'] = target('dhcms/FieldsetForm')->setToken($data['table']);
		$this->success($data);
    }
	
    /**
     * 表单内容列表调用
     */
    public function formList()
    {
        $data = $this->data;

        if(empty($data['table'])){
            $this->error('表信息不存在',404);
        }
        //获取表单信息
        $where = array();
        $where['table'] = $data['table'];
        $formInfo = target('dhcms/FieldsetForm')->getWhereInfo($where);
        if(empty($formInfo)){
            $this->error('表信息不存在',404);
        }
        //设置模型
        $model = target('dhcms/FieldData');
        $model->setTable('ext_'.$formInfo['table']);
        //获取条件
        $where = array();
        if(!empty($formInfo['list_where'])){
            $where[] = $formInfo['list_where'];
        }
        if(!empty($data['where'])){
            $where[] = $data['where'];
        }
        if(empty($data['order'])){
            $data['order'] = 'data_id DESC';
        }
        if(empty($data['limit'])){
            $data['limit'] = 10;
        }
        //查询内容
        $list = $model->loadList($where,$data['limit'],$data['order']);
        //字段列表
        $where = array();
        $where['A.fieldset_id'] = $formInfo['fieldset_id'];
        $fieldList = target('dhcms/FieldForm')->loadList($where);
         //格式化表单内容为基本数据
        $data = array();

        if(!empty($list)){
            foreach ($list as $key => $value) {
                $data[$key]=$value;
                foreach ($fieldList as $v) {
                    $data[$key][$v['field']] = target('dhcms/FieldData')->revertField($value[$v['field']],$v['type'],$v['config']);
                }
            }
        }

        return $this->success($data);
    }

	/**
     * 碎片调用
     */
    public function fragment(){
    	$data = $this->data;
        $where=array();
        if(empty($data['mark'])){
            $this->error('字段为空',404);
        }
        if (strpos($data['mark'], ',') !== false) {
        	$mark = explode(',', $data['mark']);
        	$info = array();
        	foreach ($mark as $key => $value) {
        		$where['label'] = $value;
        		$var = target('dhcms/Fragment')->getWhereInfo($where);
        		$info[$value] = htmlspecialchars_decode(html_out($var['content']));
        	}
        }else{
			$where['label'] = $data['mark'];
        	$var = target('dhcms/Fragment')->getWhereInfo($where);
        	$info[$data['mark']] = htmlspecialchars_decode(html_out($var['content']));
        }
        if(empty($info)){
            $this->error('信息为空',404);
        }
        return $this->success($info);
    }
}