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

        public function getStats(){
            try {
                // Connexion à MongoDB
                //$manager = new MongoDB\Driver\Manager("mongodb+srv://test:test@cluster0.63c2egn.mongodb.net/?retryWrites=true&w=majority");
                $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
                $year = $_SESSION["year"];
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
        }
    }
?>