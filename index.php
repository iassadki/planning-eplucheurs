<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning Eplucheurs</title>
    <link href="View/CSS/general.css" rel="stylesheet" type="text/css">
    <link href="View/CSS/header-footer.css" rel="stylesheet" type="text/css">
    <link href="View/CSS/mainSection.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
?>
   
    <?php
    
    require_once './Model/Connection.php';
        $pdoBuilder = new Connection();
        $db = $pdoBuilder->getManager();
        if (( isset($_GET['ctrl']) && !empty($_GET['ctrl']) ) && ( isset($_GET['action']) && !empty($_GET['action']) )) {
            $ctrl = $_GET['ctrl'];
            $action = $_GET['action'];
        }
        else {
            $ctrl = 'User';
            $action = 'login';
        }
        require_once('./Controller/'.ucfirst($ctrl).'Controller.php');
        $ctrl = $ctrl.'Controller';
        $controller = new $ctrl($db);
        $controller->$action();

    ?>
    
        
    
</body>
</html>



