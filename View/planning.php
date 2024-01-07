<select name="year" id="year">
<form action="" method="get">
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