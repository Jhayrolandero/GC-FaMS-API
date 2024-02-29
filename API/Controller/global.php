<?php
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: *");

include_once "./Model/database.php";

class GlobalMethods extends Connection{
    //UNUSED FUNCTIONS (MAY BE REPURPOSED LATER)
    //UNUSED FUNCTIONS (MAY BE REPURPOSED LATER)
    //UNUSED FUNCTIONS (MAY BE REPURPOSED LATER)
    // public function sendPayLoad($data, $remarks, $message, $code){
    //     $status = array("remarks"=>$remarks, "message"=> $message);
    //     http_response_code($code);
    //     return array(
    //         "status"=>$status,
    //         "data"=>$data,
    //         "prepared_by"=>"Chris Kirk Patrick V. Viacrusis",
    //         "timestamp"=>date_create()
    //     );
    // }
    //UNUSED FUNCTIONS (MAY BE REPURPOSED LATER)
    //UNUSED FUNCTIONS (MAY BE REPURPOSED LATER)
    //UNUSED FUNCTIONS (MAY BE REPURPOSED LATER)
    
    public function executeQuery($sqlString){
        $data = array();
        $errmsg = "";
        $code = 0;

        try{
            if($result = $this->connect()->query($sqlString)->fetchAll()){
                foreach($result as $record){
                    array_push($data, $record);
                }
                $code = 200;
                $result = null;
                return array("code"=>$code, "data"=>$data);
            }
            else{
                $errmsg = "No data found";
                $code = 404;
            }
        }
        catch(\PDOException $e){
            $errmsg = $e->getMessage();
            $code = 403;
        }
        return array("code"=>$code, "errmsg"=>$errmsg);
    }


}

?>