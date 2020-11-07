<?php
namespace App\Services;
use John\AliyunOSS\AliyunOSS;
class OSS
{
    private $ossClient;
    private static $bucketName;
    public function __construct($isInternal=false)
    {
        //下面第二个config少了一截，推测应该为config(key:'alioss.ossServer')
        $serverAddress=$isInternal ? config(key:'alioss.ossServerInternal') : config(key:'alioss.ossServer');
        $this->ossClient=AliyunOSS::boot(
            $serverAddress,
            config(key:'alioss.AcessKeyId'),
            config(key:'alioss.AccessKeySecret')
        );
    }
    public static function upload($ossKey,$filePath)
    {
        $oss=new OSS(isInternal:false);//上传文件使用内网，免流量费
        $oss->ossClient->setBucket(config(key:'alioss.BucketName'));
        $res=$oss->ossClient->uploadFile($ossKey,$filePath);
        return $res;
    }
    
}