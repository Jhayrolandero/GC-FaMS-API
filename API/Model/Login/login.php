<?php
// header('Access-Control-Allow-Origin: http://localhost:4200');
// header('Access-Control-Allow-Methods: POST, OPTIONS');
// header("Access-Control-Allow-Headers: *");
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;

require_once('../vendor/autoload.php');
include_once "./Controller/global.php";

class Login extends GlobalMethods
{
    function generateToken($faculty_ID, $isAdmin, $college_ID, $teaching_position)
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + (10 * 60);

        $env = parse_ini_file('.env');
        $secretKey = $env["GCFAMS_API_KEY"];
        $token = array(
            "iat" => $issuedAt,
            "exp" => $expirationTime,
            "id" => $faculty_ID,
            "college" => $college_ID,
            "isAdmin" => $isAdmin,
            "privilege" => $teaching_position
        );
        return array(
            "token" => JWT::encode($token, $secretKey, 'HS512'),
            "privilege" => $isAdmin,
            "code" => 200
        );
    }

    public function validateLogin($form)
    {

        $sql = "SELECT * FROM `facultymembers` WHERE `email` = '$form->email'";
        $result = $this->executeGetQuery($sql);

        if ($result['code'] == 200) {
            $passValid = password_verify($form->password, $result['data'][0]['password']);
            if ($passValid) {
                return $this->generateToken(
                    $result['data'][0]['faculty_ID'],
                    $result['data'][0]['isAdmin'],
                    $result['data'][0]['college_ID'],
                    $result['data'][0]['teaching_position'],
                );
            } else {
                return array("token" => "", "code" => 403);
            }
        } else {
            return $this->executeGetQuery($sql);
        }
    }
}
