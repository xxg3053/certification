<?php
ob_clean();//销毁输出缓冲区
//var_dump(gd_info());

$realname = "kenfo";
$schoolname = "xxxx大学";  
$idcard = "4523423142341234"; 

$image = imagecreatefrompng('certification.png'); // 证书模版图片文件的路径 
$red = imagecolorallocate($image,00,00,00);   // 字体颜色

// imageTTFText("Image", "Font Size", "Rotate Text", "Left Position","Top Position", "Font Color", "Font Name", "Text To Print");
imageTTFText($image, 10, 0, 10, 230, $red, 'simhei.ttf',$realname);
imageTTFText($image, 10, 0, 10, 250, $red, 'simhei.ttf', $schoolname);
imageTTFText($image, 15, 0, 10, 280, $red, 'simhei.ttf', $idcard);

 /* If you want to display the file in browser */
header('Content-type: image/png;');
ImagePng($image);
imagedestroy($image);


/* if you want to save the file in the web server */
$filename = 'certificate_aadarsh.png';
ImagePng($image, $filename);
imagedestroy($image);


/* If you want the user to download the file */
$filename = 'certificate_aadarsh.png';
ImagePng($image,$filename);
header('Pragma: public');
header('Cache-Control: public, no-cache');
header('Content-Type: application/octet-stream');
header('Content-Length: ' . filesize($filename));
header('Content-Disposition: attachment; filename="' .
 basename($filename) . '"');
header('Content-Transfer-Encoding: binary');
readfile($filename);
imagedestroy($image);