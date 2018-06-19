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

                $checks = array("a","b","c","d","e","f");
                foreach($checks as $visit_test){
                    if(isset($_GET[$visit_test])){
                        $test_id = $db->get("laboratory_test",array("test_name","=",$_GET[$visit_test]));
                        //var_dump($test_id);
                        $db->insert("visit_test",array(
                            "visit_Id" => $file,
                            "lab_test_id" => $test_id->first()->test_Id,
                            "taken" => 0,
                            "request_date" => intval(time())
                        ));
                    }
                }
            }
?>