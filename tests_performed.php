<?php 
require_once "core/init.php";
$timer = $_GET["period"];
$period = 0;
switch($timer){
    case "today":
        $period = 86400;
        break;
    case "thisweek":
        $period = 86400 * 7;
        break;
    case "thismonth":
        $period = 86400 * 31;
        break;
    default:
        $period = 86400;
        break;
}

$db = new Database();
$lab_tests = $db->query("SELECT lab_test_id FROM visit_test WHERE (".intval(time())." - request_date) < ${period}");

?>

                <table class="table" >
                     <thead>
                         <tr>
                             <th>Test</th>
                             <th>Num of times</th>
                         </tr>
                     </thead>
                     <tbody>
                         <?php
                            foreach($lab_tests->results() as $ts){
                                $db_ = new Database();
                                $test = $db_->query("SELECT test_name FROM laboratory_test WHERE test_Id = ".$ts->lab_test_id);
                                echo "<tr><td>".$test->first()->test_name."</td><td>".count($ts)."</td></tr>";
                            }
                          ?>
                     </tbody>
                 </table>