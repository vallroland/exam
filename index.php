<?php 

header('Content-Type: application/json');
date_default_timezone_set('Asia/Manila');

$dir = __DIR__;

require_once 'autoload.php';
chdir($dir);

$request = $_SERVER['REQUEST_URI'];
$params = $_POST;

$pdo = new Database;
$con = $pdo->getConnection();



if($con){
	$api = new Api($con);
	$result = $api->execute($request,$params);
} else {
	$result = array("status"=>"Failed",$message=>$pdo->errorMsg);	
}
 
echo json_encode($result);
?>