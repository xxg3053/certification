<?php
require_once('libs/Response.php');
require_once('libs/LibCertification.php');
require_once('libs/File.php');

define('ROOT', dirname(dirname(__FILE__)));

$max_file_size=2000000;
$imgPath= ROOT."/public/upload/";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	 if(!is_uploaded_file($_FILES["file"]['tmp_name'])){
         Response::show(100,'图片不存在!');
	 }
	$file = $_FILES["file"];  
    if($max_file_size < $file["size"]){  
         Response::show(101,'文件太大!');
    }  
   // echo $file["type"];
    if(!file_exists($imgPath)){  
        mkdir($imgPath);  
    }
    $filename=$file["tmp_name"];
   //echo $filename;
    $image_size = getimagesize($filename);  
    $pinfo=pathinfo($file["name"]);  
    $ftype=$pinfo['extension'];  
    $destination = $imgPath.time().".".$ftype;
    if (file_exists($destination) && $overwrite != true){  
        Response::show(102,'同名文件已经存在了');
    }  
  
    if(!move_uploaded_file ($filename, $destination)){   
        Response::show(103,'同名文件已经存在了');
    }
    
    $pinfo=pathinfo($destination);  
    $fname=$pinfo['basename']; 
    //$file = new File();
    //$file->debug("aaa");
    Response::show(200,'上传成功',Array('fname'=>$fname));
}