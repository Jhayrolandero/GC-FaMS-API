<?php
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: *");


use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;

require_once('../vendor/autoload.php');

include_once "./Model/database.php";

class GlobalMethods extends Connection
{

    private $env;


    function __construct()
    {
        $this->env = parse_ini_file('.env');
    }
    /**
     * Global function to execute queries
     *
     * @param string $sqlString
     *   string representing sql query.
     *
     * @return array
     *   the result of query.
     */
    public function executeGetQuery($sqlString)
    {
        $data = array();
        $errmsg = "";
        $code = 0;

        try {
            if ($result = $this->connect()->query($sqlString)->fetchAll()) {
                foreach ($result as $record) {
                    array_push($data, $record);
                }
                $code = 200;
                $result = null;
                return array("code" => $code, "data" => $data);
            } else {
                $errmsg = "No data found";
                $code = 404;
            }
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 403;
        }
        return array("code" => $code, "errmsg" => $errmsg, "data" => $data);
    }

    public function executePostQuery($stmt)
    {
        $errmsg = "";
        $code = 0;

        try {
            if ($stmt->execute()) {
                $code = 200;
                return array("code" => $code, "msg" => 'Successful Query.');
            } else {
                $errmsg = "No data found";
                $code = 404;
            }
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 403;
        }
        return array("code" => $code, "errmsg" => $errmsg);
    }

    public function saveImage($dir, $tableName, $name) //A reusable function for saving images based on provided directory location
    {
        //Declare temporary holders for parameter and value for sql
        $tempFile = '';
        $fileName = '';

        //Iterates through the file uploaded (image)
        //Assigngs the parameter and value (filename)
        $tempFile = $_FILES[$name]['tmp_name'];
        $fileName = $_FILES[$name]['name'];

        //Fetch last autoincrement id on commex
        $lastIncrementID = $this->getLastID($tableName);

        // $picID = isset($id) ? $id : $lastIncrementID;

        //Declares folder location
        $fileFolder = __DIR__ . $dir . "$lastIncrementID/";

        //Creates directory if it doesn't exist yet
        if (!file_exists($fileFolder)) {
            mkdir($fileFolder, 0777);
        }

        //Declares location for image file itself.
        $filepath = __DIR__ . $dir . "$lastIncrementID/$fileName";

        //If file exists in path, delete it.
        if (file_exists($filepath)) {
            unlink($filepath);
        }

        //Add file to give nfilepath
        if (!move_uploaded_file($tempFile, $filepath)) {
            return array("code" => 404, "errmsg" => "Upload unsuccessful");
        }

        return $filepath = str_replace("C:\\xampp\\htdocs", "", $filepath);
    }

    public function verifyToken()
    {
        //Check existence of token
        if (!preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            header('HTTP/1.0 403 Forbidden');
            echo 'Token not found in request';
            exit;
        }

        //Check header
        $jwt = $matches[1];
        if (!$jwt) {
            header('HTTP/1.0 403 Forbidden');
            echo 'Token is missing but header exists';
            exit;
        }

        // return $matches;
        //Separate token to 3 parts
        $jwtArr = explode('.', $jwt);

        // return $jwtArr;
        $headers = new stdClass();
        $env = parse_ini_file('.env');
        $secretKey = $this->env["GCFAMS_API_KEY"];

        //Decode received token
        $payload = JWT::decode($jwt, new Key($secretKey, 'HS512'), $headers);

        // return $payload;
        // Decode payload part
        $parsedPayload = json_decode(json_encode($payload), true);

        //Re-encode decoded payload with the stored signature key to check for tampers
        $toCheckSignature = JWT::encode($parsedPayload, $secretKey, 'HS512');
        $toCheckSignature = explode('.', $toCheckSignature);

        //If re-encoded token is equal to received token, validate token.
        if ($toCheckSignature[2] == $jwtArr[2]) {
            return [
                "code" => 200,
                "payload" =>
                array(
                    "id" => $payload->id,
                    "college" => $payload->college
                )
            ];
        } else {
            header('HTTP/1.0 403 Forbidden');
            echo 'Currently encoded payload does not matched initially signed payload';
            exit;
        }
    }
    // public function verifyToken()
    // {

    //     return $_SERVER['HTTP_AUTHORIZATION'];
    //     // //Check existence of token
    //     // if (!preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
    //     //     header('HTTP/1.0 403 Forbidden');
    //     //     echo 'Token not found in request';
    //     //     exit;
    //     // }

    //     // //Check header
    //     // $jwt = $matches[1];
    //     // if (!$jwt) {
    //     //     header('HTTP/1.0 403 Forbidden');
    //     //     echo 'Token is missing but header exists';
    //     //     exit;
    //     // }
    //     // //Separate token to 3 parts
    //     // $jwtArr = explode('.', $jwt);

    //     // return $jwtArr;
    //     // $headers = new stdClass();
    //     // // $env = parse_ini_file('.env');
    //     // $secretKey = $this->env["GCFAMS_API_KEY"];

    //     // //Decode received token
    //     // $payload = JWT::decode($jwt, new Key($secretKey, 'HS512'), $headers);

    //     // //Decode payload part
    //     // $parsedPayload = json_decode(json_encode($payload), true);

    //     // //Re-encode decoded payload with the stored signature key to check for tampers
    //     // $toCheckSignature = JWT::encode($parsedPayload, $secretKey, 'HS512');
    //     // $toCheckSignature = explode('.', $toCheckSignature);

