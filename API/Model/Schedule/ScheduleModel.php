<?php
    header('Access-Control-Allow-Origin: http://localhost:4200');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header("Access-Control-Allow-Headers: *");

    include_once "./Controller/global.php";
    
    class Schedule extends GlobalMethods{
        //Faculty id GET sched
        public function getScheduleFaculty($id){
            $sql = "SELECT * 
                    FROM `faculty-course` 
                    INNER JOIN course on `faculty-course`.course_code=course.course_code
                    WHERE faculty_ID = $id;";

            return $this->executeGetQuery($sql)['data'];
        }

        //GET all sched
        public function getScheduleAll(){
            $sql = "SELECT * 
                    FROM `faculty-course` 
                    INNER JOIN course on `faculty-course`.course_code=`course`.course_code;";

            return $this->executeGetQuery($sql);
        }
    }

?>