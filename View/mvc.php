<?php
require_once '../Model/Connection.php';
require_once '../Model/User.php';

$db = new Connection();
$manager = $db->getManager();

$users = new User();
echo "test";
?>
