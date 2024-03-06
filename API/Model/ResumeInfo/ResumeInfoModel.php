<?php
    header('Access-Control-Allow-Origin: http://localhost:4200');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header("Access-Control-Allow-Headers: *");

    include_once "./Controller/global.php";
    
    class ResumeInfo extends GlobalMethods{
        //Faculty id GET sched
        public function getResumeInfo($id){
            $educAttain = [];
            $certs = [];
            $experience = [];
            $expertise = [];

            $educSQL = "SELECT * 
                        FROM `educattainment` 
                        INNER JOIN school on `educattainment`.school_ID=`school`.school_ID
                        WHERE faculty_ID = $id;";

            $educAttain = $this->executeGetQuery($educSQL)['data'];

            $certSQL = "SELECT * FROM `certifications-faculty` 
                        INNER JOIN `certifications` on `certifications-faculty`.cert_ID=`certifications`.cert_ID 
                        WHERE faculty_ID = $id;";
            $certs = $this->executeGetQuery($certSQL)['data'];

            return array("educAttainment" => $educAttain, "certifications" => $certs, "industryExp" => $experience, "expertise" => $expertise);
        }

    }

?>