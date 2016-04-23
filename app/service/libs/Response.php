<?php
require_once('File.php');

class Response {

	const JSON = "json";
	/**
	 * [show description]
	 * @Author   KENFO
	 * @Email    xxg3053@qq.com
	 * @DateTime 2015-06-28T19:30:22+0800
	 * @Describe
	 * @param    [type]                   $code    [description]
	 * @param    string                   $message [description]
	 * @param    array                    $data    [description]
	 * @param    [type]                   $type    ['json','xml','array']
	 * @return   [type]                            [description]
	 */
	public static function show($code,$message='',$data=array(),$type=self::JSON){
		if(!is_numeric($code)){
			return '';
		}

		$type = isset($_GET['format'])?$_GET['format']:$type;
		$result=array(
				'code'=>$code,
				'message'=>$message,
				'data'=>$data
			);
		if($type=='json'){
			self::json($code,$message,$data);
			exit;
		}elseif ($type == 'array') {
			var_dump($result);
		}elseif ($type == 'xml') {
			self::xmlEncode($code,$message,$data);
		}else{
			//DOTO
		}
	}
	/**
	 * [json description]
	 * @Author   KENFO
	 * @Email    xxg3053@qq.com
	 * @DateTime 2015-06-28T12:07:54+0800
	 * @Describe  按json方式输出通讯数据
	 * @param    [integer]                   $code    [状态码]
	 * @param    string                   $message [提示信息]
	 * @param    array                    $data    [数据]
	 * @return   [type]                            [description]
	 */
	public static function json($code,$message='',$data = array()){
		if(!is_numeric($code)){
			return '';
		}
		$result=array(
				'code'=>$code,
				'message'=>$message,
				'data'=>$data
			);
		$res = json_encode($result);
		$file = new File();
		$file->debug($res);
		echo $res;
		exit;
	}
	/**
	 * [xmlEncode description]
	 * @Author   KENFO
	 * @Email    xxg3053@qq.com
	 * @DateTime 2015-06-28T12:31:35+0800
	 * @Describe  输出xml
	 * @param    [type]                   $code    [description]
	 * @param    string                   $message [description]
	 * @param    array                    $data    [description]
	 * @return   [type]                            [description]
	 */
	public static function xmlEncode($code,$message='',$data = array()){
		if(!is_numeric($code)){
			return '';
		}
		$result=array(
				'code'=>$code,
				'message'=>$message,
				'data'=>$data
			);
		header("Content-Type:text/xml");
		$xml = "<?xml version='1.0' encoding='utf-8' ?>\n";
		$xml .= "<root>";
		$xml .= self::xmlToEncode($result);
		$xml .= "</root>";
		echo $xml;
		exit;
	}

	public static function xmlToEncode($data){
		$xml = $attr = '';
		foreach ($data as $key => $value) {
			if(is_numeric($key)){
				$attr = " id='{$key}'";
				$key = "item";
			}
			$xml .= "<{$key}{$attr}>";
			$xml .= is_array($value)?self::xmlToEncode($value):$value;
			$xml .= "</{$key}>\n";
		}
		//echo($xml);
		return $xml;
	}
}