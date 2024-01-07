<?php 

    class User{
        private int $_id;
        private string $nom;
        private string $prenom;
        private string $email;
        private string $mdp;
        private array $dates;


        public function getId(){
            return $this->_id;
        }
    }
?>