<?php
    require_once "core/init.php";

    $user = new User();
    $patient = new Patient();

if(!$user->isLoggedIn()){
    Redirect::to('login.php');
}

    $db = new Database();

    if(isset($_GET["file"])){
        $file = intval($_GET["file"]);
        date_default_timezone_set('Africa/Kampala');

        if(isset($_GET['end'])){
            $db->delete("queues",array("visit_Id","=",$file));
            $db->delete("visit_medication",array("visit_id","=",$file));
            //$db->delete("visit_test",array("visit_id","=",$file));
            $patient->patientFileUpdate(array(
                'visitStatus' => "terminated",
                'attending_doctor' => null,
                'attending_lab_pro' => null,
                'attending_pharm_pro' => null,
                'visitEndTimeStamp' => intval(time()),
                'lastVisit' => intval(time()) 
            ),$file);
        }elseif(isset($_GET['que'])){
            $db->delete("queues",array("visit_Id","=",$file));
            $db->insert("queues",array(
                "visit_Id" => $file,
                "queue_atendant_group" => $_GET['que'],
                "queue_response" => "pending"
            ));
            $checks = array("a","b","c","d","e","f");
            if($_GET['que'] == "laboratory"){
                foreach($checks as $visit_test){
                    if(isset($_GET[$visit_test])){
                        $test_id = $db->get("laboratory_test",array("test_name","=",$_GET[$visit_test]));
                        //var_dump($test_id);
                        $db->insert("visit_test",array(
                            "visit_Id" => $file,
                            "lab_test_id" => $test_id->first()->test_Id
                        ));
                    }
                }
            }else{
                foreach($checks as $visit_presc){
                    if(isset($_GET[$visit_presc])){
                        $presc_id = $db->get("medication",array("med_name","=",$_GET[$visit_presc]));
                        //var_dump($presc_id);
                        $db->insert("visit_medication",array(
                            "visit_id" => $file,
                            "med_id" => $presc_id->first()->med_id
                        ));
                    }
                }
            }

        }else{

        $visit_count = $db->get("visits",array("visit_Id",'=',$file));
        
        $patient->patientFileUpdate(array(
            'visitStatus' => "started",
            'visitStartTimeStamp'=> intval(time()),
            'visitEndTimeStamp'=> null,
            'visitCount' => intval(($visit_count->first()->visitCount)) + 1
        ),$file);

        //$db->delete("queues",array("visit_Id","=",$file));
    }
    }

    if(isset($_GET["patient"])){
        $db->insert("visits",array(
            "patient_Id" => $_GET['patient'],
            "admition_executer_Id" => $user->data()->user_Id,
            "visitStatus" => "started",
            'visitStartTimeStamp'=> intval(time())
        ));
    }
    if(isset($_GET["visit"])){
        $db->insert("visits",array(
            "patient_Id" => $_GET['visit'],
            "admition_executer_Id" => $user->data()->user_Id,
            "visitStatus" => "started",
            'visitStartTimeStamp'=> intval(time())
        ));
    }

?>