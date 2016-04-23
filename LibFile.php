<?php

/**
 * 文件操作相关的模型
 * @author a-yi
 */
class LibFile{

	/**
	 * 上传文件
	 * @param string $name 上传文件的控件的name <input type="file" name="$name" />
	 * @param string $uploadPath 文件上传的目录,在这个目录下面再用date('Ym')创建一层目录
	 * @param string $uploadUrl 文件上传目录的URL
	 * @param int $size 上传限制,单位为kb
	 * @param array $ext 上传格式限制,如array('.jpg', '.gif', '.png')
	 * @param string $fileName 指定文件名
	 * @return array 返回array('state' => bool, 'msg' => string, 'url' => string)
	 *			 state 上传状态,成功为true,失败为false
	 *			 msg   上传失败的提示信息: SIZE_OVER(上传的尺寸过大)/ EXT_ERROR(格式不允许)/UPLOAD_ERROR上传失败
	 *			 url	 上传成功可访问到该图片的url
	 */
	public static function upload($name, $uploadPath, $uploadUrl, $size = 1000, $ext = array(), $fileName = ''){
		$res = array();
		$_FILES[$name]['tmp_name'] = str_replace("\\\\", "\\", $_FILES[$name]['tmp_name']); //框架自动加反斜杠了
		if(!is_uploaded_file($_FILES[$name]['tmp_name']) || !$_FILES[$name]['name']){
			$res['state'] = false;
			$res['url'] = "";
			$res['msg'] = "UPLOAD_ERROR";
			return $res;
		}

		$uploadInfo = self::getUploadInfo($_FILES[$name]['name'], $uploadPath, $uploadUrl, $fileName);

		//格式验证
		$current_type = $uploadInfo['ext'];

		if(!in_array($current_type, $ext)){
			$res['msg'] = "EXT_ERR";
			$res['state'] = false;
			return $res;
		}
		//大小验证
		
		$file_size = 1024 * $size;
		if($_FILES[$name]['size'] > $file_size){
			$res['msg'] = "SIZE_OVER";
			$res['state'] = false;
			return $res;
		}
		
		//保存图片
		$res['state'] = true;
		$res['url'] = $uploadInfo['uploadUrl'];
		$res['file'] = $uploadInfo['uploadpath'];

		$result = move_uploaded_file($_FILES[$name]['tmp_name'], $uploadInfo['uploadpath']);
		if(!$result){
			$res['state'] = false;
			$res['url'] = "";
			$res['file'] = "";
			$res['msg'] = "UPLOAD_ERROR";
		}
		$res['ext'] = $current_type;
		$res['msg'] = "UPLOAD_SUCCESS";

		if(Debug::check()){
			list($file, $line) = Debug::getPosition();
			Debug::addMsg('info', $uploadInfo['uploadpath'] . ':' . $res['msg'], $file, $line);
		}
		return $res;
	}
	/*
	 * 直接上传到tools
	 */
	public static function uploadToTools($name, $size = 1000, $exts = array(), $fileName = ''){

        if(is_string($name)){
            $file = $_FILES[$name];
        }else{
            $file = $name;
        }

		$file['tmp_name'] = str_replace("\\\\", "\\", $file['tmp_name']); //框架自动加反斜杠了
		if(!is_uploaded_file($file['tmp_name']) || !$file['name']){
			return array('state' => false, 'msg' => 'UPLOAD_ERROR');
		}

		//格式验证
		$ext = self::getExt($file['name']);

		if(!empty($exts) && !in_array($ext, $exts)){
			return array('state' => false, 'msg' => 'EXT_ERROR');
		}
		
		if($size !== 0){
			//大小验证
			$file_size = 1024 * $size;
			if($file['size'] > $file_size){
				return array('state' => false, 'url' => '', 'msg' => 'SIZE_OVER');
			}
		}
		//保存图片
		$time = time();
		$postData = array(
			'fileName' => $fileName,
			'fileExt'  => $ext,
			'time' => $time,
			'flag' => md5(TOOLS_UPLOAD_SERVER_KEY . $time),
		);
		$files = array(
			'fileData' => array('path' => $file['tmp_name'], 'type' => $file['type'], 'name' => $file['name']),
		);
		$ip = defined('TOOLS_UPLOAD_SERVER_IP') ? TOOLS_UPLOAD_SERVER_IP : '';
		$re = LibUtil::request(TOOLS_UPLOAD_SERVER_API, $postData, 0, $ip, array(), '', false, $files);
		Debug::log($re);
		if($re['code'] == 200){
			$result = json_decode($re['result'], true);
			if(!$result){
				return array('state' => false, 'msg' => 'UPLOAD_ERROR');
			}else{
				if($result['state']){
					return array('state' => true, 'url' => $result['data'], 'msg' => 'UPLOAD_SUCCESS');
				}else{
					return array('state' => false, 'msg' => 'UPLOAD_ERROR');
				}

			}
		}else{
			return array('state' => false, 'msg' => 'UPLOAD_ERROR');
		}
	}

