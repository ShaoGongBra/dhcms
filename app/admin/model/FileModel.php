<?php
namespace app\admin\model;
use app\base\model\BaseModel;
namespace app\admin\model;
use app\base\model\BaseModel;
#引入七牛
require_once ROOT_PATH . 'vendor/autoload.php';
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
/**
 * 文件操作
 */
class FileModel extends BaseModel {
    //完成
    protected $_auto = array (
        array('time','time',3,'function'),
     );

    /**
     * 上传数据
     * @return array 文件信息
     */
    public function uploadData()
    {
      $qiniu = load_config(CONFIG_PATH . 'qiniu.php');
      if($qiniu['QINIU_STATUS']){
        $auth = new Auth($qiniu['QINIU_AK'], $qiniu['QINIU_SK']);
        $token = $auth->uploadToken($qiniu['QINIU_BUCKET']);
        if (empty($_FILES['file'])) {
            $file = $_FILES['imgFile'];
        }else{
            $file = $_FILES['file'];
        }
        $filePath=$file['tmp_name'];
        $fileSize = $file['size'];
        #获取上传文件的后缀
        $string = $file['name'];
        $array = explode('.',$string);
        $file_ext =  $array[1];
        #保存的文件名
        $key = 'upload/'.date('Y-m-d').'/'.md5(time()).'.'.$file_ext;
        #开始上传
        $uploadMgr = new UploadManager();
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        if ($err !== null) {
            $this->error = $ree->error;
            return false;
        } else {
            $data = array();
            $data['url'] = $qiniu['QINIU_URL'].$ret['key'];
            return $data;
        }
      }else{
        $upload = target('base/Upload');
        $config = array();
        $config['DIR_NAME'] = date('Y-m-d');
        $data = $upload->upload($config);
        if(!$data){
            $this->error = $upload->getError();
            return false;
        }
        $this->add($data);
        return $data;
      }
    }

}
