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
    //UNUSED FUNCTIONS (MAY BE REPURPOSED LATER)
    //UNUSED FUNCTIONS (MAY BE REPURPOSED LATER)
    //UNUSED FUNCTIONS (MAY BE REPURPOSED LATER)
    // public function sendPayLoad($data, $remarks, $message, $code){
    //     $status = array("remarks"=>$remarks, "message"=> $message);
    //     http_response_code($code);
    //     return array(
    //         "status"=>$status,
    //         "data"=>$data,
    //         "prepared_by"=>"Chris Kirk Patrick V. Viacrusis",
    //         "timestamp"=>date_create()
    //     );
    // }
    //UNUSED FUNCTIONS (MAY BE REPURPOSED LATER)
    //UNUSED FUNCTIONS (MAY BE REPURPOSED LATER)
    //UNUSED FUNCTIONS (MAY BE REPURPOSED LATER)

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
}
