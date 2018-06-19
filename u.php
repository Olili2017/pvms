<?php 
require_once("core/init.php");
$db = new Database();
if(isset($_GET['v'])){
    if($_GET['v'] == "drug"){  
        $db->update("visit_medication","visit_med_Id",Input::get("visit_med_Id"),array(
            "visit_med_dosage" => Input::get("dose"),
            "visit_med_duration" => Input::get("duration"),
            "visit_med_quantity" => Input::get("quantity")
        ));
    }
}else if(isset($_GET['serve'])){
    if($_GET['serve'] == "true"){
        $db->update("visit_medication","visit_med_Id",$_GET['presc_id'],array(
            "served" => 1
        ));
    }
}else{
    $count = 0;
    $storage = array();
    if(count($_POST)){
        //var_dump($_POST);
        foreach($_POST as $key){
            //echo $key;
            $count += 1;
            array_push($storage,$key);
            if($count == 3) 
            {
                $db->update("visit_test","lab_test_id",$storage[0],array(
                    "results" => $storage[1],
                    "visit_test_comment" => $storage[2],
                    "taken" => 1,
                    "date_taken" => intval(time())
                ));
                //var_dump($storage);
                $storage = array();
                $count = 0;
            }
        }
        //die();
    }
}

    Redirect::to("index.php?page=patient_file&id={$_GET['id']}&patient={$_GET['patient']}");
    //$db->tabulate("users",array("user_Id","=",1000));
?>