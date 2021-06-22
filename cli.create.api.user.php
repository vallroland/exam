<?php 
date_default_timezone_set('Asia/Manila');

$dir = __DIR__;

require_once 'autoload.php';
chdir($dir);

$pdo = new Database;
$con = $pdo->getConnection();

$now = date("Y-m-d H:i:s");
$username = "apimaster";
$password = "genesis";
$password_encrypted = md5($password);
try {
	
	$stmt = $con->prepare("INSERT INTO api_user (username, password, password_encrypted,date_created,date_update) VALUES(?,?,?,?,?)");
	
	try {
		
		$con->beginTransaction();
		$stmt->execute( array($username, $password,$password_encrypted,$now,$now) );
		
		$con->commit();
		$insert_id = $con->lastInsertId();
		
		echo "Api credentials created";
	} catch (PDOException $e) {
		echo $e->getMessage();
	}
} catch( PDOException $e ) {
	
	 echo $e->getMessage();
}

?>