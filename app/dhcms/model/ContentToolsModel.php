<?php
namespace app\dhcms\model;
use app\base\model\BaseModel;
#引入七牛
require_once ROOT_PATH . 'vendor/autoload.php';
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
/**
 * 内容工具
 */
class ContentToolsModel {

    /**
     * 获取内容指定图片
     * @param string $content 内容
     * @param int $num 第N张图片
     * @return string 图片URL
     */
    public function getImage($content, $num = 1)
    {
        $content = html_out($content);
        $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"]/i', $content, $matches);
        $num = $num - 1;
        $img = $matches[1][$num];
        return $img;
    }

    /**
     * 获取关键词
     * @param string $title 标题
     * @param string $content 内容
     * @return string 图片URL
     */
    public function getKerword($title, $content = '')
    {
        $data= \framework\ext\Http::doGet('http://keyword.discuz.com/related_kw.html?ics=utf-8&ocs=utf-8&title='.urlencode($title).'&content='.urlencode($content),10);
        if(empty($data)){
            return;
        }
        preg_match_all("/<kw>(.*)A\[(.*)\]\](.*)><\/kw>/",$data, $list, PREG_SET_ORDER);
        if(empty($list)){
            return;
        }
        $keywords = array();
        foreach ($list as $value) {
            $keywords[] = $value[2];
        }
        return implode(',', $keywords);
    }
    /**
     * 获取百度推荐词
     * @param string $title 标题
     * @param string $content 内容
     * @return string 图片URL
     */
    public function getBaiduKey($word)
    {
      $data= \framework\ext\Http::doGet('http://suggestion.baidu.com/su?ie=utf-8&wd='.$word,10);
      if(empty($data)){
          return;
      }
      $result = preg_replace('/.*\((.+)\).*/', '$1', $data);
      $result = str_replace('q:','"q":',$result);
      $result = str_replace('p:','"p":',$result);
      $result = str_replace('s:','"s":',$result);
      $list = json_decode($result,true);
      if(empty($list)){
        return;
      }
      return implode(',', $list['s']);
    }

    /**
     * 远程抓图
     * @param string $content 内容
     * @return string 抓取后内容
     */
    public function getRemoteImage($content)
    {
        if(empty($content)){
            return $content;
        }
        $filesName = date('Y-m-d').'/';
        //文件路径
        $filePath = './uploads/'.$filesName;
        //文件URL路径
        $fileUrl = __ROOT__ .'/uploads/'. $filesName;
        $body=htmlspecialchars_decode($content);
        $imgArray = array();
        preg_match_all("/(src|SRC)=[\"|'| ]{0,}(http:\/\/(.*)\.(gif|jpg|jpeg|bmp|png))/isU",$body,$imgArray);
        $imgArray = array_unique($imgArray[2]);
        set_time_limit(0);
        $milliSecond = date("dHis") . '_';
        if(!is_dir($filePath)) @mkdir($filePath,0777,true);
        $http = new \framework\ext\Http;
        $qiniu = load_config(CONFIG_PATH . 'qiniu.php');
        if($qiniu['QINIU_STATUS']){
          foreach($imgArray as $key =>$value)
          {
              $auth = new Auth($qiniu['QINIU_AK'], $qiniu['QINIU_SK']);
              $value = trim($value);
              $ext=explode('.', $value);
              $ext=end($ext);
              #保存的文件名
              $key = 'upload/'.date('Y-m-d').'/'.md5(time()).'.'.$ext;
              #开始上传
              $bucketMgr = new BucketManager($auth);
              list($ret, $err) = $bucketMgr->fetch($value, $qiniu['QINIU_BUCKET'],$key);
              if ($err == null) {
                $body = str_replace($value,$qiniu['QINIU_URL'].$ret['key'],$body);
              }

          }
        }else{
          foreach($imgArray as $key =>$value)
          {
            $value = trim($value);
            $ext=explode('.', $value);
            $ext=end($ext);
            $getFile = $http->doGet($value,5);
            $getfileName = $milliSecond.$key.'.'.$ext;
            $getFilePath = $filePath.$getfileName;
            $getFileUrl = $fileUrl.$getfileName;
            if($getFile){
                if(@file_put_contents($getFilePath, $getFile)){
                    $body = str_replace($value,$getFileUrl,$body);
                }
            }
          }
        }
        return $body;

    }

}
