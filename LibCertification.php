<?php

class LibCertification{
    
     // imageTTFText("Image", "Font Size", "Rotate Text", "Left Position","Top Position", "Font Color", "Font Name", "Text To Print");
    public static function makeCertification($img){
        $realname = "kenfo";
        $schoolname = "x大学";  
        $idcard = "4523423"; 
        $iinfo=getimagesize($img,$iinfo); 
        switch ($iinfo[2]) {
            case 1:
                $image = imagecreatefromgif($img); // 证书模版图片文件的路径 
                $red = imagecolorallocate($image,00,00,00);   // 字体颜色

                imageTTFText($image, 8, 0, 98, 220, $red, 'simhei.ttf',$realname);
                imageTTFText($image, 8, 0, 98, 240, $red, 'simhei.ttf', $schoolname);
                imageTTFText($image, 8, 0, 98, 255, $red, 'simhei.ttf', $idcard);

                imagejpeg($image,$img);
                imagedestroy($image);
                break;
            case 2: 
                $image = imagecreatefromjpeg($img); // 证书模版图片文件的路径 
                $red = imagecolorallocate($image,00,00,00);   // 字体颜色

               
                imageTTFText($image, 8, 0, 98, 220, $red, 'simhei.ttf',$realname);
                imageTTFText($image, 8, 0, 98, 240, $red, 'simhei.ttf', $schoolname);
                imageTTFText($image, 8, 0, 98, 255, $red, 'simhei.ttf', $idcard);

                imagejpeg($image,$img);
                imagedestroy($image);
                break;
            case 3:
                $image = imagecreatefrompng($img); // 证书模版图片文件的路径 
                $red = imagecolorallocate($image,00,00,00);   // 字体颜色

             
                imageTTFText($image, 8, 0, 98, 220, $red, 'simhei.ttf',$realname);
                imageTTFText($image, 8, 0, 98, 240, $red, 'simhei.ttf', $schoolname);
                imageTTFText($image, 8, 0, 98, 255, $red, 'simhei.ttf', $idcard);

                ImagePng($image,$img);
                imagedestroy($image);
                break;
            case 6:
                $image = imagecreatefromwbmp($img); // 证书模版图片文件的路径 
                $red = imagecolorallocate($image,00,00,00);   // 字体颜色

                imageTTFText($image, 8, 0, 98, 220, $red, 'simhei.ttf',$realname);
                imageTTFText($image, 8, 0, 98, 240, $red, 'simhei.ttf', $schoolname);
                imageTTFText($image, 8, 0, 98, 255, $red, 'simhei.ttf', $idcard);

                imagewbmp($image,$img);
                imagedestroy($image);
                break; 
            default:  
            die("不支持的文件类型");  
            exit; 
        
        }
       
    }
}