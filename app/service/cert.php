<?php 
require_once('libs/Response.php');
require_once('libs/LibCertification.php');
require_once('libs/ResizeImg.php');

define('ROOT', dirname(dirname(__FILE__)));

$userName = $_GET['userName'];
$imgName = $_GET['imgName'];

$destination = ROOT."/public/upload/".$imgName;
$desmin = "../public/upload/min/cert/";
//制作工作正
$userInfo = Array(
        'userName'=>$userName
);
$dest = LibCertification::makeCertification($destination,$userInfo);
//print_r($dest);
$end = strrpos($dest,'/');
$cetrName = substr($dest,$end+1);
//压缩图片 453 * 714
$minimgName = substr($cetrName,0,strrpos($cetrName, '.'));
$result = resizeImg($dest, $desmin, $minimgName, 320, 504.37);

Response::show(200,'ok',Array('userName'=>$userName,'img'=>$cetrName));