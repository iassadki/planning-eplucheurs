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
    $weeks = $date->getAll($year);
    print_r($weeks);
    ?>

    <h2>Statistiques par ordre croissant</h2>

    <!-- Afficher les prenoms depuis la base de données MongoDB -->
    <?php
    try {
        // Connexion à MongoDB
        $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

        // Agrégation pour obtenir les résultats triés
        $command = new MongoDB\Driver\Command([
            'aggregate' => 'users',
            'pipeline' => [
                ['$unwind' => '$dates'],
                ['$match' => ['dates' => ['$regex' => ".*-$year"]]],
                [
                    '$group' => [
                        '_id' => [
                            '_id' => '$_id',
                            'nom' => '$nom',
                            'prenom' => '$prenom'
                        ],
                        'nbDates' => ['$sum' => 1]
                    ]
                ],
                [
                    '$project' => [
                        '_id' => '$_id._id',   
                        'nom' => '$_id.nom',
                        'prenom' => '$_id.prenom',
                        'nbDates' => '$nbDates'
                    ]
                ],
                ['$sort' => ['nbDates' => 1]]
            ],
            'cursor' => new stdClass,
        ]);

        $result = $manager->executeCommand('Planning', $command);
        $resultArray = $result->toArray();

        $sortedResults = [];
        $filter = [];
        $option = [];
        $read = new MongoDB\Driver\Query($filter, $option);
        $all_users = $manager->executeQuery('Planning.users', $read);

        foreach ($all_users as $user) {
            foreach ($resultArray as $userData) {
                if (!empty($resultArray)) {
                    $fullName = $userData->prenom;
                    $sortedResults[$fullName] = $userData->nbDates;    
                }  
            }
        }
        ?>
        
        <ul>
            <?php foreach ($sortedResults as $fullName => $nbDates): ?>
                <li><?php echo $fullName . " : " . $nbDates; ?></li>
            <?php endforeach; ?>
        </ul>
        <?php
    } catch (MongoDB\Driver\ConnectionException $e) {
        echo $e->getMessage();
    }
    ?>

</body>

</html>