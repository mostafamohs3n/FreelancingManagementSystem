<?php
/** Immutable**/
class Core{
	private $host, $user, $pw, $dbName;
	public static $conn = null;

	public function __construct($host, $user, $pw, $dbName){
		$this->host = $host;
		$this->user = $user;
		$this->pw = $pw;
		$this->dbName = $dbName;
		if(is_null(self::$conn)){
			self::$conn = new PDO("mysql:host=" . $this->host .";dbname=" . $this->dbName, $this->user, $this->pw);
		}
	}
	public static function getConnection(){
		return self::$conn;
	}

	public static function getLastInsertedRow(){
		return self::$conn->lastInsertId();
	}

}