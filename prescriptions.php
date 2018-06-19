<?php 
    require_once "core/init.php";
    $db = new Database();
                $prescriptions = $db->get("visit_medication",array("visit_id","=",$visit->visit_Id)); 
                if(!count($prescriptions->results())){
            ?>
            <div id="prescription_table" class="container-fluid" >
                No prescriptions yet. Click button below to prescribe a drag for <strong><?php echo strtoupper($patient_details['name']); ?></strong>
                <br />
                <a href="javascript:void(0)" onclick="addPresc(<?php echo $patient_file; ?>)" class="btn bg-theme-light btn-sm pull-right" >Prescribe</a>
            </div>
                <?php }else{ ?>
                    <div class="col-md-12 text-right" ><button class="btn btn-sm bg-theme-light" onclick="addPresc(<?php echo $patient_file; ?>)" ><i class="glyphicon glyphicon-plus-sign" ></i> Add Drug</button><hr /></div>
                    <table class="table" >
                        <thead>
                            <tr>
                                <th>Drug</th>
                                <th>Formulation</th>
                                <th>Daily intake</th>
                                <th>Duration (days)</th>
                                <th>Quantity</th>
                                <?php if($user->belongs_to_group("doctor") or $user->belongs_to_group("pharmacist")){ echo "<th>Actions</th>"; }?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if($user->belongs_to_group("doctor")){
                                foreach($prescriptions->results() as $prescription){
                                    $med_id = $db->get("medication",array("med_id","=",$prescription->med_id));
                                    $med_name = strtoupper($med_id->first()->med_name); 
                                    $id = $_GET['id'];
                                    $patient = $_GET['patient'];
                                    echo "<tr><form method='post' action='u.php?v=drug&id=${id}&patient=${patient}' ><td><input type='text' name='visit_med_Id' value='{$prescription->visit_med_Id}' hidden>${med_name}</td><td>{$med_id->first()->med_type}</td><td><input class='form-control' type='text' name='dose' value='{$prescription->visit_med_dosage}' ></td><td><input class='form-control' type='text' name='duration' value='{$prescription->visit_med_duration}' ></td><td><input class='form-control' type='text' name='quantity' value='{$prescription->visit_med_quantity}' ></td><td><input type='submit' value='Save' class='btn btn-sm bg-theme-success' ><button class='btn btn-sm btn-danger' ><i class='glyphicon glyphicon-trash' ></i></button></td></form></tr>";
                                }
                            }else{
                                foreach($prescriptions->results() as $prescription){
                                    $med_id = $db->get("medication",array("med_id","=",$prescription->med_id));
                                    $med_name = strtoupper($med_id->first()->med_name); 
                                    $serve = null;
                                    $served = null;
                                    $id = $_GET['id'];
                                    $patient = $_GET['patient'];
                                    if($user->belongs_to_group("pharmacist")){
                                        if($prescription->served){
                                            $serve = "<td style='color: green; font-weight: 600;' >Served <i class='glyphicon glyphicon-ok-sign' ></i></td>";
                                        }else{
                                            $serve = "<td><a href='u.php?serve=true&presc_id={$prescription->visit_med_Id}&id=${id}&patient=${patient}' class='btn btn-sm bg-theme-light' >serve</a></td>";
                                        }
                                    }else{
                                        if($prescription->served){
                                            $served = "<i style='color: green; font-weight: 600;' class='glyphicon glyphicon-ok-sign' ></i>";
                                        }else{
                                            $served = "<i style='color: orange; font-weight: 600;' class='glyphicon glyphicon-question-sign' ></i>";
                                        }
                                    }
                                    echo "<tr><td>${med_name} &nbsp;&nbsp;&nbsp;${served}</td><td>{$med_id->first()->med_type}</td><td>{$prescription->visit_med_dosage}</td><td>{$prescription->visit_med_duration}</td><td>{$prescription->visit_med_quantity}</td>${serve}</tr>";
                                }
                        }
                            ?>
                        </tbody>
                    </table>
                <?php } ?>
