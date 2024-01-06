<?php session_start(); ?>
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
    <form action="" method="get">
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
    switch ($_GET['year']) {
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

    <h2>Statistiques par ordre croissant</h2>

    <!-- Afficher les prenoms depuis la base de données MongoDB -->
    <?php
    try {
        // Connexion à MongoDB
        $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

        // filtre
        $filter = [];
        $option = [];
        $read = new MongoDB\Driver\Query($filter, $option);
        $all_users = $manager->executeQuery('Planning.users', $read);
        ?>
        <ul>
            <?php foreach ($all_users as $user): ?>
                <li>
                    <?php echo nl2br($user->prenom); ?>
                    :<?php
                    $filter = ['_id' => new MongoDB\BSON\ObjectId($user->_id)];
                    $option = [];
                    $read = new MongoDB\Driver\Query($filter, $option);
                    $user = $manager->executeQuery('Planning.users', $read)->toArray()[0];
                    $count = 0;
                    foreach ($user->dates as $date) {
                        if (strpos($date, $year) !== false) {
                            $count++;
                        }
                    }
                    echo $count;
                    ?>
                    <?php
                    $selectedYear = $year;
                    // echo $selectedYear . " ";

                    $startDate = new MongoDB\BSON\UTCDateTime(strtotime("$selectedYear-01-01 00:00:00") * 1000);
                    $endDate = new MongoDB\BSON\UTCDateTime(strtotime(($selectedYear + 1) . "-01-01 00:00:00") * 1000);
                    $query = new MongoDB\Driver\Query(['dates' => ['$gte' => $startDate, '$lt' => $endDate]]);
                    $rows = $manager->executeQuery('Planning.users', $query);
                    $filter = [
                        'dates' => [
                            '$elemMatch' => [
                                '$gte' => new MongoDB\BSON\UTCDateTime(strtotime("$selectedYear-01-01 00:00:00") * 1000),
                                '$lt' => new MongoDB\BSON\UTCDateTime(strtotime(($selectedYear + 1) . "-01-01 00:00:00") * 1000),         
                            ]
                        ]
                    ];
                    $option = [];
                    $read = new MongoDB\Driver\Query($filter, $option);
                    $all_users = $manager->executeQuery('Planning.users', $read);


                    $count = count($user->dates);
                    echo $count . "\n";

                    ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php
    } catch (MongoDB\Driver\ConnectionException $e) {
        echo $e->getMessage();
    }
    ?>


</body>

</html>