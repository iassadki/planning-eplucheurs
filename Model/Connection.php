<?php
try {
    // Connexion à MongoDB Atlas
    $manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
} catch (Exception $e) {
    echo $e->getMessage(), "\n";
}
?>