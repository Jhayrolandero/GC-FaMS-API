<?php
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");         

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

foreach (glob("./Controller/*/*.php") as $filename) {
    include_once $filename;
}
include_once "./Model/database.php";
include_once "./Controller/global.php";

$con = new Connection();
$globalOb = new GlobalMethods();
$pdo = $con->connect();

$getSchedule = new Schedule($pdo);
$getFaculty = new Profile($pdo);
$getCommex = new Commex($pdo);
$login = new Login();


if(isset($_REQUEST['request'])){
    $request = explode('/', $_REQUEST['request']);
}
else{
    http_response_code(404);
}


switch($_SERVER['REQUEST_METHOD']){
    case 'GET':
        $userSessionID = $globalOb->verifyToken()['payload'];

        switch($request[0]){
            case 'getschedules':
                if($request[1] == "fetchFaculty"){
                    echo json_encode($getSchedule->getScheduleFaculty($globalOb->verifyToken()['payload']));
                }          
                break;

            case 'getprofile':
                if($request[1] == "fetchProfile"){
                    echo json_encode($getFaculty->getFacultyInfo($globalOb->verifyToken()['payload']));
                }
                break;
            
            case 'getcommex':
                if($request[1] == "fetchCommex"){
                    echo json_encode($getCommex->getCommexFaculty($globalOb->verifyToken()['payload']));
                }
                break;

            default:
                http_response_code(404);    
                break;
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        switch($request[0]){
            case 'login':
                echo json_encode($login->validateLogin($data));
        }    
        break;

    default:
        http_response_code(404);    
        break;
}

?>