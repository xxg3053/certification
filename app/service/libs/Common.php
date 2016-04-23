<?php
/**
 * 处理接口公共方法
 */
require_once('Response.php');
require_once('db.php');
class Common
{
	
	function __construct()
	{
		
	}
	public $params;
	public $app;

	/**
	 * [check description]
	 * @Author   KENFO
	 * @Email    xxg3053@qq.com
	 * @DateTime 2015-06-28T22:48:01+0800
	 * @Describe
	 * @return   [type]                   [description]
	 */
	public function check(){
		$this->params['app_id'] = $appId = isset($_POST['app_id'])?$_POST['app_id']:'';//app id
		$this->params['version_id'] = $versionId = isset($_POST['version_id'])?$_POST['version_id']:'';//版本号
		$this->params['version_mini'] = $versionMini = isset($_POST['version_mini'])?$_POST['version_mini']:'';//小版本号
		$this->params['did'] = $did = isset($_POST['did'])?$_POST['did']:'';//设备id
		$this->params['encrypt_did'] = $encryptDid = isset($_POST['encrypt_did'])?$_POST['encrypt_did']:'';//加密的设备id

		if(!is_numeric($appId) || !is_numeric($versionId)){
			return Response::show(401,'参数不合法');
		}

		//判断APP是否需要加密
		$this->app = $this->getApp($appId);
		if(!$this->app){
			return Response::show(402,'app_id不存在！');
		}
		if($this->app['is_encryption'] && $encryptDid != md5($did.$this->app['key'])){
			return Response::show(403,'没有该权限！'.$encryptDid."=".md5($did.$this->app['key']));
		}
	}
	/**
	 * [getApp description]
	 * @Author   KENFO
	 * @Email    xxg3053@qq.com
	 * @DateTime 2015-06-28T22:48:10+0800
	 * @Describe 根据设备id获取该设备
	 * @param    [type]                   $id [description]
	 * @return   [type]                       [description]
	 */
	public function getApp($id){
		$sql = "select * from app where id=".$id." and status=1 limit 1";
		//echo $sql;exit;
		$conn = Db::getInstance()->connect();
		$result = mysql_query($sql,$conn);
		//var_dump(mysql_fetch_assoc($result));
		return mysql_fetch_assoc($result);
	}

	public function getVersionUpgrade($appId){
		$sql = "select * from version_upgrade where app_id = ".$appId." and status=1 limit 1";
		//echo $sql;exit;
		$conn = Db::getInstance()->connect();
		$result = mysql_query($sql,$conn);
		return mysql_fetch_assoc($result);
	}

}