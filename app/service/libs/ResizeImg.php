<?php 
/**
 * 等比例压缩图片,支持图片格式jpg,jpeg,png
 * @param string $srcImgPath	需要压缩的图片地址
 * @param string $dst_dir	上传的文件夹
 * @param string $dst_name	上传后的名称，不包括扩展名
 * @param int $maxWidth	如果需要等比例压缩图片，指定压缩后的最大宽度，默认为200
 * @param int $maxHeight	如果需要等比例压缩图片，指定压缩后的最大高度，默认为200
 * @return boolean	成功返回true，否则返回false
 */
function resizeImg($srcImgPath, $dst_dir, $dst_name, $maxWidth, $maxHeight) {
	if (!file_exists($srcImgPath)) {
		return false;
	}
	$file = pathinfo($srcImgPath);
	$ext = $file['extension'];
	if (empty($ext)){
		return false;
	}
	$ext = strtolower($ext);
	if (empty($ext) || !in_array($ext, array('jpg', 'jpeg', 'png'))){
		return false;
	}
	list($srcWidth, $srcHeight) = getimagesize($srcImgPath);
	//设置描绘的x、y坐标，高度、宽度
	$dst_x = $dst_y = $src_x = $src_y = 0;
	$ratio = min ( $maxHeight / $srcHeight, $maxWidth / $srcWidth );
	$dst_h = ceil ( $srcHeight * $ratio );
	$dst_w = ceil ( $srcWidth * $ratio );
	$dst_x = ($maxWidth - $dst_w)/2;
	$dst_y = ($maxHeight - $dst_h)/2;
	$dst_im = imagecreatetruecolor($maxWidth, $maxHeight);
	// 载入原图
	$createFun = 'ImageCreateFrom' . ($ext == 'jpg' ? 'jpeg' : $ext);
	$srcImg = $createFun($srcImgPath);
	
	//使用红色作为背景
	//$red = imagecolorallocate($im, 255, 0, 0);
	//imagefill($im, 0, 0, $red);
	// 复制图片
	imagecopyresampled ( $dst_im, $srcImg, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $srcWidth, $srcHeight );
	// 生成图片
	$imageFun = 'image' . ($ext == 'jpg' ? 'jpeg' : $ext);
	$file_name = $dst_dir.$dst_name.".".$ext;
	//压缩比例为70%
	if($imageFun == 'imagejpeg'){
		$result = imagejpeg($dst_im, $file_name, 70);
	}else{
		$result = imagepng($dst_im, $file_name, 7);
	}
	imagedestroy($dst_im);
	if (!$result){
		if (file_exists($file_name)) {
			unlink($file_name);
		}
	}
	return $result;
}