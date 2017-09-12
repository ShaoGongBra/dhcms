<?php
namespace app\dhcms\service;
/**
 * 标签接口
 */
class LabelService{
	/**
	 * 栏目列表
	 */
	public function categoryList($data){
        $where='';
        //上级栏目
        if(isset($data['parent_id'])){
            $where['parent_id'] = $data['parent_id'];
        }
        //指定栏目
        if(!empty($data['class_id'])){
            $where[] = 'class_id in ('.$data['class_id'].')';
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
        return $model->loadData($where,$data['limit']);
	}

    /**
     * 内容列表
     */
    public function contentList($data){
        $where=array();
        //指定栏目内容
        if(!empty($data['class_id'])){
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
        return target('dhcms/Content')->loadList($where,$data['limit'],$data['order']);
    }

    /**
     * 碎片调用
     */
    public function fragment($data){
        $where=array();
        if(empty($data['mark'])){
            return ;
        }
        $where['label'] = $data['mark'];
        $info = target('dhcms/Fragment')->getWhereInfo($where);
        if(empty($info)){
            return ;
        }
        return htmlspecialchars_decode(html_out($info['content']));
    }

    /**
     * 表单token
     */
    public function formToken($data){
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
        return target('dhcms/FieldsetForm')->setToken($data['table']);
    }

    /**
     * 文章链接调用
     */
    public function aurl($data){
        if(empty($data['content_id'])){
            return ;
        }
        $where=array();
        $where['content_id'] = $data['content_id'];
        $info = target('dhcms/Content')->getWhereInfo($where);
        if(empty($info)){
            return ;
        }
        return target('dhcms/Content')->getUrl($info);
    }
	
	/**
     * 文章标题调用
     */
    public function atitle($data){
        if(empty($data['content_id'])){
            return ;
        }
        $where=array();
        $where['content_id'] = $data['content_id'];
        $info = target('dhcms/Content')->getWhereInfo($where);
        if(empty($info)){
            return ;
        }
        return $info['title'];
    }

    /**
     * 栏目链接调用
     */
    public function curl($data){
        if(empty($data['class_id'])){
            return ;
        }
        $where=array();
        $where['class_id'] = $data['class_id'];
        $info = target('dhcms/Category')->getWhereInfo($where);
        if(empty($info)){
            return ;
        }
        return target('dhcms/Category')->getUrl($info);
    }
	
	/**
     * 栏目名称调用
     */
    public function cname($data){
        if(empty($data['class_id'])){
            return ;
        }
        $where=array();
        $where['class_id'] = $data['class_id'];
        $info = target('dhcms/Category')->getWhereInfo($where);
        if(empty($info)){
            return ;
        }
        return $info['name'];
    }
	
    /**
     * TAG列表调用
     */
    public function tagsList($data){

        $where = array();
        //解析TAG文字
        if(!empty($data['name'])){
            $str = $data['name'];
            $str = str_replace('，', ',', $str);
            $str = str_replace(' ', ',', $str);
            $name = explode(',', $str);
            $nameArray = array();
            foreach ($name as $key => $value) {
                $nameArray[$key]['name'] = $value;
                $nameArray[$key]['url'] = url('dhcms/TagsContent/index',array('name' =>$value));
                $nameArray[$key]['i'] = $key;
            }
            return $nameArray;
        }
        //数量
        if(empty($data['limit'])){
            $data['limit'] = 10;
        }
        //默认排序
        if(empty($data['order'])){
            $data['order'] = 'tag_id DESC';
        }
        return target('dhcms/Tags')->loadList($where, $data['limit'], $data['order']);
    }

    /**
     * 表单列表调用
     */
    public function formList($data)
    {
        if(empty($data['table'])){
            return array();
        }
        //获取表单信息
        $where = array();
        $where['table'] = $data['table'];
        $formInfo = target('dhcms/FieldsetForm')->getWhereInfo($where);
        if(empty($formInfo)){
            return array();
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
				$data[$key]['i'] = $key;
                foreach ($fieldList as $v) {
                    $data[$key][$v['field']] = target('dhcms/FieldData')->revertField($value[$v['field']],$v['type'],$v['config']);
                }
                $data[$key]['furl'] = url('DhCms/Form/info',array('name'=>$name,'id'=>$value['data_id']));          
            }
        }
        return $data;
    }
	
	/**
     * 表单名称调用
     */
    public function formName($data){
        if(empty($data['name'])){
            return ;
        }
        $where=array();
        $where['table'] = $data['name'];
        $info = target('dhcms/FieldsetForm')->getWhereInfo($where);
        if(empty($info)){
            return ;
        }
        return $info['name'];
    }
	
	/**
     * 选择类表单字段配置项调用
     */
    public function formSelectConfig($data)
    {
        if(empty($data['table']) || empty($data['field'])){
            return array();
        }
        //获取表单信息
        $where = array();
        $where['table'] = $data['table'];
        $formInfo = target('dhcms/FieldsetForm')->getWhereInfo($where);
        if(empty($formInfo)){
            return array();
        }
        //字段信息
        $where = array();
        $where['A.fieldset_id'] = $formInfo['fieldset_id'];
		$where['A.field'] = $data['field'];
        $fieldInfo = target('dhcms/FieldForm')->getWhereInfo($where);
		//解析配置
		$config = explode(",",$fieldInfo['config']);
		if(empty($config)){
			return array();
		}
		$default = explode(",",$fieldInfo['default']);
		$data = array();
		foreach($config as $key => $value){
			$data[$key]['title'] = $value;
			$data[$key]['value'] = $key+1;
			$data[$key]['default'] = 0;
			$data[$key]['i'] = $key;
			foreach($default as $key1 => $value1){
				if($value1 == $key+1){
					$data[$key]['default'] = 1;
					break;
				}
			}
		}
        return $data;
    }
	
	/**
     * 查询当天访问量
     */
    public function curNum(){
        return target('dhcms/TotalVisitor')->curNum();
    }
	
	/**
     * 查询总访问量
     */
    public function curTotalNum(){
		return target('dhcms/TotalVisitor')->curTotalNum();
    }
	
    /**
     * 查询当天API访问量
     */
    public function curApi(){
        return target('dhcms/TotalVisitor')->curApi();
    }
	
	/**
     * 查询总API访问量
     */
    public function curTotalApi(){
        return target('dhcms/TotalVisitor')->curTotalApi();
    }
}
