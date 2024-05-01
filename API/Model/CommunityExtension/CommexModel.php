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

        $sql = "SELECT commex.commex_ID, commex_title, commex_details, commex_header_img, commex_date
                FROM `$table`
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

            if ($key === "attendees") {
                continue;
            }

            array_push($params, $key);
            array_push($tempForm, $value);
        }

        // Add Commex 
        $this->prepareAddBind('commex', $params, $tempForm);

        $lastCommexID = $this->getLastID('commex') - 1;
        if (empty($_POST["attendees"])) {
            return
                $this->prepareAddBind(
                    'commex-faculty',
                    array('faculty_ID', 'commex_ID'),
                    array($this->verifyToken()['payload'], $lastCommexID)
                );
        }

        $this->prepareAddBind(
            'commex-faculty',
            array('faculty_ID', 'commex_ID'),
            array($this->verifyToken()['payload'], $lastCommexID)
        );



        $faculty_ID = [];
        $college_ID = [];

        foreach ($_POST["attendees"] as $attendee) {

            $attendee = json_decode($attendee);

            if (!$this->arrayIncludes($college_ID, [$lastCommexID, $attendee->college_ID])) {
                array_push($college_ID, [$lastCommexID, $attendee->college_ID]);
            };
            array_push($faculty_ID, [$lastCommexID, $attendee->faculty_ID]);
            // var_dump($attendee);
        }

        $this->prepareMultipleAddBind('commex-college', ["commex_id", "college_ID"], $college_ID);
        return $this->prepareMultipleAddBind('commex-faculty', ["commex_id", "faculty_ID"], $faculty_ID);
    }
    public function addAttendee($data)
    {

        $cols = [];
        $values = [];

        if (is_string($data[0])) {
            $data = json_decode($data[0]);
            $keys = array_keys((array)$data);

            foreach ($keys as $key) {
                array_push($cols, $key);
            }
        } else {
            foreach (array_keys($data[0]) as $key) {
                array_push($cols, $key);
            }
        }


        $colLength = count($cols);


        if (is_string($data[0])) {

            foreach ($data as $item => $item_value) {
                // print_r($item);
                print_r($item_value);
            }
        } else {
            foreach ($data as $item) {
                $value = [];

                for ($i = 0; $i < $colLength; $i++) {
                    array_push($value, $item[$cols[$i]]);
                }

                array_push($values, $value);
            }
        }

        return $this->prepareMultipleAddBind('commex-faculty', $cols, $values);

        // var_dump($params);

        // $this->prepareAddBind(());
        // $sql = "
        // INSERT INTO `commex-faculty`
        // (commex_id, faculty_id) 
        // VALUES
        // ()
        // ";
    }

    public function getAttendee($commex_ID, $query = null, $faculty_ID = null)
    {

        $selectRes = '';
        $from = '
                FROM facultymembers 
                INNER JOIN `commex-faculty` on facultymembers.faculty_ID=`commex-faculty`.faculty_ID 
                ';

        switch ($query) {
            case 'number':
                $selectRes = "SELECT COUNT(*) as `count`";
                $condition = "WHERE `commex-faculty`.commex_ID = $commex_ID";
                break;
            case 'check':
                $selectRes = "SELECT COUNT(*) as `attended`";
                $from = 'FROM `commex-faculty`';
                $condition = "WHERE commex_ID = $commex_ID AND faculty_ID = $faculty_ID;";
                break;
            default:
                $selectRes = "SELECT facultymembers.first_name, facultymembers.middle_name, facultymembers.last_name, facultymembers.ext_name, facultymembers.faculty_ID, facultymembers.profile_image";
                $condition = "WHERE `commex-faculty`.commex_ID = $commex_ID";
                break;
        }


        $sql = "$selectRes
                $from
                $condition";
        return $this->executeGetQuery($sql);
    }


    public function checkAttendee($commex_ID, $faculty_ID)
    {

        $sql = "
        SELECT COUNT(*) as attended
        FROM `commex-faculty`
        WHERE commex_ID = `$commex_ID` AND faculty_ID = `$faculty_ID`;
        ";

        return $this->executeGetQuery($sql);
    }
}
