<?php

use function PHPSTORM_META\type;

header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Methods: GET, POST, PATCH, OPTIONS');
header("Access-Control-Allow-Headers: *");

// Fetches every single file in Model
foreach (glob("./Model/*/*.php") as $filename) {
    include_once $filename;
}
// include_once "./Model/Login/login.php";

class PostTunnel
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
    }

    // public function addFaculty($data){
    //     return $this->faculty->addFaculty($data);
    // }

    public function toValidateLogin($form)
    {
        return $this->login->validateLogin($form);
    }

    public function addFaculty($data)
    {
        return $this->faculty->addFaculty();
        // // return $_FILES;
        // if (!empty($_FILES)) {
        //     $tempFile = '';
        //     $fileName = '';

        //     foreach ($_FILES as $key => $file) {
        //         $tempFile = $file['tmp_name'];
        //         $fileName = $file['name'];
        //     }
        //     $lastIncrementID = $this->faculty->fetchLastID();
        //     $fileFolder = __DIR__ . "/../../Image_Assets/Faculty_Profile/$lastIncrementID/";

        //     if (!file_exists($fileFolder)) {
        //         mkdir($fileFolder, 0777);
        //     }

        //     $filepath = __DIR__ . "/../../Image_Assets/Faculty_Profile/$lastIncrementID/$fileName";

        //     if (file_exists($filepath)) {
        //         unlink($filepath);
        //     }

        //     if (!move_uploaded_file($tempFile, $filepath)) {
        //         return array("code" => 404, "errmsg" => "Upload unsuccessful");
        //     }

        //     $filepath = str_replace("C:\\xampp\\htdocs", "", $filepath);
        //     return $this->faculty->addFaculty($filepath);
        //     // return 'withimage';
        // } else {

        //     return $this->faculty->addFaculty();
        // }
        // // return 'withoutimage';
    }

    public function toAddEval($data, $id)
    {
        return $this->eval->addEval($data, $id);
    }

    public function toAddCommex($data)
    {

        return $this->commex->addCommex($data);
    }



    public function toAddResume($form, $id, $type)
    {
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

    public function toEditProfile($params, $id)
    {
        switch ($params) {
            case 'faculty':
                return $this->faculty->editFaculty(null, $id);
        }
    }

    public function toEditCover($params, $id)
    {
        switch ($params) {
            case 'faculty':
                return $this->faculty->editFaculty(null, $id);
        }
    }

    function test()
    {
        $put = array();
        parse_str(file_get_contents('php://input'), $put);

        return $put;
    }

    // [ { "commex_id": 1, "faculty_id": 1} ]
    public function toAddAttendee()
    {
        $datas = $_POST["id"];

        $faculty_ID = [];
        $college_ID = [];



        foreach ($datas as $data) {
            $data = json_decode($data);
            array_push($faculty_ID, $data->faculty_ID);
            array_push($college_ID, $data->college_ID);
            // print_r($data);
        }

        foreach ($college_ID as $id) {
            array_push($college_ID, 1);
        }

        // $faculty_status = $this->commex->addAttendee($faculty_ID);
        // $college_status = $this->commex->addAttendee($faculty_ID);
        // return gettype($data);
        // $data = explode(",", $data);
        // return $data;
        // return $this->commex->addAttendee($data);
        // $data = json_decode($data[0]);
        // $keys = array_keys((array)$data);

        return $college_ID;

        // return gettype($data[0]);
        // if (is_object($data[0])) {
        //     return "Object";
        // } else {
        //     return "Not";
        // }
        // }
        // COnvert to PHP Object
        // $item = json_decode($data[0]);
        // foreach ($data as $item) {
        //     // Access commex_id and faculty_id directly from $item array
        //     echo "Commex ID: " . $item["commex_ID"] . ", Faculty ID: " . $item["faculty_ID"] . "<br>";
        // }
        // // return $this->commex->addAttendee();
        // return $this->commex->addAttendee($data);
        // return $item->college_ID;
        // return array_keys((array)$item);
    }
}

/**
 *  I want to show you how i wasted 3 hours of productive work
 *  seems that PHP can't handle multiform PATCH request through formdata efficiently
 *  i don't wanna touch regex
 *  none of these works BTW
 */

//  public function toEditFaculty($data, $id)
//     {

//         return $data;
//         // parse_str(file_get_contents('php://input'), $_PROFILE);
//         // parse_str(file_get_contents('php://input'), $_PATCH);
//         // return $_PATCH;
//         // return $_FILES('profile');
//         // return $_POST;
//         // return $_PATCH;
//         // preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);

