<?php
    header('Access-Control-Allow-Origin: http://localhost:4200');
    header('Access-Control-Allow-Methods: GET, POST, PATCH, OPTIONS');
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

                case 2:
                    return $this->resume->addExp($form, $id);
                
                case 3:
                    return $this->resume->addCert($form, $id);

                case 4:
                    return $this->resume->addProj($form, $id);

                case 5:
                    return $this->resume->addSpec($form, $id);
                
                default:
                    # code...
                    break;
            }
        }

        public function toEditResume($form, $id, $type){
            switch ($type) {
                case 1:
                    return $this->resume->editEduc($form, $id);

                case 2:
                    return $this->resume->editExp($form, $id);

                case 3:
                    return $this->resume->editCert($form, $id);

                case 4:
                    return $this->resume->editProj($form, $id);
                
                case 5:
                    return $this->resume->editSpec($form, $id);

                default:
                    # code...
                    break;
            }
        }

        public function toDeleteResume($id, $type){
            switch ($type) {
                case 1:
                    return $this->resume->deleteEduc($id);

                case 2:
                    return $this->resume->deleteExp($id);

                case 3:
                    return $this->resume->deleteCert($id);
                
                case 4:
                    return $this->resume->deleteProj($id);

                case 5:
                    return $this->resume->deleteSpec($id);
                    
                default:
                    # code...
                    break;
            }
        }
    }

?>