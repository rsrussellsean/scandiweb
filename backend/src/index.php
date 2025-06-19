<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
echo "Hello World";

include 'DbConnect.php';
$objDb = new DbConnect();
$conn = $objDb->connect();

print_r(file_get_contents('php://input'));
?>