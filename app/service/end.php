<?php 
require_once('libs/Response.php');
require_once('libs/LibEnd.php');
require_once('libs/ResizeImg.php');

define('ROOT', dirname(dirname(__FILE__)));

$imgName = $_GET['imgName'];

$destination = "../public/upload/cert/".$imgName;
$desmin = "../public/upload/min/end/";
//制作工作正
$userInfo = Array(
        'imgName'=>$imgName
);
$dest = LibEnd::makeCertification($destination,$userInfo);
$end = strrpos($dest,'/');
$cetrName = substr($dest,$end+1);
$dimgName = substr($cetrName,0,strrpos($cetrName, '.'));

//压缩图片 912 1630
$result = resizeImg($dest, $desmin, $dimgName, 600, 1072);
//print_r($dest);

Response::show(200,'ok',Array('img'=>$cetrName));