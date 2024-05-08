<?php
// header('Access-Control-Allow-Origin: http://localhost:4200');
// header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
// header("Access-Control-Allow-Headers: *");

include_once "./Controller/global.php";

class College extends GlobalMethods
{
    //Faculty id GET sched
    public function getCollege()
    {
        $sql = "SELECT * FROM college";

        $data = $this->executeGetQuery($sql)['data'];
        return $this->secured_encrypt($data);
    }
}
