<?php

include_once('../../Controller/global.php');
class Faculty
{
    private $pdo;
    private $global;
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->global = new GlobalMethods;
    }


    public function addFaculties($data)
    {
        $sql = "INSERT INTO FACULTIES (
            programID, 
            teaching_position, 
            first_name, 
            last_name,
            middle_name,
            ext_name,
            email,
            phone_number,
            birthdate,
            region,
            province,
            city,
            barangay,
            gender,
            language,
            nationality
            )";
    }
}
