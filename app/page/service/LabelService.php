<?php
namespace app\page\service;
/**
 * 标签接口
 */
class LabelService{
	/**
	 * 栏目列表
	 */
	public function content($data){
        $where='';
        //指定栏目
        if(isset($data['class_id'])){
            $where['A.class_id'] = $data['class_id'];
        }
        //栏目属性
        if(isset($data['type'])){
            if($data['type']){
                $where['A.type'] = 1;
            }else{
                $where['A.type'] = 0;
            }
        }
        //其他条件
        if(!empty($data['where'])){
            $where[] = $data['where'];
        }
        //其他属性
        $where['A.show'] = 1;
        $model = target('page/CategoryPage');
        $info = $model->getWhereInfo($where);
        if ($data['field'] == 'content') {
            return html_out($info['content']);
        }else{
            return $info[$data['field']];
        }
	}

}
