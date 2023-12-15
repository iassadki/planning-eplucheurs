<?php
try {
    // Connexion à MongoDB
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    // filtre
    $filter = [];
    $option = [];
    $read = new MongoDB\Driver\Query($filter, $option);
    $all_users = $manager->executeQuery('Planning.users', $read);
    echo nl2br("All users => n");
    foreach ($all_users as $user) {
        echo nl2br(' <br> ' . $user->prenom);
    }
} catch (MongoDB\Driver\ConnectionException $e) {
    echo $e->getMessage();
}