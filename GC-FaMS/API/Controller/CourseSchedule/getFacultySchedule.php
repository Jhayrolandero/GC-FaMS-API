<?php
    
    class Schedule{
        private $pdo;
        private $faculty_ID = 1;

        public function __construct(\PDO $pdo){
            $this->pdo = $pdo;
        }

        public function executeQuery($sql){
            return $this->pdo->query($sql)->fetchAll();
        }

        //Faculty id and week GET sched
        public function getScheduleWeek($id, $week){
            $sql = "SELECT * 
                    FROM `faculty-course` 
                    INNER JOIN course on `faculty-course`.course_code=course.course_code
                    WHERE faculty_ID = $this->faculty_ID AND week = $week;";

            return $this->executeQuery($sql);
        }

        //Faculty id GET sched
        public function getScheduleFaculty($id){
            $sql = "SELECT * 
                    FROM `faculty-course` 
                    INNER JOIN course on `faculty-course`.course_code=course.course_code
                    WHERE faculty_ID = $this->faculty_ID;";

            return $this->executeQuery($sql);
        }

        //GET all sched
        public function getScheduleAll(){
            $sql = "SELECT * 
                    FROM `faculty-course` 
                    INNER JOIN course on `faculty-course`.course_code=`course`.course_code;";

            return $this->executeQuery($sql);
        }
    }

?>