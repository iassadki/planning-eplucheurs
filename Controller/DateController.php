<?php

class DateController {
    private $dateManager;

    public function __construct($db1) {
        require_once ('./Model/DateManager.php');
        $this->dateManager = new DateManager($db1);
    }

    public function choseYear() {
        // Cette action permet de choisir l'année du planning à afficher
        $page = 'planning';
        switch ($_POST['year']) {
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
        require('./View/default.php');
    }

}