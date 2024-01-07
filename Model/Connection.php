<?php

class Connection
{
    public $manager;

    public function __construct()
    {
        try {
            // Connexion à MongoDB Atlas
            //$this->manager = new MongoDB\Driver\Manager('mongodb+srv://test:test@cluster0.63c2egn.mongodb.net/?retryWrites=true&w=majority');
            $this->manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');

        } catch (Exception $e) {
            echo $e->getMessage(), "\n";
        }
    }

    public function getManager()
    {
        return $this->manager;
    }
}

?>