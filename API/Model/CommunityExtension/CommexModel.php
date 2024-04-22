<?php
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: *");

include_once "./Controller/global.php";

class Commex extends GlobalMethods
{
    //Faculty id GET sched
    public function getCommex($id, $query)
    {

        $table = '';
        $condID = '';
        switch ($query) {
            case 'college':
                $table = 'commex-college';
                $condID = 'college_ID';
                break;
            case 'faculty':
                $table = 'commex-faculty';
                $condID = 'faculty_ID';
                break;
        }

        $sql = "SELECT * FROM `$table`
                INNER JOIN commex on `$table`.`commex_ID`=`commex`.`commex_ID`
                WHERE $condID = $id;";

        return $this->executeGetQuery($sql)['data'];
        // return $sql;
    }

    //GET all sched
    public function getCommexAll()
    {
        $sql = "SELECT * FROM `commex`;";

        return $this->executeGetQuery($sql)['data'];
    }
    /*
    
    
    SELECT c.*, COUNT(cf.faculty_ID) AS attendee_count
FROM commex c
LEFT JOIN `commex-faculty` cf ON c.commex_ID = cf.commex_ID
GROUP BY c.commex_ID, c.commex_title, c.commex_date;

    */

    public function addCommex($data)
    {
        $filepath = null;

        $params = [];
        $tempForm = [];

        //Calls function that saves image.
        if (!empty($_FILES)) {
            $filepath = $this->saveImage("/../../Image_Assets/CommunityExtensions/", "commex", "commex_header_img");
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
    public function addAttendee($data)
    {

        $cols = [];
        $values = [];

        foreach (array_keys($data[0]) as $key) {
            array_push($cols, $key);
        }


        $colLength = count($cols);
        foreach ($data as $item) {
            $value = [];

            for ($i = 0; $i < $colLength; $i++) {
                array_push($value, $item[$cols[$i]]);
            }

            array_push($values, $value);
        }

        return $this->prepateMultipleAddBind('commex-faculty', $cols, $values);

        // var_dump($params);

        // $this->prepareAddBind(());
        // $sql = "
        // INSERT INTO `commex-faculty`
        // (commex_id, faculty_id) 
        // VALUES
        // ()
        // ";
    }

    public function getAttendee($id, $query = null)
    {

        $selectRes = '';

        switch ($query) {
            case 'number':
                $selectRes = "SELECT COUNT(*) as `count`";
                break;
            default:
                $selectRes = "SELECT facultymembers.first_name, facultymembers.middle_name, facultymembers.last_name, facultymembers.ext_name, facultymembers.faculty_ID, facultymembers.profile_image";
                break;
        }


        $sql = "$selectRes
                FROM facultymembers INNER JOIN `commex-faculty` on facultymembers.faculty_ID=`commex-faculty`.faculty_ID 
                WHERE `commex-faculty`.commex_ID = $id";
        return $this->executeGetQuery($sql);
    }
}
