<?php 
    require_once "core/init.php";
    $db = new Database();


                        $labs = $db->get("visit_test",array("visit_Id","=",$_GET['id']),array("request_date",">",$_GET['from']));
                        foreach($labs->results() as $lab){
                            $test = $db->get("laboratory_test",array("test_Id","=",$lab->lab_test_id));
                            $results = $lab->results;
                            $lab_status = ($lab->taken)?"<span style='color: green;' ><b><i class='glyphicon glyphicon-check' ></i> Finished</b></span>":"<span style='color: orange;' >proccessing</span>";
                            echo "<tr><td>{$test->first()->test_name}</td><td class='test_results'>${results}</td><td>{$lab->visit_test_comment}</td><td>${lab_status}</td></tr>";
                        }
                    
?>