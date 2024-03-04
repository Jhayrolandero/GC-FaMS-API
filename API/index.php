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

include_once "./Model/database.php";
include_once "./Controller/global.php";
include_once "./Controller/getTunnel.php";
include_once "./Controller/postTunnel.php";

$getTunnel = new GetTunnel();
$postTunnel = new PostTunnel();
$globalOb = new GlobalMethods();

if (isset($_REQUEST['request'])) {
    $request = explode('/', $_REQUEST['request']);
} else {
    http_response_code(404);
}


switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':

        switch ($request[0]) {
            case 'getschedules':
                if ($request[1] == "fetchFaculty") {
                    echo json_encode($getTunnel->toGetSchedule($globalOb->verifyToken()['payload']));
                }
                break;
            // case 'college':
            //     echo json_encode($getTunnel->getCollege());
            //     break;
            // case 'program':
            //     if (isset($request[1])) {
            //         echo json_encode($getTunnel->getProgram($request[1]));
            //     } else {
            //         echo json_encode($getTunnel->getProgram());
            //     }
            //     break;

            case 'getprofile':
                if ($request[1] == "fetchProfile") {
                    echo json_encode($getTunnel->toGetFaculty($globalOb->verifyToken()['payload']));
                }
                break;

            case 'getcommex':
                if ($request[1] == "fetchCommex") {
                    echo json_encode($getTunnel->toGetCommex($globalOb->verifyToken()['payload']));
                }
                break;

            default:
                http_response_code(404);
                break;
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        switch ($request[0]) {
            // case 'faculty':
            //     echo json_encode($faculty->addFaculty($data));
            //     break;

            case 'login':
                echo json_encode($postTunnel->toValidateLogin($data));
                break;

            default:
                http_response_code(403);
                break;
        }
        break;

    default:
        http_response_code(404);
        break;
}
