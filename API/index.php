<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header("Cache-Control: no-cache, no-store, must-revalidate");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {


    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With");

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
    // Enforce the login form should be populated else this will be evaluated
    if (empty(json_decode(file_get_contents("php://input")))) {
        echo "Unauthorized Access!";
        exit;
    }
    echo json_encode($postTunnel->toValidateLogin(json_decode(file_get_contents("php://input"))));
    exit;
}


//Main request switch endpoints
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $id = $globalOb->verifyToken()['payload']['id'];
        $college = $globalOb->verifyToken()['payload']['college'];
        $privilege = $globalOb->verifyToken()['payload']['privilege'];

        // $id = 40;
        // $college = 1;
        switch ($request[0]) {

            case 'picture':

                $docPath = $_GET["path"];
                $file = __DIR__ . "/../$docPath";

                echo $file;
                // $file =  "./Cv.pdf";
                if (file_exists($file)) {
                    // Set headers to indicate a file download
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/pdf');
                    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));

                    // Read the file and output its contents
                    readfile($file);
                    exit;
                } else {
                    http_response_code(404);
                    echo "File not found.";
                }

                break;
            case 'educdocs':
                echo json_encode($getTunnel->getDocs($id, 'educdocs'));
                break;

            case 'certdocs':
                echo json_encode($getTunnel->getDocs($id, 'certdocs'));
                break;

            case 'industrydocs':
                echo json_encode($getTunnel->getDocs($id, 'industrydocs'));
                break;

            case 'expdocs':
                echo json_encode($getTunnel->getDocs($id, 'expertisedocs'));
                break;

            case 'schedules':
                $query = $_GET['t'];
                if (empty($query)) {
                    http_response_code(404);
                    return;
                }
                switch ($query) {
                    case 'college':
                        echo json_encode($getTunnel->getSchedule($college, $query));
                        break;
                    case 'faculty':
                        echo json_encode($getTunnel->getSchedule($id, $query));
                        break;
                    default:
                        http_response_code(404);
                        break;
                }
                break;

            case 'fetchCollege':

                if ($privilege === "Admin") {
                    echo json_encode($getTunnel->getCollege(null));
                    break;
                }
                echo json_encode($getTunnel->getCollege($college));
                break;

            case 'profile':
                // echo json_encode($college);

                echo json_encode($getTunnel->getFaculty($id));
                break;

            case 'test':
                echo json_encode($getTunnel->test());
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
                        echo json_encode($getTunnel->getCommex($college, $query));
                        break;
                    case 'faculty':
                        echo json_encode($getTunnel->getCommex($id, $query));
                        break;

                    case 'faculty-id':
                        echo json_encode($getTunnel->getCommex($_GET['id'], $query));
                        break;

                    case 'all':
                        echo json_encode($getTunnel->getCommex(null, null, $query));
                        break;
                    default:
                        http_response_code(404);
                        break;
                }
                break;

            case 'certificate':
                echo json_encode($getTunnel->getCert($id, 0));
                break;

            case 'certificate-college':
                echo json_encode($getTunnel->getCert($college, 1));
                break;


            case 'experience':
                echo json_encode($getTunnel->getExp($id, 0));
                break;

            case 'experience-college':
                echo json_encode($getTunnel->getExp($college, 1));
                break;


            case 'education':
                echo json_encode($getTunnel->getEduc($id, 0));
                break;

            case 'education-college':
                echo json_encode($getTunnel->getEduc($college, 1));
                break;

            case 'project':
                echo json_encode($getTunnel->getProj($id, 0));
                break;

            case 'project-authors':
                echo json_encode($getTunnel->getProjAuthor($request[1]));
                break;

            case 'project-images':
                echo json_encode($getTunnel->getProjImages($request[1]));
                break;

            case 'project-college':
                echo json_encode($getTunnel->getProj($college, 1));
                break;


            case 'expertise':
                echo json_encode($getTunnel->getSpec($id, 0));
                break;

            case 'expertise-college':
                echo json_encode($getTunnel->getSpec($college, 1));
                break;


            case 'evaluation':
                echo json_encode($getTunnel->getEvaluation($id, 0));
                break;

            case 'evaluation-college':
                echo json_encode($getTunnel->getEvaluation($college, 1));
                break;

            case 'faculty':

                if ($privilege === "Admin") {
                    echo json_encode($getTunnel->getFaculties(null));
                    break;
                }

                echo json_encode($getTunnel->getFaculties($college));
                break;

            case 'attendee':

                if (isset($_GET['q'])) {
                    $query = $_GET['q'];
                    $commex_ID = $request[1];

                    switch ($query) {
                        case 'number':
                            echo json_encode($getTunnel->getAttendee($commex_ID, $query));
                            break;
                        case 'check':
                            echo json_encode($getTunnel->getAttendee($commex_ID, $query, $id));
                            break;

                        default:
                            http_response_code(404);
                            break;
                    }
                    die();
                }
                echo json_encode($getTunnel->getAttendee($request[1]));
                break;
            default:
                http_response_code(404);
                break;
        }
        break;

    case 'POST':
        $id = $globalOb->verifyToken()['payload']['id'];
        $college = $globalOb->verifyToken()['payload']['college'];
        $data = json_decode(file_get_contents("php://input"));
        switch ($request[0]) {
            case 'addEduc':
                echo json_encode($postTunnel->toAddResume($data, $id, 1));
                break;

            case 'addExp':
                echo json_encode($postTunnel->toAddResume($data, $id, 2));
                break;

            case 'addFacultyCert':
                echo json_encode($postTunnel->toAddResume($data, $id, 3));
                break;

            case 'addNewCert':
                echo json_encode($postTunnel->toAddResume($data, $id, 6));
                break;

            case 'addProj':
                echo json_encode($postTunnel->toAddResume($data, $id, 4));
                break;

            case 'addSpec':
                echo json_encode($postTunnel->toAddResume($data, $id, 5));
                break;

            case 'addNewSpec':
                echo json_encode($postTunnel->toAddResume($data, $id, 7));
                break;

            case 'addCv':
                echo json_encode($postTunnel->toAddCv($data, $id));
                break;

            case 'addCourse':
                echo json_encode($postTunnel->toAddCourse($data, $id));
                break;

            case 'addEval':
                echo json_encode($postTunnel->toAddEval($data, $id));
                break;

            case 'faculty':
                echo json_encode($postTunnel->addFaculty($data));
                break;

            case 'addCommex':
                echo json_encode($postTunnel->toAddCommex());
                break;

                // case 'test':
                //     echo json_encode($postTunnel->test($id));
                //     break;

                // Use this for updating profile pic

            case 'profile':
                // $params = $_GET["t"];
                echo json_encode($postTunnel->toEditProfile($id));
                break;

                // Use this for updating cover pic
            case 'cover':
                // $params = $_GET["t"];
                echo json_encode($postTunnel->toEditCover($id));
                break;
            case 'attendee':
                echo json_encode($postTunnel->toAddAttendee($id, $college));
                break;

            case 'educdocs':
                echo json_encode($postTunnel->toAddEducDocs($id));
                break;
            case 'certdocs':
                echo json_encode("HEYS");
                // echo json_encode($postTunnel->toAddCertDocs($id));
                break;
            case 'expdocs':
                echo json_encode($postTunnel->toAddExpDocs($id));
                break;
            case 'industrydocs':
                echo json_encode($postTunnel->toAddIndustryDocs($id));

                break;



            default:
                http_response_code(403);
                break;
        }
        break;

    case 'PATCH':
        $data = json_decode(file_get_contents("php://input"));
        $id = $globalOb->verifyToken()['payload']['id'];
        $college = $globalOb->verifyToken()['payload']['college'];

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

            case 'profile':
                echo json_encode($postTunnel->toEditFaculty2($data, $id));
                break;

            case 'selectCv':
                echo json_encode($postTunnel->toSelectCv($data, $id));
                break;

            case 'faculty':
                echo json_encode($postTunnel->toEditFaculty($data, $request[1]));
                break;

            case 'password':
                if (isset($request[1])) {
                    echo json_encode($postTunnel->toEditPassword($data, $request[1]));
                    die;
                }
                echo json_encode($postTunnel->toEditPassword($data, $id));
                break;

            case 'commex':
                echo json_encode($postTunnel->toEditCommex($data, $request[1]));
                break;
            default:
                http_response_code(403);
                break;
        }
        break;

    case 'DELETE':
        $id = $globalOb->verifyToken()['payload']['id'];
        $college = $globalOb->verifyToken()['payload']['college'];

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

            case 'deleteCourse':
                echo json_encode($postTunnel->toDeleteCourse($request[1], $id));
                break;

            case 'faculty':
                echo json_encode($postTunnel->toDeleteFaculty($request[1]));
                break;

            case 'attendee':
                $faculty_ID = $id;
                $commex_ID = $request[1];
                echo json_encode($postTunnel->toDeleteAttendee($commex_ID, $faculty_ID));
                break;

            case 'commex':
                $commex_ID = $request[1];
                echo json_encode($postTunnel->toDeleteCommex($commex_ID));
                break;

            case 'educdocs':
                echo json_encode($postTunnel->todeleteEducDocs($request[1]));
                break;
            case 'certdocs':
                echo json_encode($postTunnel->todeleteCertDocs($request[1]));
                break;
            case 'expdocs':
                echo json_encode($postTunnel->todeleteExpDocs($request[1]));
                break;
            case 'industrydocs':
                echo json_encode($postTunnel->todeleteIndustryDocs($request[1]));

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
