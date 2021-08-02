<?php

namespace qiniuoss\common;

use tpext\builder\inface\Storage;
use tpext\builder\common\model\Attachment;
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;
use UnexpectedValueException;

class OssStorage implements Storage
{
    /**
     * Undocumented function
     *
     * @param Attachment $attachment
     * @return string url
     */
    public function process($attachment)
    {
        $config = Module::getInstance()->getConfig();

        $accessKey = $config['access_key'];
        $secretKey = $config['secret_key'];
        $region = $config['region'];
        $domain = rtrim($config['domain'], '/');
        $bucketName = $config['bucket_name'];

        if (empty($accessKey) || empty($secretKey) || empty($region) || empty($bucketName)) {
            trace('七牛云cos配置有误');
            $this->delete($attachment);
            return '';
        }

        try {
            $bucketExists = false;

            $auth = new Auth($accessKey, $secretKey);
            $bucketMgr = new BucketManager($auth);

            $bucketList = $bucketMgr->buckets();


            foreach ($bucketList as $bu) {
                if ($bu == $bucketName) {
                    $bucketExists = true;
                    break;
                }
            }

            if (!$bucketExists) { //存储桶不存在，创建
                $bucketMgr->createBucket($bucketName, $region);
            }
        } catch (\Throwable $e) {
            trace('七牛云cosClient初始化失败');
            trace($e->__toString());
            $this->delete($attachment);
            return '';
        }

        try {
            // 生成上传Token
            $token = $auth->uploadToken($bucketName);
            // 构建 UploadManager 对象
            $uploadMgr = new UploadManager();

            $name = preg_replace('/^.+?\/([^\/]+)$/', '$1', $attachment['url']); //获取带后缀的文件名

            $res = $uploadMgr->putFile($token, $name, '.' . $attachment['url']);

            trace(json_encode($res));

            if ($res && count($res) && isset($res[0]['key'])) {

                $url = "{$domain}/{$res[0]['key']}";

                $ossUrl = $url;
                $ossUrl = '//' . preg_replace('/^https?:\/\//', '', $ossUrl); //去掉http协议头，以//开头
                $attachment['url'] = $ossUrl;
                $attachment->save();
                return $ossUrl;
            } else {
                throw new UnexpectedValueException('未知错误');
            }
        } catch (\Throwable $e) {
            trace('七牛云Oss文件上传失败');
            trace($e->__toString());
            $this->delete($attachment);
            return '';
        }

        return '';
    }

    /**
     * OSS操作失败，删除数据库已保存的记录和已上传的文件
     *
     * @param Attachment $attachment
     * @return boolean
     */
    private function delete($attachment)
    {
        $res1 = Attachment::where('id', $attachment['id'])->delete();
        $res2 = @unlink('.' . $attachment['url']);

        return $res1 && $res2;
    }
}
