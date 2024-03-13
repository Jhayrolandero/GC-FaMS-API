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

        public function addCommex($profileIMGPath = null){
   
            $params = [];
            $tempForm = [];

            foreach ($_POST as $key => $value) {
                if ($key === 'password') {
                    $value = password_hash($value, PASSWORD_DEFAULT);
                    array_push($params, $key);
                    array_push($tempForm, $value);
                } elseif ($key === 'commex_header_img' && empty($profileIMGPath)) {
                    array_push($params, 'commex_header_img');
                    array_push($tempForm, $profileIMGPath);
                } else {
                    array_push($params, $key);
                    array_push($tempForm, $value);
                }
            }
    
            if (isset($profileIMGPath)) {
                array_push($params, 'commex_header_img');
                array_push($tempForm, $profileIMGPath);
            }
            //Add Commex 
            $this->prepareAddBind('commex', $params, $tempForm);

            //Assign commex to faculty
            return $this->prepareAddBind('commex-faculty', array('faculty_ID', 'commex_ID'), array($this->verifyToken()['payload'], $this->fetchLastID() - 1));
        }

        public function fetchLastID()
        {
            /**
             * @param $table 
             */
            return $this->getLastID('commex')["data"][0]['AUTO_INCREMENT'];
        }
    }

?>