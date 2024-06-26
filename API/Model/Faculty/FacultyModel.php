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
        $sql = "SELECT *
                FROM `facultymembers` 
                INNER JOIN `college` on `facultymembers`.`college_ID`=`college`.`college_ID` 
                WHERE faculty_ID = $id;";

        $result = $this->executeGetQuery($sql);
        if ($result['code'] == 200) {
            $data = $this->secured_encrypt($result['data'][0]);
            return $data;
        }
    }

    public function getAllFaculty($college_ID)
    {
        $sql = "SELECT  
               *
                FROM `facultymembers`
                LEFT JOIN `college` ON `facultymembers`.`college_ID` = `college`.`college_ID`";

        if (isset($college_ID)) {
            $sql .= "WHERE `facultymembers`.`college_ID` = $college_ID AND `facultymembers`.`teaching_position` != 'Admin'";
        }

        $data = $this->secured_encrypt($this->executeGetQuery($sql)['data']);
        return $data;
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

    /**
     * 
     * DOn;t if something uses this func
     */
    public function editFaculty($data = null, $id)
    {
        $params = $this->getParams($data);
        $tempForm = $this->getValues($data);
        array_push($tempForm, $id);
        return $this->prepareEditBind($this->tableName, $params, $tempForm, 'faculty_ID');
    }


    public function editProfile($id)
    {
        $params = [];
        $tempForm = [];

        //Calls function that saves image.
        if (!empty($_FILES['profile_image'])) {
            $filepath = $this->saveImage("/../../Image_Assets/Faculty_Profile/", $this->tableName, "profile_image", $id);
            array_push($params, 'profile_image');
            array_push($tempForm, $filepath);
        } else {
            return "Empty File";
        }
        array_push($tempForm, $id);
        return $this->prepareEditBind($this->tableName, $params, $tempForm, 'faculty_ID');
    }

    public function editCover($id)
    {

        $params = [];
        $tempForm = [];

        if (!empty($_FILES['cover_image'])) {
            $filepathCover = $this->saveImage("/../../Image_Assets/Faculty_Cover/", $this->tableName, "cover_image", $id);
            array_push($params, 'cover_image');
            array_push($tempForm, $filepathCover);
        } else {
            return "Empty File";
        }
        array_push($tempForm, $id);

        return $this->prepareEditBind($this->tableName, $params, $tempForm, 'faculty_ID');
    }

    public function editFaculty2($data, $id)
    {
        $params = [];
        $values = [];


        foreach ($data as $key => $value) {
            array_push($params, $key);
            array_push($values, $value);
        }
        array_push($values, $id);


        return $this->prepareEditBind($this->tableName, $params, $values, 'faculty_ID');
    }

    public function editPassword($data, $id)
    {
        $values = [];

        $password_hash = password_hash($data, PASSWORD_DEFAULT);
        array_push($values, $password_hash);
        array_push($values, $id);

        return $this->prepareEditBind($this->tableName, ["password"], $values, 'faculty_ID');
    }
}
