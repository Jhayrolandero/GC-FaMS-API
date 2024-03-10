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
                        WHERE faculty_ID = $id;";

            $educAttain = $this->executeGetQuery($educSQL)['data'];

            $certSQL = "SELECT * FROM `certifications-faculty` 
                        INNER JOIN `certifications` on `certifications-faculty`.cert_ID=`certifications`.cert_ID 
                        WHERE faculty_ID = $id;";
            $certs = $this->executeGetQuery($certSQL)['data'];

            $expSQL = "SELECT * FROM `experience-faculty`
                       WHERE faculty_ID = $id;";
            $experience = $this->executeGetQuery($expSQL)['data'];

            return array("educAttainment" => $educAttain, "certifications" => $certs, "industryExp" => $experience, "expertise" => $expertise);
        }

        public function addEduc($form, $id){
            // $sql = "INSERT INTO `educattainment`(faculty_ID, educ_title, educ_school, year_start, year_end educ_details)
            // VALUES (?,?,?,?,?,?)";
            $params = array('faculty_ID','educ_title','educ_school','year_start','year_end','educ_details');
            $tempForm = array($id,
                            $form->educ_title, 
                            $form->educ_school,
                            $form->year_start,
                            $form->year_end,
                            $form->educ_details);
            return $this->prepareAddBind('educattainment', $params, $tempForm);
        }

        public function deleteEduc($id){
            return $this->prepareDeleteBind('educattainment', 'educattainment_ID', $id);
        }
    }
?>