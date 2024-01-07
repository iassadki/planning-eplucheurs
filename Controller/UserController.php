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

    public function choseYear() {
        // Cette action permet de choisir l'année du planning à afficher
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
        $page = 'planning';
        require('./View/default.php');
    }

}