    //     // //If re-encoded token is equal to received token, validate token.
    //     // if ($toCheckSignature[2] == $jwtArr[2]) {
    //     //     return array(
    //     //         "code" => 200,
    //     //         "payload" => 
    //     //         array(
    //     //             "id" => $payload->id,
    //     //             "college" => $payload->college
    //     //         )
    //     //     );
    //     // } else {
    //     //     header('HTTP/1.0 403 Forbidden');
    //     //     echo 'Currently encoded payload does not matched initially signed payload';
    //     //     exit;
    //     // }
    // }

    public function prepareAddBind($table, $params, $form)
    {
        $sql = "INSERT INTO `$table`(";
        $tempParam = "(";
        $tempValue = "";

        foreach ($params as $key => $col) {
            //Insertion columns details
            sizeof($params) - 1 != $key ? $sql = $sql . $col . ', ' : $sql = $sql . $col . ')';
            //Question marks
            sizeof($params) - 1 != $key ? $tempParam = $tempParam . '?' . ', ' : $tempParam = $tempParam . '?' . ')';
        }

        $sql = $sql . " VALUES " . $tempParam;
        $stmt = $this->connect()->prepare($sql);

        foreach ($form as $key => $value) {
            $stmt->bindParam(($key + 1), $form[$key]);
        }

        return $this->executePostQuery($stmt);
        // return $sql;
    }

    public function prepareMultipleAddBind($table, $cols, $values)
    {

        $sql = "INSERT INTO `$table` (";

        foreach ($cols as $key => $col) {
            sizeof($cols) - 1 != $key ? $sql = $sql . $col . ', ' : $sql = $sql . $col . ')';
        }

        $sql .= " VALUES (";

        foreach ($values as $valuesLen => $value) {
            foreach ($value as $key => $val) {

                sizeof($values) - 1 != $valuesLen ? (sizeof($value) - 1 != $key ? $sql = $sql . $val . ', ' : $sql = $sql . $val . '), (') : $sql = $sql . $val . ',';
            }
        }

        $sql = $this->str_replace_last(',', ')', $sql);

        $stmt = $this->connect()->prepare($sql);

        return $this->executePostQuery($stmt);
        // return $sql;
    }
    public function prepareEditBind($table, $params, $form, $rowId)
    {
        // UPDATE `educattainment`
        // SET `faculty_ID` = 3, `educ_title` = 'My nutsacks', `educ_school` = 'Nutsack School', `year_start` = '2022', `year_end` = '2023', `educ_details` = 'very nuts, much sacks'
        // WHERE `educattainment_ID` = 26;


        $sql = "UPDATE `$table`
                SET ";

        foreach ($params as $key => $col) {
            //Insertion columns details
            sizeof($params) - 1 != $key ? $sql = $sql . "`$col` = ?, " : $sql = $sql . "`$col` = ? ";
        }
        $sql = $sql . "WHERE `$rowId` = ?";
        $stmt = $this->connect()->prepare($sql);
        foreach ($form as $key => $value) {
            $stmt->bindParam(($key + 1), $form[$key]);
        }

        return $this->executePostQuery($stmt);
    }

    public function prepareDeleteBind($table, $col, $id)
    {
        $sql = "DELETE FROM `$table` WHERE `$col` = ?";

        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(1, $id);

        return $this->executePostQuery($stmt);
    }

    // Tinatamad akong irefactor gawa nalang ako bago :D
    public function prepareDeleteBind2($table, $cols, $ids)
    {
        $sql = "DELETE FROM `$table` WHERE ";

        foreach ($cols as $key => $col) {
            sizeof($cols) - 1 != $key ? $sql = $sql . "`$col` = ? AND " : $sql = $sql . "`$col` = ? ";
        }

        $stmt = $this->connect()->prepare($sql);

        foreach ($ids as $key => $value) {
            $stmt->bindParam(($key + 1), $ids[$key]);
        }

        return $this->executePostQuery($stmt);
        // return $sql;
    }

    public function getLastID($table)
    {

        $DBName = $this->env["DB_NAME"];
        $sql = "SELECT AUTO_INCREMENT 
                FROM information_schema.TABLES 
                WHERE TABLE_SCHEMA = '$DBName' AND TABLE_NAME = '$table'";

        return $this->executeGetQuery($sql)['data'][0]['AUTO_INCREMENT'];
    }

    public function getParams($data)
    {
        $params = [];

        foreach ($data as $key => $value) {
            array_push($params, $key);
        }

        return $params;
    }
    public function getValues($data)
    {
        $values = [];

        foreach ($data as $key => $value) {
            array_push($values, $value);
        }

        return $values;
    }

    function str_replace_last($search, $replace, $str)
    {
        if (($pos = strrpos($str, $search)) !== false) {
            $search_length  = strlen($search);
            $str    = substr_replace($str, $replace, $pos, $search_length);
        }
        return $str;
    }

    function arrayIncludes($mainArray, $targetArray)
    {

        foreach ($mainArray as $array) {
            // Check if the current array matches the target array
            if ($this->checkContents($array, $targetArray)) {
                return true;
            }
        }

        return false;
    }

    function checkContents($array1, $array2)
    {
        // Encode arrays to JSON strings for comparison
        $json1 = json_encode($array1, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $json2 = json_encode($array2, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        // Compare JSON strings
        return $json1 === $json2;
    }
}
