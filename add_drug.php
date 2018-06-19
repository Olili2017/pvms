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
?>