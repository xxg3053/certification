<?php 
require_once('LibCertification.php');
/******************************************************************************
参数说明: 
$max_file_size  : 上传文件大小限制, 单位BYTE 
$destination_folder : 上传文件路径 
$watermark   : 是否附加水印(1为加水印,其他为不加水印); 
 
使用说明: 
1. 将PHP.INI文件里面的"extension=php_gd2.dll"一行前面的;号去掉,因为我们要用到GD库; 
2. 将extension_dir =改为你的php_gd2.dll所在目录; 
******************************************************************************/  
  
//上传文件类型列表  
$uptypes=array(  
    'image/jpg',  
    'image/jpeg',  
    'image/png',  
    'image/pjpeg',  
    'image/gif',  
    'image/bmp',  
    'image/x-png'  
);  
  
$max_file_size=2000000;     //上传文件大小限制, 单位BYTE  
$destination_folder="uploadimg/"; //上传文件路径  
$watermark=1;      //是否附加水印(1为加水印,其他为不加水印);  
$watertype=2;      //水印类型(1为文字,2为图片)  
$waterposition=1;     //水印位置(1为左下角,2为右下角,3为左上角,4为右上角,5为居中);  
$waterstring="kenfo";  //水印字符串  
$waterimg="work.png";    //水印图片  
$imgpreview=1;      //是否生成预览图(1为生成,其他为不生成);  
$imgpreviewsize=1/1;    //缩略图比例  

if ($_SERVER['REQUEST_METHOD'] == 'POST'){  
    if (!is_uploaded_file($_FILES["upfile"]['tmp_name']))  
    //是否存在文件  
    {  
         echo "图片不存在!";  
         exit;  
    }  
  
    $file = $_FILES["upfile"];  
    if($max_file_size < $file["size"])  
    //检查文件大小  
    {  
        echo "文件太大!";  
        exit;  
    }  
  
    if(!in_array($file["type"], $uptypes))  
    //检查文件类型  
    {  
        echo "文件类型不符!".$file["type"];  
        exit;  
    }  
  
    if(!file_exists($destination_folder))  
    {  
        mkdir($destination_folder);  
    }  
  
    $filename=$file["tmp_name"];  
    $image_size = getimagesize($filename);  
    $pinfo=pathinfo($file["name"]);  
    $ftype=$pinfo['extension'];  
    $destination = $destination_folder.time().".".$ftype;  
    if (file_exists($destination) && $overwrite != true)  
    {  
        echo "同名文件已经存在了";  
        exit;  
    }  
  
    if(!move_uploaded_file ($filename, $destination))  
    {  
        echo "移动文件出错";  
        exit;  
    }  
  
    $pinfo=pathinfo($destination);  
    $fname=$pinfo['basename'];  
   
  
    if($watermark==1){  
        $iinfo=getimagesize($destination,$iinfo);  
        $nimage=imagecreatetruecolor($image_size[0],$image_size[1]);  
        $white=imagecolorallocate($nimage,255,255,255);  
        $black=imagecolorallocate($nimage,0,0,0);  
        $red=imagecolorallocate($nimage,255,0,0);  
        imagefill($nimage,0,0,$white);  
        switch ($iinfo[2])  
        {  
            case 1:  
            $simage =imagecreatefromgif($destination);  
            break;  
            case 2:  
            $simage =imagecreatefromjpeg($destination);  
            break;  
            case 3:  
            $simage =imagecreatefrompng($destination);  
            break;  
            case 6:  
            $simage =imagecreatefromwbmp($destination);  
            break;  
            default:  
            die("不支持的文件类型");  
            exit;  
        }  
  
        imagecopy($nimage,$simage,0,0,0,0,$image_size[0],$image_size[1]);  
        imagefilledrectangle($nimage,1,$image_size[1]-15,80,$image_size[1],$white);  
  
        switch($watertype)  
        {  
            case 1:   //加水印字符串  
            imagestring($nimage,2,3,$image_size[1]-15,$waterstring,$black);  
            break;  
            case 2:   //加水印图片
            $nImg = ImageCreateTrueColor(74,82);   //新建一个真彩色画布
            ImageCopyReSampled($nImg,$nimage,0,0,0,0,74,82,$image_size[0],$image_size[1]);//重采样拷贝部分图像并调整大小
            //ImageJpeg ($nImg,$Image);     //以JPEG格式将图像输出到浏览器或文件
            imagedestroy($nimage);
            $simage1 =imagecreatefrompng("work.png"); 
            //imagecopy ( resource $dst_im , resource $src_im , int $dst_x , int $dst_y , int $src_x , int $src_y , int $src_w , int $src_h )
            
            imagecopy($simage1,$nImg,76,114,0,0,74,82);  
           
            break;  
        }  
  
        switch ($iinfo[2])  
        {  
            case 1:  
            //imagegif($nimage, $destination);  
            imagejpeg($simage1, $destination);  
            break;  
            case 2:  
            imagejpeg($simage1, $destination);  
            break;  
            case 3:  
            imagepng($simage1, $destination);  
            break;  
            case 6:  
            imagewbmp($simage1, $destination);   
            break;  
        }  
  
        //覆盖原上传文件  
       // imagedestroy($nimage);  
        imagedestroy($simage);  
    }  
    
     LibCertification::makeCertification($destination);
     
    $image_size = getimagesize("work.png"); 
    if($imgpreview==1)  
    {  
    echo "<br>look:<br>";  
    echo "<img src=\"".$destination."\" width=".($image_size[0]*$imgpreviewsize)." height=".($image_size[1]*$imgpreviewsize);  
    echo " alt=\"图片预览:\r文件名:".$destination."\r上传时间:\">";  
    }  
} 