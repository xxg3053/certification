<?php
require_once('File.php');

/**
 * 制作最后模版
 */
class LibEnd{

    static $workTemplateImg = "../public/img/template/end.png"; //工作证模版
    static $workCertDir = "../public/upload/end/"; //工作存放的地方
    //相关配置
    static $info = Array(
            //员工姓名 imageTTFText
            'TTF_FONT_SIZE' => 18,
            'TTF_ROTATE_TEXT' => 0,
            'TTF_LEFT_POSITION' => 175,
            'TTF_TOP_POSITION' => 469,
            'TTF_FONT_RED' => 235,
            'TTF_FONT_GREER' => 104,
            'TTF_FONT_BLUE' => 119,
            'TTF_FONT_TYPE' => '../service/libs/simhei.ttf',
            //放大头照的模版大小
            'HEAD_WIDHT' => 560,//360+100  //453
            'HEAD_HEIGHT' => 768,//568+100 //714
            //将用户上传的照片压缩到模版上 ImageCopyReSampled
            'SAMPL_DST_X' => 0,
            'SAMPL_DST_Y' => 0,
            'SAMPL_SRC_X' => 0,
            'SAMPL_SRC_Y' => 0,
            'SAMPL_DST_W' => 360,//360+100
            'SAMPL_DST_H' => 568,//568+100
            'SAMPL_SRC_W' => 0,
            'SAMPL_SRC_H' => 0,
            //旋转角度
            'WORK_ROTATE' => 7.5,
             //将大头贴拷贝到工作证上 imagecopy
            'COPY_DST_X' => 165,
            'COPY_DST_Y' => 500,
            'COPY_SRC_X' => 0,
            'COPY_SRC_Y' => 0,
            'COPY_SRC_W' => 360,//-100
            'COPY_SRC_H' => 568 //-100

        );

    
    /* 向工作证中写入个人信息
     * @param  [type]
     * @param  [type]
     * @return [type]
     */
    public static function imageTTFText_($image,$userInfo){
        $nameColor = imagecolorallocate($image,self::$info['TTF_FONT_RED'],self::$info['TTF_FONT_GREER'],self::$info['TTF_FONT_BLUE']);   // 字体颜色
        $userName = $userInfo['userName'];
        $leftPos = self::$info['TTF_LEFT_POSITION'];
        $topPos = self::$info['TTF_TOP_POSITION'];
        $len = strlen($userName);
        if($len > 9){
            $leftPos = $leftPos - ($len - 9) * 5;
        }else if($len < 9){
            $leftPos = $leftPos + (9 - $len) * 5;
        }
        imageTTFText($image, self::$info['TTF_FONT_SIZE'], self::$info['TTF_ROTATE_TEXT'], $leftPos, $topPos, $nameColor, self::$info['TTF_FONT_TYPE'],$userName);

        return $image;
    }

    public static function writeTextByPng($destination,$userInfo){
        $image = imagecreatefrompng($destination); // 证书模版图片文件的路径 
        $image = self::imageTTFText_($image,$userInfo);
        ImagePng($image,$destination);
        imagedestroy($image);
        return $destination;
    }
    public static function writeTextByGif($destination,$userInfo){
        $image = imagecreatefromgif($destination); // 证书模版图片文件的路径 
        $image = self::imageTTFText_($image,$userInfo);
        imagejpeg($image,$destination);
        imagedestroy($image);
        return $destination;
    }
    public static function writeTextByJpeg($destination,$userInfo){
        $image = imagecreatefromjpeg($destination); // 证书模版图片文件的路径 
        $image = self::imageTTFText_($image,$userInfo);
        imagejpeg($image,$destination);
        imagedestroy($image);
        return $destination;
    }
    
    public static function writeTextByWbmp($destination,$userInfo){
        $image = imagecreatefromwbmp($destination); // 证书模版图片文件的路径 
        $image = self::imageTTFText_($image,$userInfo);
        imagewbmp($image,$destination);
        imagedestroy($image);
        return $destination;
    }

