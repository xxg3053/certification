<?php 
require_once('ResizeImg.php');

// $destination = "../../public/upload/end/1461572282.png";
// $dir = "../../public/upload/min/end/";

// resizeImg($destination, $dir, "400_100", 300, 543);
// 
// //制作工作正
// $userInfo = Array(
//         'imgName'=>'1461569778.png'
// );
// $dest = LibEnd::makeCertification($destination,$userInfo);
//$certPath ="../../public/img/work.png"; 

 //$pinfo=pathinfo($certPath);
 //print_r($pinfo);
 //$certTemp =imagecreatefrompng($certPath); 
 //print_r($certTemp);
//$end = strrpos($certPath,'/');
// echo substr($certPath,$end+1);
// 
// 
// $filename = $destination;
// $degrees = 10;

// // Content type
// header('Content-type: image/jpeg');

// // Load
// $source = imagecreatefrompng($filename);

// // Rotate
// $rotate = imagerotate($source, $degrees, imageColorAllocateAlpha($source, 0, 0, 0, 127));

// // Output
// imagepng($rotate);
$dest = "./adsf/asdfa/aaa.png";
$end = strrpos($dest,'/');
$cetrName = substr($dest,$end+1);
$dimgName = substr($cetrName,0,strrpos($cetrName, '.'));
echo  $dimgName;
