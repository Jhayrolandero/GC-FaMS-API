<?php
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: *");

include_once "./Controller/global.php";

class Commex extends GlobalMethods
{
    //Faculty id GET sched
    public function getCommexFaculty($id)
    {
        $sql = "SELECT * FROM `commex-faculty`
                    INNER JOIN commex on `commex-faculty`.`commex_ID`=`commex`.`commex_ID`
                    WHERE faculty_ID = $id;";

        return $this->executeGetQuery($sql)['data'];
    }

    //GET all sched
    public function getCommexAll()
    {
        $sql = "SELECT * FROM `commex-faculty`
                    INNER JOIN commex on `commex-faculty`.`commex_ID`=`commex`.`commex_ID`;";

        return $this->executeGetQuery($sql);
    }

    public function addCommex($data)
    {
        $filepath = null;


        $params = [];
        $tempForm = [];

        //Calls function that saves image.
        if (!empty($_FILES)) {
            $filepath = $this->saveImage("/../../Image_Assets/CommunityExtensions/");
            array_push($params, 'commex_header_img');
            array_push($tempForm, $filepath);
        }

        //Iterates through FormData, and assigns parameter and value.
        foreach ($_POST as $key => $value) {
            array_push($params, $key);
            array_push($tempForm, $value);
        }

        //Add Commex 
        $this->prepareAddBind('commex', $params, $tempForm);

        //Assign Commex to faculty
        return $this->prepareAddBind(
            'commex-faculty',
            array('faculty_ID', 'commex_ID'),
            array($this->verifyToken()['payload'], $this->getLastID('commex') - 1)
        );
    }
}
