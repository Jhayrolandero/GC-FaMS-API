<?php
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: *");

include_once "./Controller/global.php";

class Commex
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function executeQuery($sql)
    {
        return $this->pdo->query($sql)->fetchAll();
    }

    //Faculty id GET sched
    public function getCommexFaculty($id)
    {
        $sql = "SELECT * FROM `commex-faculty`
                    INNER JOIN commex on `commex-faculty`.`commex_ID`=`commex`.`commex_ID`
                    WHERE faculty_ID = $id;";

        return $this->executeQuery($sql);
    }

    //GET all sched
    public function getCommexAll()
    {
        $sql = "SELECT * FROM `commex-faculty`
                    INNER JOIN commex on `commex-faculty`.`commex_ID`=`commex`.`commex_ID`;";

        return $this->executeQuery($sql);
    }
}
