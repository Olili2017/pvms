<?php

    require_once "core/init.php";
    $queue_attendant = new User();
    $user = new User();
    $queue = new Database();

$queue_atendant_group = "";


if($queue_attendant->belongs_to_group("doctor")){
    $queue_atendant_group = "doctor";
}else if($queue_attendant->belongs_to_group("laboratory")){
    $queue_atendant_group = "laboratory";
}else if($queue_attendant->belongs_to_group("pharmacist")){
    $queue_atendant_group = "pharmacy";
}else if($queue_attendant->belongs_to_group("receptionist")){
    $queue_atendant_group = $_GET["group"];
}else{
    echo "no lists for you";
    die();
}

if(isset($_GET["for"])){
    echo $_GET["for"]."`s Queue here";
    die();
}

$queue = $queue->get("queues", array("queue_atendant_group", "=", $queue_atendant_group));

$db = new Database();
$patient = new Patient();

if(count($queue)){

        $status = "";
        $STATUS = "";
        $color = "";
        $$status = "";
        $need_cancel = "";
        $status = "STATUS";
        $STATUS = "";
    //$visitors = $db->get("queues",array("queue_atendant_group","=",$queue_atendant_group));
    /*if($queue_attendant->belongs_to_group("doctor") && isset($_GET["check"])){
        $status = "STATUS";
        $STATUS = "";
        $need_cancel = "<button class='btn btn-sm btn-danger' >Cancel</button>";
    }*/
    switch($queue_atendant_group){
        case 'doctor':
            $_group = "DOCTOR";
            break;
        case 'pharmacy':
            $_group = "PHARMACIST";
            break;
        case 'laboratory':
            $_group = "LAB TECHNICIAN";
            break;
        default:
            $_group = "";
            break;
    }
    if($user->belongs_to_group("receptionist") or $user->belongs_to_group("administrator")){
        $view_option = "STATUS";
    }else{
        $view_option = "ACTIONS";
    }
    $output = <<<HTML
    <div class='col-md-12 card-stark text-theme' style="font-weight: 600; font-size: 1em; text-transform: capitalize;" >
        <div class="col-md-2" >
            <span >FILE NO:</span>
        </div>
        <div class="col-md-3" >
            <span class="text-right" style="padding-left: 2em; text-align: right;" >PATIENT NAME</span>
        </div>
        <div class="col-md-3" >
            <span >${_group}</span>
        </div>
        <div class="col-md-2" >
            <span style="padding-left: 2em;" >{$status}</span>
        </div>
        <div class="col-md-2 text-right" >
            ACTIONS
        </div>
    </div>
HTML;

    echo $output;

        foreach ($queue->results() as $visitor) {
            $visit_file = $db->get("visits",array("visit_Id","=",$visitor->visit_Id));
            $visit_patient_id = $visit_file->first()->patient_Id;
            
            $patient->find($visit_patient_id);
            $STATUS = ucfirst($visitor->queue_response);
            if($STATUS == "Processing"){
                $color = "orange";
            }else{
                $color = "green";
            }
            $handler = "";
            if(($visit_file->first()->attending_doctor != null) and ($queue_atendant_group == "doctor")){
                $queue_attendant->find($visit_file->first()->attending_doctor);
                $handler = $queue_attendant->data()->user_fname." ".$queue_attendant->data()->user_lname;
            }
            if(($visit_file->first()->attending_lab_pro != null) and ($queue_atendant_group == "laboratory")){
                $queue_attendant->find($visit_file->first()->attending_lab_pro);
                $handler = $queue_attendant->data()->user_fname." ".$queue_attendant->data()->user_lname;
            }
            if(($visit_file->first()->attending_pharm_pro != null) and ($queue_atendant_group == "pharmacy")){
                $queue_attendant->find($visit_file->first()->attending_pharm_pro);
                $handler = $queue_attendant->data()->user_fname." ".$queue_attendant->data()->user_lname;
            }
            
            ?>

            <div class='col-md-12 card-stark' >
                <div class="col-md-2" >
                    <span >PV/<?php echo $visit_file->first()->visit_Id."".$visit_patient_id; ?>/18</span>
                </div>
                <div class="col-md-3" >
                    <span class="text-right" style="padding-left: 2em; text-align: right; font-weight: 600; font-size: 1.2em; color: #666; text-transform: capitalize;" ><?php echo $patient->data()->fname." ".$patient->data()->lname; ?></span>
                </div>
                <div class="col-md-3" >
                    <span style="padding-right: 2em;" ><?php echo $handler?$handler:'not assigned'; ?></span>
                </div>
                <div class="col-md-2" >
                    <span style="padding-left: 2em; font-weight: 600; color: <?php echo $color; ?>" ><?php echo $$status; ?></span>
                </div>
                <div class="col-md-2 text-right" >
                    <?php 
                        if($need_cancel == ""){
                            
                                if(($visit_file->first()->attending_doctor == null) and ($user->belongs_to_group("doctor")) or ($visit_file->first()->attending_lab_pro == null) and ($user->belongs_to_group("laboratory")) or ($visit_file->first()->attending_pharm_pro == null) and ($user->belongs_to_group("pharmacist"))){
                                ?>
                                <a href="<?php echo '?page=patient_file&attend=true&id='.$visit_file->first()->visit_Id.'&patient='.$visit_patient_id; ?>" class="btn btn-sm btn-primary" >Attend to patient</a>
                                <?php }else{ ?>
                                <a href="<?php echo '?page=patient_file&id='.$visit_file->first()->visit_Id.'&patient='.$visit_patient_id; ?>" class="btn btn-sm bg-theme-light" >View patient</a>
                                <?php
                                }
                            
                            
                        }else{
                            //echo $need_cancel;
                        }
                        ?>

                </div>
            </div>
<?php
            //echo $output;
        }

}else{
    $output = <<<HTML
    <div class="col-md-12" >
        <p>nothing in the queue yet.</p>
    </div>
HTML;
echo $output;
}

?>