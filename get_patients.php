<?php 
    require_once "core/init.php";
    $user = new User();
    $db = new Database();
    $p = new Patient();
    /*if(!$user->belongs_to_group("doctor") || !$user->belongs_to_group("receptionist")){
        die("You are not allowed to access this page");
    }*/
    
?> 
            <div id="available" class="table-responsive table-bordered">
                <table class="table" >
                    <thead class="table-header">
                        <tr>
                            <th>File No.</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Age(yrs)</th>
                            <th>Last visit</th>
                            <th>Visit count</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
<?php 
    $rows = "";
    $row = "";
    $btn_view_visit = "";
    $btn_end_visit = "";
    $btn_start_visit = "";
    $patient_count = array();

    if(isset($_GET['lower_limit'])){
        $lower_limit = $_GET['lower_limit'];
    }else{
        $lower_limit = 0;
    }

    if(isset($_GET['search'])){
        $db_p = new Database();
        $ppp = $db_p->search("patients","fname",$_GET['search'],"phone_no",$_GET['search']);
        $ppp_id = $ppp->first()->patient_Id; //@TODO: create view instead
        $patients = $db->search("visits","patient_Id",intval($ppp_id));
    }else{
        $patients = $db->query('SELECT * FROM visits ORDER BY visitStatus LIMIT '.$lower_limit.',8');
    }
    
    foreach($patients->results() as $patient){
        $btn_view_visit = "";
        $btn_end_visit = "";
        $btn_start_visit = "";
        $admission_date = "";
        $last_visit = "";
        $visit_count= "";
        $p->find($patient->patient_Id);
        array_push($patient_count, $p->data()->patient_Id);
        //$user->find($patient->doctor_Id);
        $check_permision = new User();
        
        $last_visit = $patient->lastVisit;
        
        $last_visit = ($patient->lastVisit != "")?(date('d-m-Y',$patient->lastVisit)):"not defined";
        
        if($patient->visitStartTimeStamp && !$patient->visitEndTimeStamp){
            //$last_visit = $patient->lastVisit;
            //$last_visit = date("d-m-Y",$last_visit);
            $btn_view_visit = "<a href='?page=patient_file&id={$patient->visit_Id}&patient={$p->data()->patient_Id}' class='btn bg-theme-light btn-sm' >View visit</a>";
            if($check_permision->belongs_to_group("receptionist"))
            $btn_end_visit = "<a href='#' onclick='javscript:endVisit({$patient->visit_Id})' class='btn btn-danger btn-sm' style='margin-left: 0.3em;' >End visit</a>";
        }else{
            //$last_visit = "not defined";
            $btn_start_visit = "<a href='javascript:void(0)' onclick='javascript:process({$patient->visit_Id})' class='btn btn-sm btn-primary form-control' >Start visit</a>";
            if($patient->visitEndTimeStamp){
                $btn_start_visit = "<a href='javascript:void(0)' onclick='javascript:process({$patient->visit_Id})' class='btn btn-sm btn-primary form-control' >Start new visit</a>";
                //$btn_start_visit = "<a href='javascript:void(0)' onclick='javascript:process({$p->data()->patient_Id})' class='btn btn-sm btn-primary form-control' >Start new visit</a>";
            }
        }

        $patient_name = strtoupper($p->data()->fname." ".$p->data()->lname);
        $patient_phone = $p->data()->phone_no;
        $patient_age = $p->data()->dateOfBirth;
        $patient_age = date("Y-m-d") - date($patient_age);
        $visit_count = $patient->visitCount;
        //die();
        $handler = ucwords($user->data()->user_fname." ".$user->data()->user_lname);
        $row .= "
        <tr>
            <td>PV/{$patient->visit_Id}{$patient->patient_Id}/18</td>
            <td>${patient_name}</td>
            <td>${patient_phone}</td>
            <td>${patient_age}</td>
            <td>${last_visit}</td>
            <td>${visit_count}</td>
            <td>${btn_start_visit}${btn_view_visit}${btn_end_visit}</td>
        </tr>";
    }

    //die(var_dump($patient_count));
    $rows .= $row;
    echo $rows;

    $___db = new Database();
    $patient_count = $___db->query("SELECT * FROM visits");
    $page_count = ceil(count($patient_count->results()) / 8);
?>
                    </tbody>
                </table>
<div class="container-fluid table-header footer-pager" >
    <div class="col-md-3" >
        <ul class="pagination" >Showing <?php echo count($patients->results()); ?> results of <?php echo count($patient_count->results()); ?></ul>
    </div>
    <div class="col-md-6" >
        <ul class="pagination">
            <?php 
                if($lower_limit){
                    //$active = 'active';
                    if($lower_limit == 0){
                        $default_active = 'active';
                        $target = null;
                        $active = null;
                    }else{
                        $target = ceil(($lower_limit + 8) / 8);
                        //die($target);
                        $default_active = null;
                        $active = null;
                    }
                }else{
                    $target = null;
                    $default_active = 'active';
                    $active = 'active';
                } for($i = 1; $i <= $page_count; $i ++){
                    if($i == $target){ $active = 'active'; }
                echo "<li class='${default_active} ${active}' ><a href='#' onclick='lowerLimit = (${i} * 8) - 8;' >${i}</a></li>";
                $default_active = null;
                $active = null;
            } ?>
        </ul>
    </div>
    <div class="col-md-3 pull-right">
        <ul class="pager">
            <li class="previous"><a href="#" onclick="if(lowerLimit > 0) { lowerLimit -= 8; }" >Previous</a></li>
            <li class="next"><a href="#" onclick="if(<?php $target; ?> != <?php $page_count; ?>){ lowerLimit += 8; }" >Next</a></li>
        </ul>
    </div>
</div>
            </div>


