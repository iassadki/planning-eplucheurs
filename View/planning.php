<form action="index.php?ctrl=User&action=choseYear" method="POST">
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