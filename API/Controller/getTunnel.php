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
        private $commex;
        private $college;
        private $resume;

        public function __construct(){
            $this->faculty = new Faculty();
            $this->schedule = new Schedule();
            $this->commex = new Commex();
            $this->college = new College();
            $this->resume = new ResumeInfo();
        }

        public function toGetFaculty($id){
            return $this->faculty->getFacultyInfo($id);
        }

        public function toGetSchedule($id){
            return $this->schedule->getScheduleFaculty($id);
        }

        public function toGetCommex($id){
            return $this->commex->getCommexFaculty($id);
        }

        public function toGetCollege($id){
            return $this->college->getCollege();
        }

        public function toGetResumeInfo($id){
            return $this->resume->getResumeInfo($id);
        }
    }

?>