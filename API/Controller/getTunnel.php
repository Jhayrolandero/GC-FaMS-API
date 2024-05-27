<?php
// header('Access-Control-Allow-Origin: http://localhost:4200');
// header('Access-Control-Allow-Origin: http://localhost:4200');
// header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
// header("Access-Control-Allow-Headers: *");

//Fetches every single file in Model
foreach (glob("./Model/*/*.php") as $filename) {
    include_once $filename;
}


class GetTunnel extends Connection
{
    private $faculty;
    private $schedule;
    private $commex;
    private $college;
    private $resume;
    private $evaluation;

    public function __construct()
    {
        $this->faculty = new Faculty();
        $this->schedule = new Schedule();
        $this->commex = new Commex();
        $this->college = new College();
        $this->resume = new ResumeInfo();
        $this->evaluation = new Evaluation();
    }

    /**
     * Encrypt value to a cryptojs compatiable json encoding string
     *
     * @param mixed $passphrase
     * @param mixed $value
     * @return string
     */
    // function test($passphrase = "ucj7XoyBfAMt/ZMF20SQ7sEzad+bKf4bha7bFBdl2HY=", $value = "Hello World")
    // {
    //     $salt = openssl_random_pseudo_bytes(8);
    //     $salted = '';
    //     $dx = '';
    //     while (strlen($salted) < 48) {
    //         $dx = md5($dx . $passphrase . $salt, true);
    //         $salted .= $dx;
    //     }
    //     $key = substr($salted, 0, 32);
    //     $iv  = substr($salted, 32, 16);
    //     $encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iv);
    //     $data = array("ct" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "s" => bin2hex($salt));
    //     return json_encode($data);
    // }

    function test()

    {


        $env = parse_ini_file('.env');

        $first_key = $env["FIRSTKEY"];
        $plain_text = "Hello World";

        $salt = openssl_random_pseudo_bytes(256);
        $iv = openssl_random_pseudo_bytes(16);
        //on PHP7 can use random_bytes() istead openssl_random_pseudo_bytes()
        //or PHP5x see : https://github.com/paragonie/random_compat

        $iterations = 999;
        $key = hash_pbkdf2("sha512", $first_key, $salt, $iterations, 64);

        $encrypted_data = openssl_encrypt($plain_text, 'aes-256-cbc', hex2bin($key), OPENSSL_RAW_DATA, $iv);

        $data = array("ciphertext" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "salt" => bin2hex($salt));
        return json_encode($data);
    }
    // function test()

    // {

    //     $env = parse_ini_file('.env');

    //     $first_key = $env["FIRSTKEY"];

    //     $method = "aes-256-cbc";
    //     $iv_length = openssl_cipher_iv_length($method);
    //     // $iv = "h7oehNIHWGNIHxyN";
    //     $iv = openssl_random_pseudo_bytes($iv_length);


    //     // return $iv_length;
    //     // return openssl_random_pseudo_bytes($iv_length);
    //     $string = "hello world";
    //     $first_encrypted = openssl_encrypt($string, $method, $first_key, $options = 0, $iv);

    //     $output = openssl_decrypt($first_encrypted, 'AES-256-CBC', $first_key, $options = 0, $iv);

    //     return base64_encode($first_encrypted . "::" . $iv);
    // }
    public function getFaculty($id)
    {
        return $this->faculty->getFacultyInfo($id);
    }

    public function getSchedule($id, $query)
    {
        switch ($query) {
            case 'college':
                return $this->schedule->getSchedule($id, $query);
            case 'faculty':
                return $this->schedule->getSchedule($id, $query);
            case 'all':
        }
    }

    public function getCommex($id = null, $query)
    {
        switch ($query) {
            case 'college':
                return $this->commex->getCommex($id, $query);
            case 'faculty':
                return $this->commex->getCommex($id, $query);
            case 'all':
                return $this->commex->getCommexAll();
        }
    }

    public function getCollege($id)
    {
        return $this->college->getCollege($id);
    }

    // public function getResumeInfo($id)
    // {
    //     return $this->resume->getResumeInfo($id);
    // }

    public function getCert($id, $type)
    {
        return $type == 0 ?
            $this->resume->getCert($id) :
            $this->resume->getCollegeCert($id);
    }
    public function getExp($id, $type)
    {
        return $type == 0 ?
            $this->resume->getExp($id) :
            $this->resume->getCollegeExp($id);
    }
    public function getEduc($id, $type)
    {
        return $type == 0 ?
            $this->resume->getEduc($id) :
            $this->resume->getCollegeEduc($id);
    }

    public function getProj($id, $type)
    {
        return $type == 0 ?
            $this->resume->getProj($id) :
            $this->resume->getCollegeProj($id);
    }

    public function getSpec($id, $type)
    {
        return $type == 0 ?
            $this->resume->getSpec($id) :
            $this->resume->getCollegeSpec($id);
    }

    public function getEvaluation($id, $type)
    {
        return $type == 0 ?
            $this->evaluation->getEvaluation($id) :
            $this->evaluation->getCollegeEvaluation($id);
    }

    public function getFaculties($college_ID)
    {
        return $this->faculty->getAllFaculty($college_ID);
    }

    public function getAttendee($id, $query = null, $faculty_ID = null)
    {
        return $this->commex->getAttendee($id, $query, $faculty_ID);
    }
}
