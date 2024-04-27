<?php


include_once(__DIR__ . '/../../Controller/global.php');
class Faculty extends GlobalMethods
{

    private $tableName = 'facultymembers';

    public function deleteFaculty($id)
    {
        return $this->prepareDeleteBind($this->tableName, 'faculty_ID', $id);
    }
    public function getFacultyInfo($id = null)
    {
        $sql = "SELECT 
                `faculty_ID`,
                `college_name`,
                `college_abbrev`,
                `teaching_position`,
                `isAdmin`,
                `first_name`,
                `middle_name`,
                `ext_name`,
                `last_name`,
                `birthdate`,
                `age`,
                `citizenship`,
                `civil_status`,
                `sex`,
                `email`,
                `employment_status`,
                `phone_number`,
                `region`,
                `province`,
                `language`,
                `city`,
                `barangay`,
                `profile_image`,
                `cover_image`,
                facultymembers.`college_ID` 
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
        return $this->executeGetQuery($sql)['data'];
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

    public function addFaculty()
    {
        $filepath = null;
        $filepathCover = null;
        $params = [];
        $tempForm = [];

        //Calls function that saves image.
        if (!empty($_FILES['profile_image'])) {
            $filepath = $this->saveImage("/../../Image_Assets/Faculty_Profile/", $this->tableName, "profile_image");
            array_push($params, 'profile_image');
            array_push($tempForm, $filepath);
        }

        if (!empty($_FILES['cover_image'])) {
            $filepathCover = $this->saveImage("/../../Image_Assets/Faculty_Cover/", $this->tableName, "cover_image");
            array_push($params, 'cover_image');
            array_push($tempForm, $filepathCover);
        }

        if ($this->emailExist()) {
            return ["code" => 406, "errmsg" => "Email already Exist!"];
        }

        foreach ($_POST as $key => $value) {
            if ($key === 'password') {
                $value = password_hash($value, PASSWORD_DEFAULT);
                array_push($params, $key);
                array_push($tempForm, $value);
            } else {
                array_push($params, $key);
                array_push($tempForm, $value);
            }
        }

        return $this->prepareAddBind($this->tableName, $params, $tempForm);
    }

    public function fetchLastID()
    {
        /**
         * @param $table 
         */
        return $this->getLastID($this->tableName);
    }

    public function editFaculty($data = null, $id)
    {

        $params = $this->getParams($data);
        $tempForm = $this->getValues($data);
        array_push($tempForm, $id);
        return $this->prepareEditBind($this->tableName, $params, $tempForm, 'faculty_ID');

        // Ignore this
        // $filepath = null;
        // $filepathCover = null;
        // $params = [];
        // $tempForm = [];

        // //Calls function that saves image.
        // if (!empty($_FILES['profile_image'])) {

        //     // return 'Profile Exist';
        //     $filepath = $this->saveImage("/../../Image_Assets/Faculty_Profile/", $this->tableName, "profile_image", $id);
        //     array_push($params, 'profile_image');
        //     array_push($tempForm, $filepath);
        // }

        // if (!empty($_FILES['cover_image'])) {
        //     // return 'Cover Exist';
        //     $filepathCover = $this->saveImage("/../../Image_Assets/Faculty_Cover/", $this->tableName, "cover_image", $id);
        //     array_push($params, 'cover_image');
        //     array_push($tempForm, $filepathCover);
        // }

        // array_push($tempForm, $id);
        // return $this->prepareEditBind($this->tableName, $params, $tempForm, 'faculty_ID');
    }
}
