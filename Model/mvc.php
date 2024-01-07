<?php 
require './User.php';
require_once '../Model/Connection.php' ;



$db = new Connection();
$manager = $db->getManager();

$users = new User();
echo "test";

?>