//         // // return $_POST['first_name'];
//         // $result = [];
//         // $rawPost = file_get_contents('php://input');
//         // mb_parse_str($rawPost, $result);
//         // array_push($matches, $result);
//         // return $matches;
//         // $_PATCH = [];
//         // parse_str(file_get_contents('php://input'), $_PATCH);
//         // $this->parse_raw_http_request($_PATCH);
//         // return $_PATCH;
//         // // return $data;
//         // // $formData = $this->parseFormData($data);
//         // // return $formData;
//     }
       
        // function parse_multipart_content(?string $content, ?string $boundary): ?array
        // {
        //     if (empty($content) || empty($boundary)) return null;
        //     $sections = array_map("trim", explode("--$boundary", $content));
        //     $parts = [];
        //     foreach ($sections as $section) {
        //         if ($section === "" || $section === "--") continue;
        //         $fields = explode("\r\n\r\n", $section);
        //         if (preg_match_all("/([a-z0-9-_]+)\s*:\s*([^\r\n]+)/iu", $fields[0] ?? "", $matches, PREG_SET_ORDER) === 2) {
        //             $headers = [];
        //             foreach ($matches as $match) $headers[$match[1]] = $match[2];
        //         } else $headers = null;
        //         $parts[] = ["headers" => $headers, "value"   => $fields[1] ?? null];
        //     }
        //     return empty($parts) ? null : $parts;
        // }
    
        // function parse_raw_http_request(array &$a_data)
        // {
        //     // read incoming data
        //     $input = file_get_contents('php://input');
    
        //     // grab multipart boundary from content type header
        //     preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);
        //     $boundary = $matches[1];
    
        //     // split content by boundary and get rid of last -- element
        //     $a_blocks = preg_split("/-+$boundary/", $input);
        //     array_pop($a_blocks);
    
        //     // loop data blocks
        //     foreach ($a_blocks as $id => $block) {
        //         if (empty($block))
        //             continue;
    
        //         // you'll have to var_dump $block to understand this and maybe replace \n or \r with a visibile char
    
        //         // parse uploaded files
        //         if (strpos($block, 'application/octet-stream') !== FALSE) {
        //             // match "name", then everything after "stream" (optional) except for prepending newlines 
        //             preg_match('/name=\"([^\"]*)\".*stream[\n|\r]+([^\n\r].*)?$/s', $block, $matches);
        //         }
        //         // parse all other fields
        //         else {
        //             // match "name" and optional value in between newline sequences
        //             preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);
        //         }
        //         $a_data[$matches[1]] = $matches[2];
        //     }
        // }
    
    
        // function parseFormData(string $rawRequestBody): array
        // {
        //     $formData = [];
        //     $boundaries = mb_split('(\r\n\r\n)', $rawRequestBody);
    
        //     foreach ($boundaries as $boundary) {
        //         if (strpos($boundary, 'Content-Disposition: form-data;') !== false) {
        //             $headerParts = mb_split('(\r\n\r\n)', $boundary, 2);
        //             $header = $headerParts[0];
        //             $fieldName = mb_substr(
        //                 $header,
        //                 strpos($header, 'name="') + 6,
        //                 strpos($header, '"', strpos($header, 'name="') + 6) - (strpos($header, 'name="') + 6)
        //             );
        //             $fieldValue = trim($headerParts[1]);
        //             $formData[$fieldName] = $fieldValue;
        //         }
        //     }
    
        //     return $formData;
        // }
        // // function parseFormData(string $rawRequestBody)
        // // {
        // //     $formData = [];
        // //     $boundaries = str_getcsv($rawRequestBody, "\r\n\r\n");
    
        // //     return $boundaries;
        // //     // foreach ($boundaries as $boundary) {
        // //     //     if (strpos($boundary, 'Content-Disposition: form-data;') !== false) {
        // //     //         // $fieldName = $this->extractFieldName($boundary);
        // //     //         // $fieldValue = $this->extractFieldValue($boundary);
        // //     //         // $formData[$fieldName] = $fieldValue;
        // //     //     }
        // //     // }
    
        // //     // return $formData;
        // // }
    
       
        // function extractFieldName(string $boundary): string
        // {
        //     $pattern = '/name="([^"]+)"/';
        //     preg_match($pattern, $boundary, $matches);
        //     return $matches[1] ?? '';
        // }
    
    
       
        // function extractFieldValue(string $boundary): string
        // {
        //     $parts = explode("\r\n\r\n", $boundary, 2);
        //     $fieldValue = trim($parts[1] ?? '');
        //     return str_replace("\r\n", '', $fieldValue);
        // }