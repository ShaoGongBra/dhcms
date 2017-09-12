<?php
/**
 * 表单信息
 */
namespace app\dhcms\api;

use \app\base\api\BaseApi;

class FormApi extends BaseApi {

    /**
     * 列表
     */
    public function index(){
        $name = urldecode($this->data['name']);
        $table = len($name,0,20);
        if(empty($table)){
            $this->error('表单信息不能为空',404);
        }
        //获取表单信息
        $where = array();
        $where['table'] = $table;
        $formInfo = target('dhcms/FieldsetForm')->getWhereInfo($where);
        if(empty($formInfo)){
            $this->error('表单信息不存在',404);
        }
        if(!$formInfo['show_list']){
            $this->error('该表单无法获取',404);
        }
        //分页参数
        $size = intval($formInfo['list_page']);
        if (empty($size)) {
            $listRows = 20;
        } else {
            $listRows = $size;
        }
        //设置模型
        $model = target('dhcms/FieldData');
        $model->setTable('ext_'.$formInfo['table']);
        //查询数据
        $where = array();
        if(!empty($formInfo['list_where'])){
            $where[] = $formInfo['list_where'];
        }
        //查询内容
        $list = $model->page($listRows)->loadList($where,$limit,$formInfo['list_order']);
        $this->pager = $model->pager;
        //字段列表
        $where = array();
        $where['A.fieldset_id'] = $formInfo['fieldset_id'];
        $fieldList = target('FieldForm')->loadList($where);
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
        //获取分页
        //$page = $this->getPageShow($pageMaps);
        //$data = array('pageList'=>$pageList,'page'=>$page);
        return $this->success($data);
    }

    /**
     * 表单内容
     */
    public function info(){
        $name = urldecode($this->data['name']);
        $table = len($name,0,20);
        $id = $this->data['id'];
        if(empty($table)||empty($id)){
            $this->error('参数不能为空', 404);
        }
        //获取表单信息
        $where = array();
        $where['table'] = $table;
        $formInfo = target('dhcms/FieldsetForm')->getWhereInfo($where);
        if(empty($formInfo)){
            $this->error('表单信息不存在', 404);
        }
        if(!$formInfo['show_info']){
            $this->error('该表单无法获取', 404);
        }
        //设置模型
        $model = target('dhcms/FieldData');
        $model->setTable('ext_'.$formInfo['table']);
        $info = $model->getInfo($id);
        if(empty($info)){
             $this->error('信息获取失败', 404);
        }
        //字段列表
        $where = array();
        $where['A.fieldset_id'] = $formInfo['fieldset_id'];
        $fieldList = target('FieldForm')->loadList($where);
        //格式化表单内容为基本数据
        foreach ($fieldList as $v) {
            $info[$v['field']] = target('dhcms/FieldData')->revertField($info[$v['field']],$v['type'],$v['config']);
        }

        return $this->success($info);
    }

    /**
     * 发布
     */
    public function push(){
        if(!IS_POST){
            $this->error('非法操作',404);
        }
        $datas = $this->data;
        $table = $datas['table'];
        $token = $datas['token'];
        $token = trim($token);
        if(empty($table)){
            $this->error('参数不能为空',404);
        }

        //获取表单信息
        $where = array();
        $where['table'] = $table;
        $formInfo = target('dhcms/FieldsetForm')->getWhereInfo($where);
        if(empty($formInfo)){
            $this->error('信息错误',404);
        }
        if(!$formInfo['post_status']){
            $this->error('不允许提交',404);
        }
        $data = array();
        foreach ($datas as $key => $value) {
            $data['Fieldset_'.$key] = $value;
        }
        $_POST = $data;
        //设置模型
        $model = target('dhcms/FieldData');
        $model->setTable('ext_'.$formInfo['table']);
        //增加信息
        if ($model->saveData('add',$formInfo)){
            return $this->success($formInfo['post_msg']);
        }else{
            $msg = $model->getError();
            if (empty($msg))
            {
                $this->error($formInfo['name'].'发布失败，请刷新后重新尝试！');
            }else{
                $this->error($msg);
            }
        }
    }
}