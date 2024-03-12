<?php


include_once(__DIR__ . '/../../Controller/global.php');
class Faculty extends GlobalMethods
{

    public function getFacultyInfo($id)
    {
        $sql = "SELECT `faculty_ID`,`college_name`,`college_abbrev`,`teaching_position`,`isAdmin`,`first_name`,`middle_name`,`ext_name`,`last_name`,`birthdate`,`age`,`citizenship`,`civil_status`,`sex`,`email`,`employment_status`,`phone_number`,`region`,`province`,`language`,`city`,`barangay`,`profile_image`,`cover_image` 
                FROM `facultymembers` 
                INNER JOIN `college` on `facultymembers`.`college_ID`=`college`.`college_ID` 
                WHERE faculty_ID = $id;";

        $result = $this->executeGetQuery($sql);
        if ($result['code'] == 200) {
            return $result['data'][0];
        }
    }

    public function getAllFaculty()
    {
        $sql = "SELECT * FROM `facultymembers`;";
        return $this->executeGetQuery($sql);
    }

    private function emailExist()
    {
        $email = $_POST['email'];
        $sql = "SELECT email from `facultymembers`
                WHERE email = '$email';";


        if ($this->executeGetQuery($sql)["data"]) {
            return true;
        }

        return false;
    }

    public function addFaculty($profileIMGPath = null)
    {


        if ($this->emailExist()) {
            return ["code" => 406, "errmsg" => "Email already Exist!"];
        }

        $params = [];
        $tempForm = [];
        foreach ($_POST as $key => $value) {
            if ($key === 'password') {
                $value = password_hash($value, PASSWORD_DEFAULT);
                array_push($params, $key);
                array_push($tempForm, $value);
            } elseif ($key === 'profile_image' && empty($profileIMGPath)) {
                array_push($params, 'profile_image');
                array_push($tempForm, $profileIMGPath);
            } else {
                array_push($params, $key);
                array_push($tempForm, $value);
            }
        }

        if (isset($profileIMGPath)) {
            array_push($params, 'profile_image');
            array_push($tempForm, $profileIMGPath);
        }


        return $this->prepareAddBind('facultymembers', $params, $tempForm);
    }



    public function fetchLastID()
    {
        /**
         * @param $table 
         */
        return $this->getLastID('facultymembers')["data"][0]['AUTO_INCREMENT'];
    }
}
