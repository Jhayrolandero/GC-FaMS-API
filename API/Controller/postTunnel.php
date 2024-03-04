<?php
    header('Access-Control-Allow-Origin: http://localhost:4200');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header("Access-Control-Allow-Headers: *");

    //Fetches every single file in Model
    // foreach (glob("./Model/*/*.php") as $filename) {
    //     include_once $filename;
    // }
    include_once "./Model/Login/login.php";
    
    class PostTunnel{
        private $login;

        public function __construct(){
            $this->login = new Login();
        }

        // public function addFaculty($data){
        //     return $this->faculty->addFaculty($data);
        // }

        public function toValidateLogin($form){
            return $this->login->validateLogin($form);
        }
    }

?>