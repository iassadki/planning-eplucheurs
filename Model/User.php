<?php 
// include_once('Model/Connection.php');
require_once 'Connection.php';


class User{
        private int $_id;
        private string $nom;
        private string $prenom;
        private string $email;
        private string $mdp;
        private array $dates;
        private $connection;


        public function getId($user){
            $this->connection = new Connection();
            $manager = $this->connection->getManager();
            $filter = ['_id' => $this->_id];
            $options = [];
            $query = new MongoDB\Driver\Query($filter, $options);
            $result = $manager->executeQuery('Planning.users', $query);
            return $result->toArray()[0]->_id;
        }

        public function getPrenom($user){
            $this->connection = new Connection();
            $manager = $this->connection->getManager();
            $filter = ['prenom' => $this->prenom];
            $options = [];
            $query = new MongoDB\Driver\Query($filter, $options);
            $result = $manager->executeQuery('Planning.users', $query);
            return $result->toArray()[0]->prenom;
        }

        public function getEmail($user){
            $this->connection = new Connection();
            $manager = $this->connection->getManager();
            $filter = ['email' => $this->email];
            $options = [];
            $query = new MongoDB\Driver\Query($filter, $options);
            $result = $manager->executeQuery('Planning.users', $query);
            return $result->toArray()[0]->email;
        }

        public function getMdp($user){
            $this->connection = new Connection();
            $manager = $this->connection->getManager();
            $filter = ['mdp' => $this->mdp];
            $options = [];
            $query = new MongoDB\Driver\Query($filter, $options);
            $result = $manager->executeQuery('Planning.users', $query);
            return $result->toArray()[0]->mdp;
        }

        public function getDates($user){
            $this->connection = new Connection();
            $manager = $this->connection->getManager();
            $filter = ['dates' => $this->dates];
            $options = [];
            $query = new MongoDB\Driver\Query($filter, $options);
            $result = $manager->executeQuery('Planning.users', $query);
            return $result->toArray()[0]->dates;
        }
    }
?>