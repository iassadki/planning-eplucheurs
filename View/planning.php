<?php
    require_once('./Model/DateManager.php');
    require_once('./Model/UserManager.php');
    $pdoBuilder = new Connection();
    $db1 = $pdoBuilder->getManager();
    $dateManager = new DateManager($db1);
    $date = $dateManager->getAll($year);
    $userManager = new UserManager($db1);
    ?>
<br>
<center><a href="index.php?ctrl=User&action=logout" method="POST" class="deco-btn" style="text-align: center;">Deconnexion</a></center>
<br>
<form action="index.php?ctrl=Date&action=choseYear" method="POST">
    <center>
        <select name="year" id="year" class="selectDates">
            <?php
            for ($i = 2014; $i <= 2020; $i++) {
                $selected = ($i == $year) ? 'selected' : '';
                echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
            }
            ?>
        </select>
        <input type="submit" value="Show">
    </center>
</form>

<form action="index.php?ctrl=Date&action=sendDates" method="POST">
    <table>
        <?php
        

        $weeks = $date['weeks'];
        for ($row = 0; $row < $date['rows']; $row++) { ?>
            <tbody>
                <tr>
                    <?php for ($col = 0; $col < $date['columns']; $col++) { ?>
                        <?php $i = $row * $date['columns'] + $col; ?>
                        <?php if ($i < $date['weeksCount']) { ?>
                            <td>
                                <label for="week_<?php echo $i; ?>">
                                    <?php echo $weeks[$i]; ?>
                                </label>
                                <select name="week[<?php echo $i ?>]">
                                    <?php try {
                                        
                                        $all_users = $userManager->getAllUser();
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
    <center>
        <input type="submit" class="submit-btn" name="submit" value="Submit">
    </center>
</form>


<h2>Statistiques par ordre croissant</h2>
<?php
    $userManager->getStats();
?>