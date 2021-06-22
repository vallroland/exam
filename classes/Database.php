<?php 

Class Database {
	
	
	#private $host = "188.166.245.25";
	private $host = "imroland.com";
	private $db_name = "exam";
#	private $username = "adminapi";
	private $username = "postgres";
	private $password = "genesis";
	public $conn;
	public $errorMsg;
	
	// get the database connection
	public function getConnection(){
		
		$this->conn = null;
		
		try{

			$dsn = "pgsql:host=$this->host;port=5432;dbname=$this->db_name;";
			$this->conn = new PDO($dsn, $this->username, $this->password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
			
		}catch(PDOException $exception){ 			
			$this->errorMsg = $exception->getMessage();
			
			return false;
		}
		
		return $this->conn;
	}
	
	
}


?>