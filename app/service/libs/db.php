<?php
/**
* 
*/
class Db
{
	static private $_instance; //单例 保存实例
	static private $_connectSource;
	private $_dbConfig = array(
			'host'=>'127.0.0.1',
			'user'=>'root',
			'password'=>'xxg111063053',
			'database'=>'app_interfaces',
		);
	private function __construct()
	{
		//构造函数为private 单例
	}

	static public function getInstance(){
		if(!(self::$_instance instanceof self)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function connect(){
		if(!self::$_connectSource){
			self::$_connectSource = @mysql_connect(
				$this->_dbConfig['host'],
				$this->_dbConfig['user'],
				$this->_dbConfig['password']);
			if(!self::$_connectSource){
				//die("mysql connect error ".mysql_error());
				throw new Exception("mysql connect error ".mysql_error());
			}

			mysql_select_db($this->_dbConfig['database'],self::$_connectSource);
			mysql_query('set names UTF8',self::$_connectSource);
		}
		return self::$_connectSource;
	}
}
/**
$con = Db::getInstance()->connect();

$sql = "select * from htmldocument";
$result = mysql_query($sql,$con);
$row = mysql_num_rows($result);
var_dump($row);
**/