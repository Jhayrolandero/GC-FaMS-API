<?php
    header('Access-Control-Allow-Origin: http://localhost:4200');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header("Access-Control-Allow-Headers: *");

    include_once "./Controller/global.php";

    class Login extends GlobalMethods{
        function base64_url_encode($text):String{
            return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($text));
        }

        function generateToken($faculty_ID, $isAdmin){
            $secret = "jetculverin";
            $header = [
                "alg" => "HS512",
                "typ" => "JWT"
            ];
            $header = $this->base64_url_encode(json_encode($header));

            $payload = [
                "id" => $faculty_ID,
                'isAdmin' => $isAdmin
            ];
            $payload = $this->base64_url_encode(json_encode($payload));

            $signature = $this->base64_url_encode(hash_hmac('sha512', "$header.$payload", $secret, true));
            $jwt = "$header.$payload.$signature";

            return array(
                "token" => $jwt,
                "code" => 200
            );
        }
        public function validateLogin($form){
            $sql = "SELECT * FROM `facultymembers` WHERE `email` = '$form->email'";
            $result = $this->executeQuery($sql);

            if($result['code'] == 200){
                $passValid = password_verify($form->password, $result['data'][0]['password']);
                if($passValid){
                    return $this->generateToken($result['data'][0]['faculty_ID'], false);
                }
                else{
                    return array("token" => "", "code" => 403);
                }
            }
            else{
                    return array("token" => "", "code" => 403);
            }
        }
    }
?>