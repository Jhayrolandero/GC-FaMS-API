<?php
include_once(__DIR__ . '/../../Controller/global.php');
class Evaluation extends GlobalMethods{
    public function getEvaluation($id){
        $sql = "SELECT * FROM `evaluation` 
                WHERE faculty_ID = 3";

        $result = $this->executeGetQuery($sql);
        if($result['code'] == 200){
            return $result['data'];
        }
    }


    public function getProfileEvaluation($id) {
        
    }
}