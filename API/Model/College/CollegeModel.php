<?php
class College
{
    private $pdo;
    private $global;

    function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    private function executeQuery($sql)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getCollege()
    {
        $sql = "SELECT * FROM college";

        return $this->executeQuery($sql);
    }
}
