<?php


include_once(__DIR__ . '/../../Controller/global.php');
class Faculty extends GlobalMethods
{
    // private function getColumns($data)
    // {
    //     $columns = array_keys(get_object_vars($data));
    //     return $columns;
    // }

    // private function addColumns($sqlStr, $columns)
    // {
    //     $sqlStr .= "(" . implode(', ', $columns) . ")"
    //         . " VALUES ( ";

    //     for ($i = 0; $i < count($columns); $i++) {
    //         if ($i == count($columns) - 1) {
    //             $sqlStr .= '? )';
    //         } else {
    //             $sqlStr .= '?, ';
    //         }
    //     }

    //     return $sqlStr;
    // }

    // public function executeQuery($sql, $data)
    // {
    //     $stmt = $this->pdo->prepare($sql);
    //     return $stmt->execute([
    //         $data->college_ID,
    //         $data->teaching_position,
    //         $data->first_name,
    //         $data->last_name,
    //         $data->birthdate,
    //         $data->age,
    //         $data->citizenship,
    //         $data->civil_status,
    //         $data->sex,
    //         $data->email,
    //         $data->employment_status,
    //         $data->phone_number,
    //         $data->middle_name,
    //         $data->ext_name,
    //         $data->region,
    //         $data->province,
    //         $data->language,
    //         $data->city,
    //         $data->barangay
    //     ]);
    // }

    // public function addFaculty($data)
    // {
    //     $columns = $this->getColumns($data);

    //     $sql = "INSERT INTO facultymembers ";

    //     $sql = $this->addColumns($sql, $columns);

    //     return $this->executeQuery($sql, $data);
    //     // return $sql;
    // }



    public function getFacultyInfo($id)
    {
        $sql = "SELECT `faculty_ID`,`college_name`,`college_abbrev`,`teaching_position`,`isAdmin`,`first_name`,`middle_name`,`ext_name`,`last_name`,`birthdate`,`age`,`citizenship`,`civil_status`,`sex`,`email`,`employment_status`,`phone_number`,`region`,`province`,`language`,`city`,`barangay`,`profile_image`,`cover_image` 
                FROM `facultymembers` 
                INNER JOIN `college` on `facultymembers`.`college_ID`=`college`.`college_ID` 
                WHERE faculty_ID = $id;";

        $result = $this->executeGetQuery($sql);
        if ($result['code'] == 200) {
            return $result['data'][0];
        }
    }

    public function getAllFaculty()
    {
        $sql = "SELECT * FROM `facultymembers`;";
        return $this->executeGetQuery($sql);
    }


    public function addFaculty()
    {
        return "Add faculty Works!";
    }
}
