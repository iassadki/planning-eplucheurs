<?php

class UserController {
    private $userManager;

    public function __construct($db1) {
        require_once ('./Model/UserManager.php');
        $this->userManager = new UserManager($db1);
    }

    public function login() {
        $page = 'login';
        require('./View/default.php');
    }

    public function doLogin() {
        // Cette action teste l'existence d'un utilisateur de email
        $info = "";
        $error = "";
    
        if(isset($_POST['email']) && isset($_POST['password'])){
            $user = $_POST['email'];
            $pwd = $_POST['password'];
            $result = $this->userManager->login($user, $pwd);
            
            if($result){
                $info = "Connexion réussie";
                $page = 'planning';
               
            }
            else{
                $info = "Connexion echouée";
                $page = 'login';
            }
            
        }
        require('./View/default.php');    
    }

    public function logout() {
        // Cette action permet de se déconnecter
        $page = 'login';
        require('./View/default.php');
    }

  

}