	/**
	 * 获得文件上传路径及URL信息
	 * @param string $oldFileName $_FILES["$name"]['name']
	 * @param string $uploadPath 文件上传的目录
	 * @param string $uploadUrl 文件上传目录的URL
	 * @param string $fileName 指定文件名
	 *
	 * @return array 返回  array('url' => string, 'uploadFile' => string);
	 * 其中: url		可访问文件的URL
	 * uploadFile 新文件的完整路径
	 * 文件名命名规则 date('dHis') . mt_rand(1000, 9999).'_s' + 文件格式
	 * $fileName 固定的文件名
	 */
	private static function getUploadInfo($oldFileName, $uploadPath, $uploadUrl, $fileName = ''){
		$sp = substr($uploadPath, -1, 1) == '/' || substr($uploadPath, -1, 1) == '\\' ? '' : '/';
		$info = array();
		if(!$fileName){
			$_dm = date("ym");
			$uploadPath = $uploadPath . $sp . $_dm . '/';
			$uploadUrl = $uploadUrl . $sp . $_dm . '/';
			$_fileName = date('dHis') . mt_rand(1000, 9999) . self::getExt($oldFileName);
		}
		else{
			$_fileName = $fileName;
		}
		$info['uploadDir'] = $uploadPath;
		$sp = substr($uploadPath, -1, 1) == '/' || substr($uploadPath, -1, 1) == '\\' ? '' : '/';
		$uploadPath = $uploadPath . $sp . $_fileName;
		$uploadUrl = $uploadUrl . $sp . $_fileName;


		$info['uploadpath'] = $uploadPath;
		$info['uploadUrl'] = $uploadUrl;
		$info['ext'] = self::getExt($oldFileName);

		//检查是否有该文件夹，如果没有就创建
		if(!is_dir($info['uploadDir'])){
			mkdir($info['uploadDir'], 0775, true);
		}

		return $info;
	}

	public static function getExt($fileName){
		return strtolower(strrchr($fileName, '.'));
	}


    /**
     *在线管理图片
     * @param string $path	  图片文件的绝对路径
     * @param string $files	   引用数组赋初值为空数组
     *
     * @return string $files	   文件数组
     */
    public static function fileManage($originPath, $url, $allowFiles, &$files = array()){
        $_dm = date("ym");
        $path = $originPath . $_dm . '/';
        if(!is_dir($path)) return array();
        $_handle = opendir($path);
        while(false !== ($file = readdir($_handle))){
            if($file != '.' && $file != '..'){
                $path2 = $path . $file;
                if(is_dir($path2)){
                    self::fileManage($path2, $allowFiles, $files);
                }
                else{
                    if(preg_match("/\.(".$allowFiles.")$/i", $file)){
                        $files[] = array(
                            'url'=> $url.substr($path2, strlen($originPath)),
                            'mtime'=> filemtime($path2)
                        );
                    }
                }
            }
        }
        return $files;
    }

