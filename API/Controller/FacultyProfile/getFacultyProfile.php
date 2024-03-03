<?php
    header('Access-Control-Allow-Origin: http://localhost:4200');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header("Access-Control-Allow-Headers: *");

    include_once "./Controller/global.php";
    
    class Profile{
        private $pdo;

        public function __construct(\PDO $pdo){
            $this->pdo = $pdo;
        }

        public function executeQuery($sql){
            return $this->pdo->query($sql)->fetch();
        }

        //Faculty id GET sched
        public function getFacultyInfo($id){
                $sql = "SELECT `faculty_ID`,`college_ID`,`teaching_position`,`isAdmin`,`first_name`,`last_name`,`birthdate`,`age`,`citizenship`,`civil_status`,`sex`,`email`,`employment_status`,`address`,`profile_image`,`cover_image`
                FROM `facultymembers` 
                WHERE faculty_ID = $id;";
                return $this->executeQuery($sql);
        }

        public function getAllFaculty(){
            $sql = "SELECT * FROM `facultymembers`;";
            return $this->executeQuery($sql);
        }
    }

?>