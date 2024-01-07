<?php session_start(); ?>
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

    <form action="" method="post">
    <input type="email" name="email"placeholder="Mail" required/><br>
        <input type="password" name="password"placeholder="Password" required/><br>
        <p>
            <input type="submit" class="submit-btn" value="Connect">
        </p>
    </form>

    <?php
    //$manager = new MongoDB\Driver\Manager("mongodb+srv://test:test@cluster0.63c2egn.mongodb.net/?retryWrites=true&w=majority"); // Connect to MongoDB
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
    
        // Create a filter for the query
        $filter = ['email' => $email];
        $query = new MongoDB\Driver\Query($filter);
    
        // Execute the query
        $cursor = $manager->executeQuery('Planning.users', $query);
    
        // Get the user
        $user = current($cursor->toArray());
        // If a user was found and the password is correct
        if ($user && password_verify($password, $user->mdp)) {
            ?>
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
    require('./Model/Date.php');
    $date = new Date();
    echo "test";
    $year = date($year);
    $weeks = $date->getAll($year);
    print_r($weeks);
    ?>

    <h2>Statistiques par ordre croissant</h2>

    <!-- Afficher les prenoms depuis la base de données MongoDB -->
    <?php
    try {
        // Connexion à MongoDB
        //$manager = new MongoDB\Driver\Manager("mongodb+srv://test:test@cluster0.63c2egn.mongodb.net/?retryWrites=true&w=majority");
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
            <?php
            
        } 
            else {
                // Debug: print the entered password and the hashed password
                echo "Entered password: $password\n";
                echo "Hashed password: " . $user->mdp . "\n";
                echo "invalid email or password";
            }
        
    }
    ?>



    

</body>

</html>