	/**
	 * 遍历某个文件夹所有文件，不递归
	 * @param string $path	  文件夹的路径
	 * @param boolean $allPath 是否返回全路径
	 *
	 * @return array 文件数组
	 */
	public static function fileList($path, $allPath = true){
		$path = substr($path, -1, 1) == '/' ? $path : $path . '/';
		if(!is_dir($path)) return array();
		$_handle = opendir($path);
		$files = array();
		while(false !== ($file = readdir($_handle))){
			if($file != '.' && $file != '..' && is_file($path . $file)){
				if($allPath){
					$files[] = $path . $file;
				}else{
					$files[] = $file;
				}

			}
		}
		return $files;
	}

	//遍历目录
	public static function fileListPro($basePath, $dirName='', $orderBy = 'name', $orderType = 'asc'){//order by time size name  | desc asc
		$realPath = realpath($basePath . $dirName);
		$tempDir  = array();
		$tempFile = array();
		if (is_readable($realPath)){
			$fp = opendir($realPath);

			$tempOrderBy = array();
			while (($file = readdir($fp)) !== FALSE){
				if ($file=='.' || $file=='..') continue;
				$realFile = $realPath.'/'.$file;

				//验证文件合法性
				if (is_file($realFile)){
					$mTime = filemtime($realFile);
					$size  = filesize($realFile);
					$type = self::fileExt($file);

					if($size < 1024){
						$size .= " B";
					}elseif($size > 1024){
						$size = intval($size / 1024) . " KB";
					}

					if($type == 'js'){
						$icon = "js";
					}elseif($type == "css"){
						$icon = "css";
					}elseif($type == "jpg" || $type == "gif" || $type == "png"){
						$icon = "image";
					}elseif($type == "zip" || $type == "rar"){
						$icon = "rar";
					}elseif($type == "swf" || $type == "flw"){
						$icon = "swf";
					}elseif($type == "php" || $type == "inc"){
						$icon = "file_script";
					}elseif($type == "tpl" || $type == "html" || $type == "htm"){
						$icon = "html";
					}elseif($type == "conf" ){
						$icon = "nconf";
					}else{
						$icon = "other";
					}
					$tempFile[] = array(
						'name'  => $file,
						'icon'  => $icon,
						'mtime' => $mTime,
						'type'  => filetype($realFile),
						'size'  => $size
					);
					$tempOrderBy['name'][] = $file;
					$tempOrderBy['time'][] = $mTime;
					$tempOrderBy['size'][] = $size;
				}


				if (is_dir($realFile)){
					$tempDir[] = array(
						'name' => $file,
						'icon' => "file_dir",
						'path' => ($dirName == '/' ? '' : $dirName) . '/' . $file
					);
				}
			}
			closedir($fp);
			if(!empty($tempOrderBy[$orderBy])){
				array_multisort($tempOrderBy[$orderBy], $orderType == 'desc' ? SORT_DESC : SORT_ASC, $orderBy == 'name' ? SORT_STRING : SORT_NUMERIC, $tempFile);
			}
		}

		//文件排序
		$parentDir = dirname($dirName);
		if($parentDir == "\\"){
			$parentDir = "/";
		}
		$parent = array();
		if($dirName != '/'){
			$parent = array(
				array(
					'name' => '上层目录',
					'icon'  => "file_dir",
					'path' => $parentDir
				)
			);
		}

		return array_merge($parent, $tempDir, $tempFile);
	}
	//递归遍历目录
	public static function getFileLists($dir, $path = array(), $level = 0){
		if(!is_dir($dir)) return array();
		$_handle = opendir($dir);
		$files = array();
		$dirs  = array();
		$nextPath = array();
		$nextLevel = 0;

		while(false !== ($file = readdir($_handle))){
			$nextPath = $path;

			if($file == '.' || $file == '..'){
				continue;
			}

			if(is_file($dir . '/' . $file)){
				$files[] = array('type' => 'file', 'name' => $file, 'path' => ($level > 0 ? '/' : '') . implode('/', $path)  . '/' . $file, 'level' => $level);
			}else{
				$nextPath[] = $file;
				$nextLevel = $level + 1;

				$dirs[] = array('type' => 'dir', 'name' => $file, 'path' => '/' . implode('/', $nextPath), 'level' => $level);


				$dirs = array_merge($dirs, self::getFileLists($dir . '/' . $file, $nextPath, $nextLevel));
			}
		}
		$files = array_merge($dirs, $files);
		closedir($_handle);
		return $files;
	}

