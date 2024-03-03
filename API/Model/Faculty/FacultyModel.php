<?php


include_once(__DIR__ . '/../../Controller/global.php');
class Faculty
{
    private $pdo;
    private $global;

    function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    private function getColumns($data)
    {
        $columns = array_keys(get_object_vars($data));
        return $columns;
    }

    private function addColumns($sqlStr, $columns)
    {
        $sqlStr .= "(" . implode(', ', $columns) . ")"
            . " VALUES ( ";

        for ($i = 0; $i < count($columns); $i++) {
            if ($i == count($columns) - 1) {
                $sqlStr .= '? )';
            } else {
                $sqlStr .= '?, ';
            }
        }

        return $sqlStr;
    }

    public function executeQuery($sql, $data)
    {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data->college_ID,
            $data->teaching_position,
            $data->first_name,
            $data->last_name,
            $data->birthdate,
            $data->age,
            $data->citizenship,
            $data->civil_status,
            $data->sex,
            $data->email,
            $data->employment_status,
            $data->phone_number,
            $data->middle_name,
            $data->ext_name,
            $data->region,
            $data->province,
            $data->language,
            $data->city,
            $data->barangay
        ]);
    }

    public function addFaculty($data)
    {
        $columns = $this->getColumns($data);

        $sql = "INSERT INTO facultymembers ";

        $sql = $this->addColumns($sql, $columns);

        return $this->executeQuery($sql, $data);
        // return $sql;
    }
}

/**{
    "college_id": 2,
    "teaching_position": "Coordinator",
    "first_name": "Not",
    "last_name": "Sure",
    "birthdate": "1994-10-23",
    "age": 20,
    "citizenship": "Filipino",
    "civil_status": "Married",
    "sex": "Male",
    "email": "123@gmail.com",
    "employment_status": 0,
    "address": "123 st"
}
 */
