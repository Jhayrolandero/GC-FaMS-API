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
include_once "./Controller/Faculty/Faculty.php";
include_once "./Controller/College/CollegeController.php";

$con = new Connection();
// $globalOb = new GlobalMethods();
$pdo = $con->connect();

// $getSchedule = new Schedule($pdo);
// $login = new Login($pdo);
$faculty = new FacultyController($pdo);
$college = new CollegeController($pdo);
if (isset($_REQUEST['request'])) {
    $request = explode('/', $_REQUEST['request']);
} else {
    http_response_code(404);
}


switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // $userSessionID = $globalOb->verifyToken()['payload'];

        switch ($request[0]) {
            case 'getschedules':
                if ($request[1] == "fetchFaculty") {
                    echo json_encode($getSchedule->getScheduleFaculty($globalOb->verifyToken()['payload']));
                }
                break;
            case 'college':
                echo json_encode($college->getCollege());
                break;

            default:
                http_response_code(403);
                break;
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        switch ($request[0]) {
            case 'faculty':
                echo json_encode($faculty->addFaculty($data));

            default:
                http_response_code(403);
                break;
        }
        break;

    default:
        http_response_code(403);
        break;
}
