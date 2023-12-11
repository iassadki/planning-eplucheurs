<?php

class Date
{
    public $months = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12');
    public $days = array('1', '2', '3', '4', '5', '6', '7');
    public $monthWeeks = array('1', '2', '3', '4');

    function getAll($year)
    {
        $weeks = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10',
            '11', '12', '13', '14', '15', '16', '17', '18', '19', '20',
            '21', '22', '23', '24', '25', '26', '27', '28', '29', '30',
            '31', '32', '33', '34', '35', '36', '37', '38', '39', '40',
            '41', '42', '43', '44', '45', '46', '47', '48', '49', '50',
            '51', '52');
        
        for ($i = 0; $i < count($weeks); $i++) {
            $week = $weeks[$i];
            $week_start = date('d-m-Y', strtotime($year . 'W' . str_pad($week, 2, 0, STR_PAD_LEFT)));
            $week_end = date('d-m-Y', strtotime($week_start . ' +6 days'));
            $month = date('m', strtotime($week_start));
            $monthWeek = date('W', strtotime($week_start));
            $monthWeeks[$month][$monthWeek] = $week_end;
            echo $week_end . ' ';
        } 

        // for ($week = 1; $week <= 52; $week++) {
        //     $week_start = date('d-m-Y', strtotime($year . 'W' . str_pad($week, 2, 0, STR_PAD_LEFT)));
        //     $week_end = date('d-m-Y', strtotime($week_start . ' +6 days'));

        //     $month = date('m', strtotime($week_start));

        //     echo $week_end . ' ';
        // }
    }
}
?>