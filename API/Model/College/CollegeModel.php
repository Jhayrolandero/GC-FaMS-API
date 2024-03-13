<?php
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: *");

include_once "./Controller/global.php";

class College extends GlobalMethods
{
    //Faculty id GET sched
    public function getCollege()
    {
        $sql = "SELECT * FROM college";

        return $this->executeGetQuery($sql)['data'];
    }
}
