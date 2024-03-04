<?php
    header('Access-Control-Allow-Origin: http://localhost:4200');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header("Access-Control-Allow-Headers: *");

    include_once "./Controller/global.php";
    
    class Commex extends GlobalMethods{
        //Faculty id GET sched
        public function getCommexFaculty($id){
            $sql = "SELECT * FROM `commex-faculty`
                    INNER JOIN commex on `commex-faculty`.`commex_ID`=`commex`.`commex_ID`
                    WHERE faculty_ID = $id;";

            return $this->executeGetQuery($sql)['data'];
        }

        //GET all sched
        public function getCommexAll(){
            $sql = "SELECT * FROM `commex-faculty`
                    INNER JOIN commex on `commex-faculty`.`commex_ID`=`commex`.`commex_ID`;";

            return $this->executeGetQuery($sql);
        }


    }

?>