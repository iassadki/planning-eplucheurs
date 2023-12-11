<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="View/CSS/general.css" rel="stylesheet" type="text/css">
    <link href="View/CSS/header-footer.css" rel="stylesheet" type="text/css">
    <link href="View/CSS/mainSection.css" rel="stylesheet" type="text/css">
</head>
<body>
        <form action="" method="post">
        <select name="year" id="year">
            <?php
                for ($i = 2014; $i <= 2020; $i++) {
                    $selected = ($i == $year) ? 'selected' : '';
                    echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
                }
                ?>
            </select>
            <input type="submit" value="Show">
        </form>
        <?php
        switch ($_POST['year']) {
            case '2014':
                $year = 2014;
                break;
            case '2015':
                $year = 2015;
                break;
            case '2016':
                $year = 2016;
                break;
            case '2017':
                $year = 2017;
                break;
            case '2018':
                $year = 2018;
                break;
            case '2019':
                $year = 2019;
                break;
            case '2020':
                $year = 2020;
                break;
            default:
                $year = 2014;
                break;
        }
        ?>
        
        <?php
        require('Model/Date.php');
        $date = new Date();
        $year = date($year);
        // $dates = $date->getAll($year);
        $weeks = $date->getAll($year);
        print_r($weeks);
        ?>
           
    
</body>
</html>
