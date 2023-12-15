<?php

class Date
{
    public $months = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12');
    public $days = array('1', '2', '3', '4', '5', '6', '7');
    public $monthWeeks = array('1', '2', '3', '4');

    function getAll($year)
    {
        $weeks = [];
         
        for ($i = 0; $i < 52; $i++) {
            $week_start = date('d-m-Y', strtotime($year . 'W' . str_pad($i+1, 2, 0, STR_PAD_LEFT)));
            $week_end = date('d-m-Y', strtotime($week_start . ' +6 days'));
            $weeks[$i] = $week_end;
            
        } 
        ?> 
            <form action="" method="post">
            <?php foreach ($weeks as $week) { ?>
                  <label for="week"><?php echo $week; ?></label>  
                  <select name="week">
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>   
            <?php } ?>                   
                
                <input type="submit" name="submit" value="Submit">
            </form>
        <?php
         
        

        // for ($week = 1; $week <= 52; $week++) {
        //     $week_start = date('d-m-Y', strtotime($year . 'W' . str_pad($week, 2, 0, STR_PAD_LEFT)));
        //     $week_end = date('d-m-Y', strtotime($week_start . ' +6 days'));

        //     $month = date('m', strtotime($week_start));

        //     echo $week_end . ' ';
        // }
    }
}
?>