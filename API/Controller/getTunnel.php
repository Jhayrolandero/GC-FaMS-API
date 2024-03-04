<?php
    header('Access-Control-Allow-Origin: http://localhost:4200');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header("Access-Control-Allow-Headers: *");

    //Fetches every single file in Model
    foreach (glob("./Model/*/*.php") as $filename) {
        include_once $filename;
    }
    
    
    class GetTunnel extends Connection{
        private $faculty;
        private $schedule;

        public function __construct(){
            $this->faculty = new Faculty();
            $this->schedule = new Schedule();
        }

        public function toGetFaculty($id){
            return $this->faculty->getFacultyInfo($id);
        }

        public function toGetSchedule($id){
            return $this->schedule->getScheduleFaculty($id);
        }
    }

?>