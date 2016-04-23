<?php 
require_once('libs/Response.php');
require_once('libs/LibCertification.php');

define('ROOT', dirname(dirname(__FILE__)));

$userName = $_GET['userName'];
$imgName = $_GET['imgName'];

$destination = ROOT."/public/upload/".$imgName;
//制作工作正
$userInfo = Array(
        'userName'=>$userName
);
$dest = LibCertification::makeCertification($destination,$userInfo);
//print_r($dest);
$end = strrpos($dest,'/');
$cetrName = substr($dest,$end+1);
Response::show(200,'ok',Array('userName'=>$userName,'img'=>$cetrName));