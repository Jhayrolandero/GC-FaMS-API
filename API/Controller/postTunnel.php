<?php
    header('Access-Control-Allow-Origin: http://localhost:4200');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header("Access-Control-Allow-Headers: *");

    // Fetches every single file in Model
    foreach (glob("./Model/*/*.php") as $filename) {
        include_once $filename;
    }
    // include_once "./Model/Login/login.php";
    
    class PostTunnel{
        private $login;
        private $resume;

        public function __construct(){
            $this->login = new Login();
            $this->resume = new ResumeInfo();
        }

        // public function addFaculty($data){
        //     return $this->faculty->addFaculty($data);
        // }

        public function toValidateLogin($form){
            return $this->login->validateLogin($form);
        }

        public function toAddResume($form, $id, $type){
            switch ($type) {
                case 1:
                    return $this->resume->addEduc($form, $id);
                
                default:
                    # code...
                    break;
            }
        }

        public function toDeleteResume($id, $type){
            switch ($type) {
                case 1:
                    return $this->resume->deleteEduc($id);
                
                default:
                    # code...
                    break;
            }
        }
    }

?>