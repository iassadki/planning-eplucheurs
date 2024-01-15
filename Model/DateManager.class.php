<?php
class DateManager {     
    private $connection;
    
    public function __construct($connection){
        $this->connection = $connection;
    }

    public function getAll($year){
        $weeks = []; 
        for ($i = 0; $i < 52; $i++) {
            $week_start = date('d-m-Y', strtotime($year . 'W' . str_pad($i + 1, 2, 0, STR_PAD_LEFT)));
            $week_end = date('d-m-Y', strtotime($week_start . ' +6 days'));
            $weeks[$i] = $week_end;
        }
        $columns = 4;
        $weeksCount = count($weeks);
        $rows = ceil($weeksCount / $columns);
        return [
            'weeks' => $weeks,
            'columns' => $columns,
            'weeksCount' => $weeksCount,
            'rows' => $rows,
        ];
    }   
}
    
?>