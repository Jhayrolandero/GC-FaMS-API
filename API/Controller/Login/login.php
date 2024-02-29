<?php
    header('Access-Control-Allow-Origin: http://localhost:4200');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header("Access-Control-Allow-Headers: *");
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;
    use Firebase\JWT\ExpiredException;
    use Firebase\JWT\SignatureInvalidException;
    use Firebase\JWT\BeforeValidException;
    require_once('../vendor/autoload.php');
    include_once "./Controller/global.php";

    class Login extends GlobalMethods{
        function base64_url_encode($text):String{
            return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($text));
        }

        function generateToken($faculty_ID, $isAdmin){

            $token = array(
                "id" => $faculty_ID,
                "isAdmin" => $isAdmin
            );
            return array(
                "token" => JWT::encode($token, 'jetculverin', 'HS512'),
                "privilege" => $isAdmin,
                "code" => 200
            );
        }
        public function validateLogin($form){
            $isAdmin = $form->privExpected == 1 ? 0 : 1;
            $sql = "SELECT * FROM `facultymembers` WHERE `email` = '$form->email' AND `isAdmin` = $isAdmin";
            $result = $this->executeQuery($sql);
            if($result['code'] == 200){
                $passValid = password_verify($form->password, $result['data'][0]['password']);
                if($passValid){
                    return $this->generateToken($result['data'][0]['faculty_ID'], $result['data'][0]['isAdmin']);
                }
                else{
                    return array("token" => "", "code" => 403);
                }
            }
            else{
                return array("token" => "", "code" => 404);
            }
        }
    }
?>