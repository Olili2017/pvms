<?php
    require_once "core/init.php";
    $db = new Database();
    $patient = new Patient();

    if(isset($_GET['type'])){
        $type = $_GET['type'];
        switch($type){
            case 'doctor':
                $result = $db->get("queues",array("queue_atendant_group", "=", "doctor"));
                if($result->count()){
                    foreach($result->results() as $item){
                        $visit = $db->get("visits",array("visit_Id","=",$item->visit_Id));
                        $patient = $db->get("patients",array("patient_Id","=",$visit->first()->patient_Id));
                        ?>

                        <div style="border:1px solid red;padding: 0.6em; margin-top: 1em;">
                            <strong style="font-size: 3em;" ><?php echo $item->queue_Id; ?></strong><br />
                            <i><?php echo $patient->first()->patient_name; ?></i>
                        </div>
                        <?php
                    }
                }else{
                    echo "Empty!";
                }
                break;
            case 'pharm':
            $result = $db->get("queues",array("queue_atendant_group", "=", "pharmacy"),array("queue_response", "=", "processing"));
            if($result->count()){
                foreach($result->results() as $item){
                    $visit = $db->get("visits",array("visit_Id","=",$item->visit_Id));
                    $patient = $db->get("patients",array("patient_Id","=",$visit->first()->patient_Id));
                    ?>

                    <div style="border:1px solid red;padding: 0.6em; margin-top: 1em;">
                        <strong style="font-size: 3em;" ><?php echo $item->queue_Id; ?></strong><br />
                        <i><?php echo $patient->first()->patient_name; ?></i>
                    </div>
                    <?php
                }
            }else{
                echo "Empty!";
            }
                break;
            case 'lab':
            $result = $db->get("queues",array("queue_atendant_group", "=", "laboratory"),array("queue_response", "=", "processing"));
            if($result->count()){
                foreach($result->results() as $item){
                    $visit = $db->get("visits",array("visit_Id","=",$item->visit_Id));
                    $patient = $db->get("patients",array("patient_Id","=",$visit->first()->patient_Id));
                    ?>

                    <div style="border:1px solid red;padding: 0.6em; margin-top: 1em;">
                        <strong style="font-size: 3em;" ><?php echo $item->queue_Id; ?></strong><br />
                        <i><?php echo $patient->first()->patient_name; ?></i>
                    </div>
                    <?php
                }
            }else{
                echo "Empty!";
            }
                break;
            default: 
                echo "invalid que visit";
                break;
        }
    }

    if(isset($_GET['count'])){
        switch($_GET['count']){
            case 'all':
                $doc_count = $db->getCount("queues",array("queue_atendant_group","=","doctor"));
            ?>
            <h3 class="" style="color: #666;"  >All Queues</h3>
            <p class="" style="color: #666;" >Doctor <span class="badge" ><?php echo $doc_count->first()->count ?></span></p>
            <?php
                $lab_count = $db->getCount("queues",array("queue_atendant_group","=","laboratory"),array("queue_response","=","pending"));
            ?>
            <p class="" style="color: #666;" >Laboratory <span class="badge" ><?php echo $lab_count->first()->count; ?></span></p>
            <?php 
                $pha_count = $db->getCount("queues",array("queue_atendant_group","=","pharmacy"),array("queue_response","=","pending"));
             ?>
            <p class="" style="color: #666;" >Pharmacy <span class="badge" ><?php echo $pha_count->first()->count ?></span></p>
            <p class="" style="color: #666;" ><a target="_blank" href="queues_screen.php" class="btn btn-sm bg-theme">Extend to External Display</a></p>
            <?php
                break;
            case 'laboratory':
                $lab_ready_count = $db->getCount("queues",array("queue_response","=","finished"),array("queue_atendant_group","=","laboratory"));
                ?>
                    <h3>Ready<span class="badge"><?php echo $lab_ready_count->first()->count ?></span></h3>
                <?php
                    $lab_processing_count = $db->getCount("queues",array("queue_response","=","processing"),array("queue_atendant_group","=","laboratory"));
                ?>
                    <h4>processing<span class="badge"><?php echo $lab_processing_count->first()->count ?></span></h4>
                <?php
                break;
            default:
                break;
        }
    }

    if(isset($_GET["for"])){
        $visitors = $db->get("queues",array("queue_atendant_id","=",$_GET['for']));
        
        foreach ($visitors->results() as $visitor) {
            $visit_file = $db->get("visits",array("visit_Id","=",$visitor->visit_Id));
            $visit_patient_id = $visit_file->first()->patient_Id;
            
            $patient->find($visit_patient_id);
            
            echo "<div class='col-md-12 card' ><span style='' class='pad-left'>{$visit_file->first()->visit_Id}{$visit_file->first()->patient_Id}</span><span> --  </span><span style='font-weight: 600; font-size: 1.5em;' class='text-theme'>{$patient->data()->patient_name}</span><button id='queued_patient' name='{$visit_file->first()->visit_Id}' class='btn btn-sm bg-theme pull-right'>Attend</button></div>";
        }

    }

    
?>
<script>

function queAttend(params) {
        alert("hello");
    }
</script>