	//下载远程图片
	public static function getRemoteImage( $imgUrl, $uploadPath, $uploadUrl, $size = 1000, $ext = array('.jpg', '.jpeg', '.gif', '.png')){
		if(strpos($imgUrl, $uploadUrl) !== FALSE){//如果是本地上传的图片，直接返回路径
			return $uploadPath . str_replace($uploadUrl, '', $imgUrl);
		}
		//远程图片

		//忽略抓取时间限制
		set_time_limit( 0 );
		//远程抓取图片配置
		$uploadInfo = self::getUploadInfo($imgUrl, $uploadPath, $uploadUrl);

		//格式验证
		$fileType = $uploadInfo['ext'];
		if ( !in_array( $fileType , $ext ) ) {
			return false;
		}
		//死链检测
		if ( !self::urlTest( $imgUrl ) ) {
			return false;
		};

		//打开输出缓冲区并获取远程图片
		ob_start();
		readfile( $imgUrl );
		$img = ob_get_clean();

		//大小验证
		$uriSize = strlen( $img ); //得到图片大小
		$allowSize = 1024 * $size;
		if ( $uriSize > $allowSize ) {
			return false;
		}
		//创建保存位置
		$savePath = $uploadInfo['uploadpath'];

		try {
			$fp2 = @fopen( $savePath , "a" );
			fwrite( $fp2 , $img );
			fclose( $fp2 );
			return $savePath;
		} catch ( Exception $e ) {
			return false;
		}

	}

	/**
	 * 删除文件夹
	 *
	 * @param string $dir 要删除的文件夹
	 * @return boolean
	 */
	public static function delDir($dir = '') {
		if (!is_dir($dir)) {
			return false;
		}
		$handle = opendir($dir);
		$re1 = true;
		$re2 = true;
		while(false !== ($file = readdir($handle))) {
			if ($file == '.' || $file == '..') {
				continue;
			}
			if (!is_dir($dir . '/' . $file)) {
				$re1 = unlink($dir . '/' . $file);
			} else {
				$re2 = self::delDir($dir . '/' . $file);
			}
		}
		closedir($handle);
		$re3 = rmdir($dir);

		return $re1 && $re2 && $re3;
	}

	public static function copyDir($src, $dst){
		$dir = opendir($src);
		$re = mkdir($dst, 0777, true);
		if(!$re){
			return false;
		}
		$allOk  = true;
		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				if ( is_dir($src . '/' . $file) ) {
					$re = self::copyDir($src . '/' . $file,$dst . '/' . $file);
					if(!$re){
						$allOk = false;
					}
				}else {
					$re = copy($src . '/' . $file, $dst . '/' . $file);
					if(!$re){
						$allOk = false;
					}
				}
			}
		}
		closedir($dir);
		return $allOk;
	}


	/**
	 * 死链检测
	 * @param $uri
	 * @return bool
	 */
	private static function urlTest( $uri ){
		$headerArr = get_headers( $uri );
		return stristr( $headerArr[ 0 ] , "200" ) && stristr( $headerArr[ 0 ] , "OK" );
	}

	//获取文件后缀名
	public static function fileExt($filename){
		$filename = explode('.',$filename);
		return end($filename);
	}
}