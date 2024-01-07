<?php 

class UserManager{     
        private $connection;
        
        public function __construct($connection){
            $this->connection = $connection;
        }

        public function getId($user){
            $this->connection = new Connection();
            $manager = $this->connection->getManager();
            $filter = ['_id' => new MongoDB\BSON\ObjectId($user)];
            $options = [];
            $query = new MongoDB\Driver\Query($filter, $options);
            $result = $manager->executeQuery('Planning.users', $query);
            $resultArray = $result->toArray();
            if (!empty($resultArray)) {
                return (string) $resultArray[0]->_id;
            } else {
                echo "User not found";
                return null;
            }
        }

        public function getAllUser(){
            $this->connection = new Connection();
            $manager = $this->connection->getManager();
            $filter = [];
            $option = [];
            $read = new MongoDB\Driver\Query($filter, $option);
            $all_users = $manager->executeQuery('Planning.users', $read);
            return $all_users;
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

        public function login($user, $password){
            $this->connection = new Connection();
            $manager = $this->connection->getManager();
            $filter = ['email' => $user];
            $options = [];
            $query = new MongoDB\Driver\Query($filter, $options);
            $result = $manager->executeQuery('Planning.users', $query);
            $resultArray = $result->toArray();
            if (!empty($resultArray)) {
                $user = $resultArray[0];
                if (password_verify($password, $user->mdp)) {
                    return $user;
                } else {
                    echo "invalid email or password";
                    return null;
                }
            } else {
                echo "User not found";
                return null;
            }
        }
    }
?>