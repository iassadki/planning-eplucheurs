<?php

use Exception;
use MongoDB\Client;
// Replace the placeholder with your Atlas connection string
$uri = 'mongodb://localhost:27017';
// Create a new client and connect to the server
$client = new MongoDB\Client($uri);
try {
    // Send a ping to confirm a successful connection
    $client->selectDatabase('Planning')->command(['ping' => 1]);
    echo "Pinged your deployment. You successfully connected to MongoDB!\n";
} catch (Exception $e) {
    print($e->getMessage());
}
?>