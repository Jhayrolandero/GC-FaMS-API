<?php

use function PHPSTORM_META\type;

// header('Access-Control-Allow-Origin: http://localhost:4200');
// header('Access-Control-Allow-Methods: GET, POST, PATCH, OPTIONS');
// header("Access-Control-Allow-Headers: *");

// Fetches every single file in Model
foreach (glob("./Model/*/*.php") as $filename) {
    include_once $filename;
}
// include_once "./Model/Login/login.php";

include_once __DIR__ . '/./global.php';
class PostTunnel extends GlobalMethods
{
    private $login;
    private $resume;

    private $faculty;
    private $commex;

    public function __construct()
    {
        $this->login = new Login();
        $this->resume = new ResumeInfo();
        $this->faculty = new Faculty();
        $this->commex = new Commex();
        $this->eval = new Evaluation();
        $this->course = new Schedule();
    }

    // public function addFaculty($data){
    //     return $this->faculty->addFaculty($data);
    // }

    public function toValidateLogin($form)
    {
        // return $form->email;
        return $this->login->validateLogin($this->secureDecrypt($form));
        // return $this->secureDecrypt($form);
        // return $this->login->validateLogin($form);
    }

    public function addFaculty($data)
    {
        return $this->faculty->addFaculty();
    }

    public function toAddEval($data, $id)
    {
        return $this->eval->addEval($data, $id);
    }

    public function toAddCourse($data, $id)
    {
        return $this->course->addCourse($data, $id);
    }

    public function toAddCommex()
    {

        return $this->commex->addCommex();
        // return $_POST;
    }



    public function toAddResume($form, $id, $type)
    {
        switch ($type) {
            case 1:
                return $this->resume->addEduc($form, $id);

            case 2:
                return $this->resume->addExp($form, $id);

            case 3:
                return $this->resume->addFacultyCert($form, $id);

            case 4:
                return $this->resume->addProj($form, $id);

            case 5:
                return $this->resume->addSpec($form, $id);

            case 6:
                return $this->resume->addNewCert($form, $id);

            case 7:
                return $this->resume->addNewSpec($form, $id);

            default:
                # code...
                break;
        }
    }

    public function toEditResume($form, $id, $type)
    {
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

    public function toDeleteResume($id, $type)
    {
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

    public function toDeleteFaculty($id)
    {
        return $this->faculty->deleteFaculty($id);
    }

    public function toEditFaculty($data, $id)
    {
        return $this->faculty->editFaculty($data, $id);
    }

    public function toEditProfile($id)
    {
        return $this->faculty->editProfile($id);
        // switch ($params) {
        //     case 'faculty':
        //         return $this->faculty->editFaculty(null, $id);
        // }
    }

    public function toEditCover($id)
    {
        return $this->faculty->editCover($id);
        // switch ($params) {
        //     case 'faculty':
        //         return $this->faculty->editFaculty(null, $id);
        // }
    }

    function test($id)
    {

        // $passphrase = "ucj7XoyBfAMt/ZMF20SQ7sEzad+bKf4bha7bFBdl2HY=";
        // try {
        //     $salt = hex2bin($data->salt);
        //     $iv  = hex2bin($data->iv);
        // } catch (Exception $e) {
        //     return "nigga";
        // }

        // $ciphertext = base64_decode($data->ciphertext);
        // $iterations = 999; //same as js encrypting 

        // $key = hash_pbkdf2("sha512", $passphrase, $salt, $iterations, 64);

        // $decrypted = openssl_decrypt($ciphertext, 'aes-256-cbc', hex2bin($key), OPENSSL_RAW_DATA, $iv);

        // return $_FILES;
        return $this->faculty->editProfile($id);
    }
    public function toAddAttendee($faculty_ID, $college_ID)
    {

        $formData = $_POST["attendees"][0];
        $data = json_decode($formData);
        return $this->commex->addAttendee($faculty_ID, $college_ID, $data->commex_ID);
    }

    public function toDeleteAttendee($commex_ID, $faculty_ID)
    {

        return $this->commex->deleteAttendee($commex_ID, $faculty_ID);
        // return $commex_ID;
    }

    public function toDeleteCommex($commex_ID)
    {
        return $this->commex->deleteCommex($commex_ID);
    }
}
