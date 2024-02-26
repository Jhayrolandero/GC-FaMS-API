<?php
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: *");

foreach (glob("./Controller/*/*.php") as $filename) {
    include_once $filename;
}

include_once "./Model/database.php";
$con = new Connection();
$pdo = $con->connect();

$getSchedule = new Schedule($pdo);


if(isset($_REQUEST['request'])){
    $request = explode('/', $_REQUEST['request']);
}
else{
    http_response_code(404);
}


switch($_SERVER['REQUEST_METHOD']){
    case 'GET':
        switch($request[0]){
            case 'getschedules':
                if(count($request) == 3){
                    echo json_encode($getSchedule->getScheduleWeek($request[1], $request[2]));
                }
                else if(count($request) == 2){
                    echo json_encode($getSchedule->getScheduleFaculty($request[1]));
                }
                else{
                    echo json_encode($getSchedule->getScheduleAll());
                }            
                break;

            default:
                http_response_code(403);    
                break;
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        switch($request[0]){
        }    
        break;

    default:
        http_response_code(403);    
        break;
}
//echo json_encode($request);

?>