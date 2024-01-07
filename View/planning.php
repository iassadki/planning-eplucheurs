<form action="index.php?ctrl=Date&action=choseYear" method="POST">
    <select name="year" id="year">
        <?php
            for ($i = 2014; $i <= 2020; $i++) {
                $selected = ($i == $year) ? 'selected' : '';
                echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
            }
        ?>
    </select>
    <input type="submit" value="Show">
</form>

<form action="index.php?ctrl=Date&action=sendDates" method="POST">
    <table>
        <?php 
        require_once('./Model/DateManager.php');
        require_once('./Model/UserManager.php');

        $pdoBuilder = new Connection();
        $db1 = $pdoBuilder->getManager();
        $dateManager = new DateManager($db1);
        $date = $dateManager->getAll($year);

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
                                        $userManager = new UserManager($db1);
                                        $all_users = $userManager->getAllUser();
                                        ?>
                                        <?php
                                        foreach ($all_users as $user) { ?>
                                            <?php if (in_array($weeks[$i], $user->dates)) { ?>
                                                <option value="<?php echo $user->_id; ?>" selected><?php echo $user->prenom; ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $user->_id; ?>"><?php echo $user->prenom; ?></option>
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