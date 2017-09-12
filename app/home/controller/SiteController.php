<?php
namespace app\home\controller;
use app\base\controller\BaseController;
/**
 * 前台公共类
 */
class SiteController extends BaseController {
    public function __construct()
    {
        parent::__construct();
        //设置手机版参数
        if(isset($_GET['mobile']) || MOBILE){
            config('tpl_name' , config('mobile_tpl'));
        }
        //设置常量
        define('TPL_NAME', config('tpl_name'));
        //访问统计
        target('dhcms/TotalVisitor')->addData();
        target('dhcms/TotalSpider')->addData();
        target('dhcms/TotalSpiderInfo')->addData();
    }
    /**
     * 前台模板显示 调用内置的模板引擎
     * @access protected
     * @param string $name 模板名
     * @param bool $type 模板输出
     * @return void
     */
    protected function siteDisplay($name='',$type = true) {
        $tpl = THEME_NAME . '/' . TPL_NAME . '/' . $name;
        if($type){
            $this->display($tpl);
        }else{
            return $this->display($tpl,true);
        }
    }
    /**
     * 页面Meda信息组合
     * @return array 页面信息
     */
    protected function getMedia($title='',$keywords='',$description='')
    {
        if(empty($title)){
            $title=config('site_title').' - '.config('site_subtitle');
        }else{
            $title=$title.' - '.config('site_title').' - '.config('site_subtitle');
        }
        if(empty($keywords)){
            $keywords=config('site_keywords');
        }
        if(empty($description)){
            $description=config('site_description');
        }
        return array(
            'title'=>$title,
            'keywords'=>$keywords,
            'description'=>$description,
        );
    }
    //分页结果显示
    protected function getPageShow($map = array(), $mustParams = array())
    {
        $pageArray = $this->pager;
        if ($pageArray['totalPage']<2) {
           return;
        }
        $html = '
          <a href="'.$this->createPageUrl($map,$mustParams,$pageArray['firstPage']).'">首页</a>';
          if ($pageArray['page'] == $pageArray['firstPage']) {
              $html .='<span class="PreSpan">上一页</span>';
          }else{
              $html .='<a href="'.$this->createPageUrl($map,$mustParams,$pageArray['prevPage']).'">上一页</a>';
          }
          $html .='<div class="pagenum">';
            foreach ($pageArray['allPages'] as $value) {
                if($value == 0){
                    continue;
                }
                if($value == $pageArray['page']){
                    $html .= '<a class="Ahover"';
                }else{
                    $html .= '<a ';
                }
                $html .= ' href="'.$this->createPageUrl($map,$mustParams,$value).'">'.$value.'</a>';
           }
           $html .='</div>';
        if ($pageArray['page'] = $pageArray['lastPage']) {
            $html .='<span class="PreSpan next-page">下一页</span>';
        }else{
            $html .='<a href="'.$this->createPageUrl($map,$mustParams,$pageArray['nextPage']).'" >下一页</a>';
        }
         $html .= '<a class="NextA" href="'.$this->createPageUrl($map,$mustParams,$pageArray['lastPage']).'">末页</a>';
        return $html;
    }
}
