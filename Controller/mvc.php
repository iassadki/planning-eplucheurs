<?php 
require_once '../Model/UserManager.php';
require_once '../Model/Connection.php' ;



$db = new Connection(); 
$manager = $db->getManager();

$test  = "657335585b1e4f67e3f557c1";
$users = new UserManager($manager);

echo $users->getId($test);


?>