<?php
    header('Access-Control-Allow-Origin: http://localhost:4200');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header("Access-Control-Allow-Headers: *");

    include_once "./Controller/global.php";
    
    class Schedule extends GlobalMethods{
        public function getScheduleFaculty($id){
            $factCourseSql = "SELECT * 
                    FROM `course-faculty` 
                    INNER JOIN course on `course-faculty`.course_code=`course`.`course_code`
                    WHERE faculty_ID = $id;";

            $courseSql = "SELECT *
                          FROM  `course`";


            return [$this->executeGetQuery($factCourseSql)['data'], $this->executeGetQuery($courseSql)['data']];
        }

        public function getScheduleAll(){
            $sql = "SELECT * 
                    FROM `faculty-course` 
                    INNER JOIN course on `faculty-course`.course_code=`course`.course_code;";

            return $this->executeGetQuery($sql);
        }

        public function addCourse($form, $id){
            $params = array('course_code', 'faculty_ID', 'class_count');
            $tempForm = array(
                $form->course_code,
                $id,
                $form->class_count,
            );
            return $this->prepareAddBind('course-faculty', $params, $tempForm);
        }
    }

?>