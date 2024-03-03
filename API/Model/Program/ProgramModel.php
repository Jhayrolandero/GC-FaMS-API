<?php


class Program
{

    private $pdo;
    function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    private function executeQuery($sql, $variable = null)
    {
        $stmt = $this->pdo->prepare($sql);

        if (isset($variable)) {
            $stmt->bindParam(1, $variable, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function addCondition($condition, $variable)
    {
        switch (strtolower($condition)) {
            case 'where':
                $sqlQuery = " WHERE $variable = :id";
                return $sqlQuery;
            default:
                $sql = "Nah i'd win";
                return $sql;
        }
    }

    public function getPrograms($id = null)
    {
        $sql = "SELECT * FROM program";

        if (isset($id)) {
            $sql .= $this->addCondition('where', 'college_id');
        }

        return $this->executeQuery($sql, $id);
    }
}
