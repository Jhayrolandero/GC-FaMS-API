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

class GlobalMethods extends Connection{
    /**
     * Global function to execute queries
     *
     * @param string $sqlString
     *   string representing sql query.
     *
     * @return array
     *   the result of query.
     */
    public function executeGetQuery($sqlString){
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
        return array("code" => $code, "errmsg" => $errmsg);
    }

    public function executePostQuery($stmt){
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

    public function verifyToken(){
        if (!preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            header('HTTP/1.0 403 Forbidden');
            echo 'Token not found in request';
            exit;
        }

        $jwt = $matches[1];
        if (!$jwt) {
            header('HTTP/1.0 403 Forbidden');
            echo 'Token is missing but header exists';
            exit;
        }
        $jwtArr = explode('.', $jwt);

        $headers = new stdClass();
        $secretKey = 'jetculverin';

        $payload = JWT::decode($jwt, new Key($secretKey, 'HS512'), $headers);

        //ETO YUNG MISMONG JSON FORMATTED NA PAYLOAD
        $parsedPayload = json_decode(json_encode($payload), true);

        $toCheckSignature = JWT::encode($parsedPayload, $secretKey, 'HS512');
        $toCheckSignature = explode('.', $toCheckSignature);

        if ($toCheckSignature[2] == $jwtArr[2]) {
            return array(
                "code" => 200,
                "payload" => $payload->id
            );
        } else {
            header('HTTP/1.0 403 Forbidden');
            echo 'Currently encoded payload does not matched initially signed payload';
            exit;
        }
    }

    public function prepareAddBind($table, $params, $form){
        $sql = "INSERT INTO `$table`(";
        $tempParam = "(";
        $tempValue = "";

        foreach($params as $key=>$col){
            //Insertion columns details
            sizeof($params) - 1 != $key ? $sql = $sql . $col . ', ' : $sql = $sql . $col . ')';
            //Question marks
            sizeof($params) - 1 != $key ? $tempParam = $tempParam . '?' . ', ' : $tempParam = $tempParam . '?' . ')';
        }

        $sql = $sql . " VALUES " . $tempParam;
        $stmt = $this->connect()->prepare($sql);

        foreach($form as $key=>$value){
            $stmt->bindParam(($key+1), $form[$key]);
        }

        return $this->executePostQuery($stmt);
    }

    public function prepareEditBind($table, $params, $form, $rowId){
        // UPDATE `educattainment`
        // SET `faculty_ID` = 3, `educ_title` = 'My nutsacks', `educ_school` = 'Nutsack School', `year_start` = '2022', `year_end` = '2023', `educ_details` = 'very nuts, much sacks'
        // WHERE `educattainment_ID` = 26;


        $sql = "UPDATE `$table`
                SET ";

        foreach($params as $key=>$col){
            //Insertion columns details
            sizeof($params) - 1 != $key ? $sql = $sql . "`$col` = ?, " : $sql = $sql . "`$col` = ? ";
        }
        $sql = $sql . "WHERE `$rowId` = ?";
        $stmt = $this->connect()->prepare($sql);
        foreach($form as $key=>$value){
            $stmt->bindParam(($key+1), $form[$key]);
        }

        return $this->executePostQuery($stmt);
    }

    public function prepareDeleteBind($table, $col, $id){
        $sql = "DELETE FROM `$table` WHERE `$col` = ?";

        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(1, $id);

        return $this->executePostQuery($stmt);
    }
}