    public static function switchImg($iinfo,$destination,$userInfo){
        switch ($iinfo[2]) {
            case 1:
                return self::writeTextByGif($destination,$userInfo);
                break;
            case 2: 
                return self::writeTextByJpeg($destination,$userInfo);
                break;
            case 3:
                return self::writeTextByPng($destination,$userInfo);
                break;
            case 6:
                return self::writeTextByWbmp($destination,$userInfo);
                break; 
            default:  
            die("不支持的文件类型");  
            exit; 
        
        }
    }
    
    public static function createHead($img){
        $tImg = imagecreatefrompng($img);
        $image_size = getimagesize($img);

        //新建一个真彩色画布
        $nImg = ImageCreateTrueColor(self::$info['HEAD_WIDHT'],self::$info['HEAD_HEIGHT']);   
        //重采样拷贝部分图像并调整大小
        ImageCopyReSampled($nImg,$tImg,self::$info['SAMPL_DST_X'],self::$info['SAMPL_DST_Y'],self::$info['SAMPL_SRC_X'],self::$info['SAMPL_SRC_Y'],self::$info['SAMPL_DST_W'],self::$info['SAMPL_DST_H'],$image_size[0],$image_size[1]);

        $certPath = self::$workTemplateImg;
        $certTemp =imagecreatefrompng($certPath); 
       // $nImg = imagerotate($nImg,self::$info['WORK_ROTATE'],imageColorAllocateAlpha($nImg, 0, 0, 0, 127));
         //将大头贴拷贝到工作证上
        imagecopy($certTemp,$nImg,self::$info['COPY_DST_X'],self::$info['COPY_DST_Y'],self::$info['COPY_SRC_X'],self::$info['COPY_SRC_Y'],self::$info['COPY_SRC_W'],self::$info['COPY_SRC_H']); 
        // 
        //imagecopy($certTemp,$nImg,self::$info['COPY_DST_X'],self::$info['COPY_DST_Y'],self::$info['COPY_SRC_X'],self::$info['COPY_SRC_Y'],imagesx($nImg),imagesy($nImg));
        $destination_folder = self::$workCertDir;
        $pinfo=pathinfo($certPath);
        $ftype=$pinfo['extension'];  
        $destination = $destination_folder.time().".".$ftype;  
        //echo($destination);
        imagepng($certTemp, $destination);
        //move_uploaded_file($certTemp, $destination);
        //echo $destination;
        return $destination;
    }

    public static function createHead2($img){
        $tImg = imagecreatefrompng($img);
        
        $tImg = imagerotate($tImg,self::$info['WORK_ROTATE'],imageColorAllocateAlpha($tImg, 0, 0, 0, 127));
       
        $certPath = self::$workTemplateImg;
        $certTemp =imagecreatefrompng($certPath); 
        
        //$image_size = getimagesize($tImg);

         //将大头贴拷贝到工作证上
        imagecopy($certTemp,$tImg,self::$info['COPY_DST_X'],self::$info['COPY_DST_Y'],self::$info['COPY_SRC_X'],self::$info['COPY_SRC_Y'],imagesx($tImg),imagesy($tImg)); 
        // 
        //imagecopy($certTemp,$nImg,self::$info['COPY_DST_X'],self::$info['COPY_DST_Y'],self::$info['COPY_SRC_X'],self::$info['COPY_SRC_Y'],imagesx($nImg),imagesy($nImg));
        $destination_folder = self::$workCertDir;
        $pinfo=pathinfo($certPath);
        $ftype=$pinfo['extension'];  
        $destination = $destination_folder.time().".".$ftype;  
        //echo($destination);
        imagepng($certTemp, $destination);
        //move_uploaded_file($certTemp, $destination);
        //echo $destination;
        return $destination;
    }

    public static function makeCertification($img,$userInfo){
        //$file = new File();
        //$file->log($userInfo['imgName'].' 创建了最后模版 '.$img);

        $destination = self::createHead2($img);
        return $destination;

        //$iinfo=getimagesize($destination,$iinfo); 
        //print_r($iinfo);
        //return self::switchImg($iinfo,$destination,$userInfo);
    }
}