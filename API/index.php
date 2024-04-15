<?php
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Credentials: true');


if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");

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

//Converts request link to array
if (isset($_REQUEST['request'])) {
    $request = explode('/', $_REQUEST['request']);
} else {
    http_response_code(404);
}

//Login filter
if ($request[0] === 'login') {
    echo json_encode($postTunnel->toValidateLogin(json_decode(file_get_contents("php://input"))));
    exit;
}


//Main request switch endpoints
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $id = $globalOb->verifyToken()['payload'];
        // $id = 3;
        switch ($request[0]) {
            case 'getschedules':
                if ($request[1] == "fetchFaculty") {
                    echo json_encode($getTunnel->toGetSchedule($id));
                }
                break;
            case 'fetchCollege':
                echo json_encode($getTunnel->toGetCollege($id));
                break;

            case 'getprofile':
                if ($request[1] == "fetchProfile") {
                    echo json_encode($getTunnel->toGetFaculty($id));
                }
                break;

            case 'getcommex':
                $query = $_GET['t'];

                if (empty($query)) {
                    http_response_code(404);
                    return;
                }
                // echo json_encode($query);
                switch ($query) {
                    case 'college':
                        echo json_encode($getTunnel->toGetCommex($request[1], $query));
                        break;
                    case 'faculty':
                        echo json_encode($getTunnel->toGetCommex($id, $query));
                        break;
                    default:
                        http_response_code(404);
                        break;
                }
                break;

            case 'getresume':
                if ($request[1] == "fetchResume") {
                    echo json_encode($getTunnel->toGetResumeInfo($id));
                }
                break;

            case 'certificate':
                echo json_encode($getTunnel->getCert($globalOb->verifyToken()['payload']));
                break;

            case 'experience':
                echo json_encode($getTunnel->getExp($globalOb->verifyToken()['payload']));
                break;

            case 'education':
                echo json_encode($getTunnel->getEduc($globalOb->verifyToken()['payload']));
                break;

            case 'project':
                echo json_encode($getTunnel->getProj($globalOb->verifyToken()['payload']));
                break;

            case 'expertise':
                echo json_encode($getTunnel->getSpec($globalOb->verifyToken()['payload']));
                break;

            case 'getevaluation':
                if ($request[1] == "fetchEvaluation") {
                    echo json_encode($getTunnel->toGetEvaluation($id));
                }
                break;

            case 'faculty':
                echo json_encode($getTunnel->getFaculties());
                break;

            case 'attendee':
                echo json_encode($getTunnel->getAttendee($request[1]));
                break;
            default:
                http_response_code(404);
                break;
        }
        break;

    case 'POST':
        $payloadID = $globalOb->verifyToken()['payload'];
        $data = json_decode(file_get_contents("php://input"));
        switch ($request[0]) {
            case 'addEduc':
                echo json_encode($postTunnel->toAddResume($data, $globalOb->verifyToken()['payload'], 1));
                break;

            case 'addExp':
                echo json_encode($postTunnel->toAddResume($data, $globalOb->verifyToken()['payload'], 2));
                break;

            case 'addCert':
                echo json_encode($postTunnel->toAddResume($data, $globalOb->verifyToken()['payload'], 3));
                break;

            case 'addProj':
                echo json_encode($postTunnel->toAddResume($data, $globalOb->verifyToken()['payload'], 4));
                break;

            case 'addSpec':
                echo json_encode($postTunnel->toAddResume($data, $globalOb->verifyToken()['payload'], 5));
                break;

            case 'faculty':
                echo json_encode($postTunnel->addFaculty($data));
                break;

            case 'addCommex':
                echo json_encode($postTunnel->toAddCommex($data));
                break;

            case 'test':
                echo json_encode($postTunnel->test());
                break;

                // Use this for updating profile pic

            case 'profile':
                $params = $_GET["t"];
                echo json_encode($postTunnel->toEditProfile($params, $request[1]));
                break;

                // Use this for updating cover pic
            case 'cover':
                $params = $_GET["t"];
                echo json_encode($postTunnel->toEditCover($params, $request[1]));
                break;
            default:
                http_response_code(403);
                break;
        }
        break;

    case 'PATCH':
        $data = json_decode(file_get_contents("php://input"));

        // For req of Formdata
        // parse_str(file_get_contents("php://input"), $_PATCH);

        //No need for user id, so verification is applied globally. (Apply this to GET next time. Too lazy for now);
        $globalOb->verifyToken()['payload'];

        switch ($request[0]) {
            case 'editEduc':
                echo json_encode($postTunnel->toEditResume($data, $request[1], 1));
                break;

            case 'editExp':
                echo json_encode($postTunnel->toEditResume($data, $request[1], 2));
                break;

            case 'editCert':
                echo json_encode($postTunnel->toEditResume($data, $request[1], 3));
                break;

            case 'editProj':
                echo json_encode($postTunnel->toEditResume($data, $request[1], 4));
                break;

            case 'editSpec':
                echo json_encode($postTunnel->toEditResume($data, $request[1], 5));
                break;

            case 'faculty':
                echo json_encode($postTunnel->toEditFaculty($data, $request[1]));
                break;

            default:
                http_response_code(403);
                break;
        }
        break;

    case 'DELETE':
        $globalOb->verifyToken()['payload'];
        switch ($request[0]) {
            case 'deleteEduc':
                echo json_encode($postTunnel->toDeleteResume($request[1], 1));
                break;

            case 'deleteExp':
                echo json_encode($postTunnel->toDeleteResume($request[1], 2));
                break;

            case 'deleteCert':
                echo json_encode($postTunnel->toDeleteResume($request[1], 3));
                break;

            case 'deleteProj':
                echo json_encode($postTunnel->toDeleteResume($request[1], 4));
                break;

            case 'deleteSpec':
                echo json_encode($postTunnel->toDeleteResume($request[1], 5));
                break;

            case 'faculty':
                echo json_encode($postTunnel->toDeleteFaculty($request[1]));
                break;
            default:
                http_response_code(403);
                break;
        }
        break;

    case 'PUT':
        $globalOb->verifyToken()['payload'];
        $data = json_decode(file_get_contents("php://input"));
        switch ($request[0]) {
            case 'profile':
                $params = $_GET["t"];
                echo json_encode($postTunnel->toEditProfile($params, $request[1]));
                break;
            case 'test':
                echo json_encode($postTunnel->test());
                break;
            default:
                http_response_code(404);
                break;
        }
        break;
    default:
        http_response_code(404);
        break;
}
