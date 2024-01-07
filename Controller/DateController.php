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
        $_SESSION['year'] = $year;
        require('./View/default.php');
    }

    public function sendDates() {
        $year = $_SESSION['year'];
        $date = $this->dateManager->getAll($year);
        $weeks = $date['weeks'];
        // Cette action permet d'envoyer les dates sélectionnées par l'utilisateur
        $page = 'planning';
        if (isset($_POST['submit'])) {
            for ($i = 0; $i < $date['weeksCount']; $i++) {
                $selectedUserId = isset($_POST['week'][$i]) ? $_POST['week'][$i] : null;
                if ($selectedUserId !== null) {
                    $selectedYear = $year; 
                    // $client = new MongoDB\Driver\Manager("mongodb+srv://test:test@cluster0.63c2egn.mongodb.net/?retryWrites=true&w=majority");
                    $client = new MongoDB\Driver\Manager("mongodb://localhost:27017");
                    $startDate = new MongoDB\BSON\UTCDateTime(strtotime("$selectedYear-01-01 00:00:00") * 1000);
                    $endDate = new MongoDB\BSON\UTCDateTime(strtotime(($selectedYear + 1) . "-01-01 00:00:00") * 1000);

                    $query = new MongoDB\Driver\Query(['dates' => ['$gte' => $startDate, '$lt' => $endDate]]);
                    $cursor = $client->executeQuery('Planning.users', $query);
                    $selectedYear = $year;

                    // Trouver l'utilisateur qui a déjà la date
                    $query = new MongoDB\Driver\Query(['dates' => $weeks[$i]]);
                    $cursor = $client->executeQuery('Planning.users', $query);
                    $existingUsers = iterator_to_array($cursor);

                    if (count($existingUsers) > 0) {
                        // Supprimer la date de l'ancien utilisateur
                        try {
                            $bulk = new MongoDB\Driver\BulkWrite;
                            $bulk->update(
                                ['_id' => $existingUsers[0]->_id],
                                ['$pull' => ['dates' => $weeks[$i]]]
                            );
                            $result = $client->executeBulkWrite('Planning.users', $bulk);
                        } catch (MongoDB\Driver\ConnectionException $e) {
                            echo $e->getMessage();
                        }
                    }

                    // Ajouter la date au nouvel utilisateur
                    try {
                        $bulk = new MongoDB\Driver\BulkWrite;
                        $bulk->update(
                            ['_id' => new MongoDB\BSON\ObjectId($selectedUserId)],
                            ['$addToSet' => ['dates' => $weeks[$i]]]
                        );
                        $result = $client->executeBulkWrite('Planning.users', $bulk);
                    } catch (MongoDB\Driver\ConnectionException $e) {
                        echo $e->getMessage();
                    }
                }
            }
        
    }
        require('./View/default.php');
    }

}