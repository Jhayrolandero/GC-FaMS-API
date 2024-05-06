<?php
include_once(__DIR__ . '/../../Controller/global.php');
class Evaluation extends GlobalMethods
{
    public function getEvaluation($id)
    {
        $sql = "SELECT * FROM `evaluation` 
                WHERE faculty_ID = $id";

        $result = $this->executeGetQuery($sql);
        if ($result['code'] == 200) {
            $data = $result['data'];
            return $this->secured_encrypt($data);
        }
    }
    public function getCollegeEvaluation($id)
    {
        $sql = "SELECT * FROM `evaluation` 
                INNER JOIN `facultymembers` on `evaluation`.`faculty_ID`=`facultymembers`.`faculty_ID`
                WHERE college_ID = $id";

        $result = $this->executeGetQuery($sql);
        if ($result['code'] == 200) {
            $data = $result['data'];
            return $this->secured_encrypt($data);
        }
    }

    public function addEval($form, $id)
    {
        $params = array('faculty_ID', 'semester', 'evaluation_year', 'param1_score', 'param2_score', 'param3_score', 'param4_score', 'param5_score', 'param6_score');
        $tempForm = array(
            $id,
            $form->semester,
            $form->evaluation_year,
            $form->param1_score,
            $form->param2_score,
            $form->param3_score,
            $form->param4_score,
            $form->param5_score,
            $form->param6_score
        );
        return $this->prepareAddBind('evaluation', $params, $tempForm);
    }


    public function getProfileEvaluation($id)
    {
    }
}
