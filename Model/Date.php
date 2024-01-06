<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

require('./Model/Connection.php');

class Date
{

    public $months = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12');
    public $days = array('1', '2', '3', '4', '5', '6', '7');
    public $monthWeeks = array('1', '2', '3', '4');

    function getAll($year)
    {

        $weeks = [];


        for ($i = 0; $i < 52; $i++) {
            $week_start = date('d-m-Y', strtotime($year . 'W' . str_pad($i + 1, 2, 0, STR_PAD_LEFT)));
            $week_end = date('d-m-Y', strtotime($week_start . ' +6 days'));
            $weeks[$i] = $week_end;
        }

        $columns = 4;
        $weeksCount = count($weeks);
        $rows = ceil($weeksCount / $columns);
        ?>
        <form action="" method="post">
            <table>
                <?php for ($row = 0; $row < $rows; $row++) { ?>
                    <tbody>
                        <tr>
                            <?php for ($col = 0; $col < $columns; $col++) { ?>
                                <?php $i = $row * $columns + $col; ?>
                                <?php if ($i < $weeksCount) { ?>
                                    <td>
                                        <label for="week_<?php echo $i; ?>">
                                            <?php echo $weeks[$i]; ?>
                                        </label>
                                        <select name="week[<?php echo $i ?>]">
                                            <?php try {
                                                $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
                                                // filtre
                                                $filter = [];
                                                $option = [];
                                                $read = new MongoDB\Driver\Query($filter, $option);
                                                $all_users = $manager->executeQuery('Planning.users', $read);
                                                ?>
                                                <?php
                                                foreach ($all_users as $user) { ?>
                                                    <?php if (in_array($weeks[$i], $user->dates)) { ?>
                                                        <option value="<?php echo $user->_id; ?>" selected>
                                                            <?php echo $user->prenom; ?>
                                                        </option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $user->_id; ?>">
                                                            <?php echo $user->prenom; ?>
                                                        </option>
                                                    <?php } ?>
                                                <?php } ?>
                                                <?php
                                            } catch (MongoDB\Driver\ConnectionException $e) {
                                                echo $e->getMessage();
                                            } ?>
                                        </select>
                                    </td>
                                <?php } else { ?>
                                    <td></td>
                                    <td></td>
                                <?php } ?>
                            <?php } ?>
                        </tr>
                    <?php } ?>
            </table>
            <input type="submit" name="submit" value="Submit">
        </form>
        <?php
        // Traitement du formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['submit'])) {
                for ($i = 0; $i < $weeksCount; $i++) {
                    $selectedUserId = isset($_POST['week'][$i]) ? $_POST['week'][$i] : null;
                    if ($selectedUserId !== null) {
                        $selectedYear = $year;
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
        }
    }

    function displayStatistics($year)
    {
        try {
            // Connexion à MongoDB
            $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

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
                    <li>
                        <?php echo $fullName . " : " . $nbDates; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php
        } catch (MongoDB\Driver\ConnectionException $e) {
            echo $e->getMessage();
        }
    }
}
?>