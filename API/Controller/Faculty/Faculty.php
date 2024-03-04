<?php

include_once __DIR__ . '/../../Model/Faculty/FacultyModel.php';
class FacultyController
{

    private $faculty;

    function __construct($pdo)
    {
        $this->faculty = new Faculty($pdo);
    }
    public function test($data)
    {
        return $data;
    }

    public function sendPaylood()
    {
        return;
    }

    public function addFaculty($data)
    {
        return $this->faculty->addFaculty($data);
    }

//     public function getFaculty(){

//     }

//     public function getFacultyInfo($id){
//         $sql = "SELECT `faculty_ID`,`college_ID`,`teaching_position`,`isAdmin`,`first_name`,`middle_name`,`ext_name`,`last_name`,`birthdate`,`age`,`citizenship`,`civil_status`,`sex`,`email`,`employment_status`,`phone_number`,`region`,`province`,`language`,`city`,`barangay`,`profile_image`,`cover_image`
//         FROM `facultymembers` 
//         WHERE faculty_ID = $id;";

//         $result = $this->executeGetQuery($sql);
//         if($result['code'] == 200){
//             return $result['data'][0];
//         }
// }

//     public function getAllFaculty(){
//         $sql = "SELECT * FROM `facultymembers`;";
//         return $this->executeGetQuery($sql);
//     }
}
