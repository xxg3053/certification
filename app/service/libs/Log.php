<?php
/**
* 
*/
require_once('Common.php');
class ErrorLog extends Common
{
	
	function __construct()
	{
		# code...
	}

	public function index(){
		$this->check();
		$errorLog = isset($_POST['error_log'])?$_POST['error_log']:'';
		if(!$errorLog){
			return Response::show(401,'日志为空');
		}

		$sql = "insert into 
					error_log( 
						app_id,
						did,
						version_id,
						version_mini,
						create_time,
						error_log)
					values(
						".$this->params['app_id'].",
						'".$this->params['did']."',
						".$this->params['version_id'].",
						".$this->params['version_mini'].",
						".time().",
						'".$errorLog."'
					)";
		//echo $sql;
		$connect = Db::getInstance()->connect();
		if(mysql_query($sql,$connect)){
			return Response::show(200,'日志插入成功');
		}else{
			return Response::show(400,'日志插入失败');
		}

	}
}

$error = new ErrorLog();
$error->index();