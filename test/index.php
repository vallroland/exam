<?php 

date_default_timezone_set('Asia/Manila');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$dir = __DIR__;
chdir($dir);
chdir("../");
require_once 'autoload.php';
chdir($dir);
require_once 'MyTest.php';

@ini_set('output_buffering','Off');
@ini_set('zlib.output_compression',0);
@ini_set('implicit_flush',1);
@ob_end_clean();
set_time_limit(0);
ob_start();


$pdo = new Database;
$con = $pdo->getConnection();

$mytest = new MyTest($con);
$mytest->RunTest(); 


 
?>