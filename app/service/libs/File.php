<?php
/**
* 
*/
class File
{
	private $_dir;
	const EXT ='.txt';

	function __construct()
	{
		$this->_dir = ROOT.'/public/file/';
	}
    public function log($message){
    	$filename = $this->_dir.'log'.self::EXT;
    	$dir = dirname($filename);
		if(!is_dir($dir)){
			mkdir($dir,0777);
		}
        file_put_contents($filename, '[' . date('Y-m-d H:i:s') . ']' . ":\r\n{$message}\r\n", FILE_APPEND);
    }
    public function debug($message){
    	$filename = $this->_dir.'debug'.self::EXT;
    	$dir = dirname($filename);
		if(!is_dir($dir)){
			mkdir($dir,0777);
		}
        file_put_contents($filename, '[' . date('Y-m-d H:i:s') . ']' . ":\r\n{$message}\r\n", FILE_APPEND);
    }
	public function cacheData($key,$value='',$path=''){
		$filename = $this->_dir.$path.$key.self::EXT;

		if($value !== ''){//将value写入缓存
			if(is_null($value)){
				return @unlink($filename);
			}
			$dir = dirname($filename);
			if(!is_dir($dir)){
				mkdir($dir,0777);
			}

			return file_put_contents($filename, json_encode($value));
		}

		if(!is_file($filename)){
			return FALSE;
		}else{
			return json_decode(file_get_contents($filename),true);
		}
	}
}