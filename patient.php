<?php
    //include "core/init.php";
    $db = new Database();
    $user = new User();

    if(!$user->isLoggedIn())
    {
        Redirect::to('login.php');
    }

    if(Input::exists()){
        
        $validate = new Validate();
        $validation = $validate->check($_POST, array(

        ));

        if($validate->passed()){
            
            $p = new Patient();
            $p->patientFileUpdate(array(
                "visitVitalTemperature" => Input::get("visit_temp"),
                "visitVitalWeight" => Input::get("visit_weight"),
                "visitVitalHeight" => Input::get("visit_height"),
                "visitVitalPressure" => Input::get("visit_pressure"),
                "visitVitalPulse" => Input::get("visit_pulse"),
                "visitNotes" => Input::get("visit_notes"),
                "visitDiagnosis" => Input::get("visit_provisional_diagnosis")
            ),$_GET['id']);

        }
    }
    if(isset($_GET['id'])){
        $patient_file = $_GET['id'];
        $__patient = $_GET['patient'];

        $patient = new Patient();
        
        if(isset($_GET['attend'])){ 
            if($user->belongs_to_group("doctor")){
                $patient->patientFileUpdate(array(
                    'attending_doctor' => $user->data()->user_Id
                ),$patient_file);
                $db->update("queues","visit_Id",$patient_file,array("queue_response" => 'processing'),'doctor');
                
            }else if($user->belongs_to_group("laboratory")){
                $patient->patientFileUpdate(array(
                    'attending_lab_pro' => $user->data()->user_Id
                ),$patient_file);
                $db->update("queues","visit_Id",$patient_file,array("queue_response" => 'processing'),'laboratory');
            }else if($user->belongs_to_group("pharmacist")){
                $patient->patientFileUpdate(array(
                    'attending_pharm_pro' => $user->data()->user_Id
                ),$patient_file);
                $db->update("queues","visit_Id",$patient_file,array("queue_response" => 'processing'),'pharmacy');
            }
        }

        if($patient->find(intval($__patient))){
            $patient_details = array(
                'id' => $patient->data()->patient_Id,
                'name' => $patient->data()->fname." ".$patient->data()->lname,
                'phone' => $patient->data()->phone_no,
                'email' => $patient->data()->email,
                'address' => $patient->data()->patient_address,
                'gender' => $patient->data()->gender,
                'age' => date("Y-m-d") - date($patient->data()->dateOfBirth),
                'marital_status' => $patient->data()->marital_status,
                'next_of_kin' => $patient->data()->nok,
                'nok_contact' => $patient->data()->nok_contact,
                'nok_relationship' => $patient->data()->nok_relationship,
                'admition_status' => $patient->data()->discharged
            );
            if($patient_file){
                $file = $db->get("visits",array("visit_Id","=",intval($patient_file)));
                if($file->count()){
                    $visit = $file->first();
                }else{
                    Redirect::to(404);
                }
        }else{
            Redirect::to(404);
        }
        }else{
            Redirect::to(404);
        }
    }else{
        die();
    }


?>
<section class="container-fluid" >
    <?php if((!$user->belongs_to_group("laboratory")) and (!$user->belongs_to_group("pharmacist"))){ ?>
    <section class="col-m-12" >
        <section class="col-md-3" >
            <?php if($patient_details['id']){ ?>
                <div><b>File: </b> PV/<?php echo $visit->visit_Id."".$patient_details['id']."/".date("y"); ?></div>
            <?php } if($patient_details['name']){ ?>
                <div><h2><?php echo ucwords($patient_details['name']); ?></h2></div>
            <?php } ?>
        </section>
        <section class="col-md-3" >
            <?php if($patient_details['gender']){ ?>
                <div><b>Sex: </b> <?php echo ucwords($patient_details['gender']); ?></div>
            <?php } if($patient_details['age']){ ?>
                <div><b>Age: </b><?php echo ucwords($patient_details['age']); ?></div>
            <?php } if($patient_details['marital_status']){ ?>
                <div><b>Marital Status:</b> <?php echo ucwords($patient_details['marital_status']); ?></div>
            <?php } ?>
        </section>
        <section class="col-md-3" >
            <?php if($patient_details['address']){ ?>
                <div><b>Address: </b> <?php echo ucwords($patient_details['address']); ?></div>
            <?php } if($patient_details['phone']){ ?>
                <div><b>Tel: </b><?php echo ucwords($patient_details['phone']); ?></div>
            <?php } if($patient_details['email']){ ?>
                <div><b>E-Mail:</b> <?php echo strtolower($patient_details['email']); ?></div>
            <?php } ?>
        </section>
        <section class="col-md-3" >
            <?php if($patient_details['next_of_kin']){ ?>
                <div><b>NOK: </b> <?php echo ucwords($patient_details['next_of_kin']); ?></div>
            <?php } if($patient_details['nok_contact']){ ?>
                <div><b>NOK Contact: </b><?php echo ucwords($patient_details['nok_contact']); ?></div>
            <?php } if($patient_details['nok_relationship']){ ?>
                <div><b>Relationship:</b> <?php echo ucwords($patient_details['nok_relationship']); ?></div>
            <?php } ?>
        </section>
        <section class="col-md-12" >
            <?php if(!$user->belongs_to_group("laboratory") and !$user->belongs_to_group("pharmacist")){ ?>
            <a href="javascript:void(0)" class="btn btn-sm bg-theme pad-left pull-right col-md-2 pull-right" onclick="sendToLabQue(<?php echo $patient_file; ?>)" ><span class="glyphicon glyphicon-send" ></span>  Send to Laboratory</a>
            <?php } ?>
            <i class="col-md-1 pull-right" ></i>
            <?php if(!$user->belongs_to_group("doctor") and !$user->belongs_to_group("pharmacist")){ ?>
            <a href="javascript:void(0)" class="btn btn-sm bg-theme pad-left pull-right col-md-2 pull-right" onclick="sendToDocQue(<?php echo $patient_file; ?>)" ><span class="glyphicon glyphicon-send" ></span>  Send to Doctor</a>
            <i class="col-md-1 pull-right" ></i>
            <?php } ?>
            <?php if(!$user->belongs_to_group("laboratory") and !$user->belongs_to_group("pharmacist")){ ?>
            <a href="javascript:void(0)" class="btn btn-sm bg-theme pad-left pull-right col-md-2 pull-right" onclick="sendToPharmQue(<?php echo $patient_file; ?>)" ><span class="glyphicon glyphicon-send" ></span>  Send to Pharmacy</a>
            <?php } ?>
        </section>
    </section>
            <?php }
            $lab_active = "";
            $pharm_active = "";
            $rest_active = "";
            if($user->belongs_to_group("laboratory")){
                $lab_active = "active";
            }else if($user->belongs_to_group("pharmacist")){
                $pharm_active = "active";
            }else{
                $rest_active = "active";
            }
             ?>
    <section class="col-md-12" >
        <ul class="nav nav-tabs">
            <?php if((!$user->belongs_to_group("laboratory")) and (!$user->belongs_to_group("pharmacist"))){ ?>
            <li class="<?php echo $rest_active; ?>"><a data-toggle="tab" href="#vitals">Vitals</a></li>
            <?php } if(!$user->belongs_to_group("pharmacist")){?>
            <li class="<?php echo $lab_active; ?>" ><a data-toggle="tab" href="#laboratory">Laboratory</a></li>
            <?php } if(!$user->belongs_to_group("laboratory")){ ?>
            <li class="<?php echo $pharm_active; ?>"><a data-toggle="tab" href="#prescription">Prescriptions</a></li>
            <?php if(!$user->belongs_to_group("pharmacist")){ ?><li><a data-toggle="tab" href="#med_form">Medical Form</a></li>
            <li><a data-toggle="tab" href="#charts">Monitor</a></li>
            <?php } } ?>
        </ul>

        <div class="tab-content">
        <div id="laboratory" class="tab-pane fade in <?php echo $lab_active; ?>" >
            <div class="col-md-12 text-right" >
            <?php if(!$user->belongs_to_group("laboratory")){ ?><button class="btn btn-sm bg-theme-light" onclick="addLab(<?php echo $patient_file; ?>)" ><i class="glyphicon glyphicon-plus-sign" ></i> Add Lab Test</button><?php } ?>
            <?php if($user->belongs_to_group("laboratory")){ ?><button href="javascript:void(0)" class="btn btn-sm bg-theme" onclick="sendToDocQue(<?php echo $patient_file; ?>)" ><span class="glyphicon glyphicon-send" ></span>  Send to Doctor</button><?php } ?>
                <hr />
            </div>
                    
            <table class="table" >
                <thead>
                    <tr>
                        <th>Request</th>
                        <th>Finding</th>
                        <th>Comment</th>
                        <?php //if($user->belongs_to_group("laboratory")){ echo "<th>Actions</th>"; }else{ echo "<th>Status</th>"; }?>
                    </tr>
                </thead>
                <?php if(! $user->belongs_to_group("laboratory")){ ?>
                <tbody id="visit_labs" >
                    <!-- labs -->
                </tbody>
                    <?php }else{ ?>
                    <tbody>
                        <?php 
                            $id = $_GET['id'];
                            $patient = $_GET['patient'];
                        echo "<form action='u.php?id=${id}&patient=${patient}' method='post' >";
                            
                            $labs = $db->get("visit_test",array("visit_Id","=",$_GET['id']));
                            foreach($labs->results() as $lab){
                                $test = $db->get("laboratory_test",array("test_Id","=",$lab->lab_test_id));
                                
                                echo "<tr class='test' ><td><input type='text' name='lab_test_id_{$lab->lab_test_id}' value='{$lab->lab_test_id}' hidden>{$test->first()->test_name}</td><td><input type='text' class='form-control' name='result_{$lab->lab_test_id}' value='{$lab->results}' ></td><td><input type='text' class='form-control' name='comment_{$lab->lab_test_id}' value='{$lab->visit_test_comment}' ></td></tr>";
                            }
                        echo "<tr><td></td><td></td><td><input type='submit' class='btn btn-sm bg-theme-light pull-right' value='Save Lab results' ></td></tr>";
                            ?>
                        </form>
                    </tbody>
                   <?php } ?>
            </table>
        </div>
        <div id="vitals" class="tab-pane fade in <?php echo $rest_active; ?>">
            <h3>Vitals</h3>
          
            
            <div class="panel-body col-md-4" style="background-color: #eef2f5">
								<form id="update-vitals" method="post" >
								<div class="col-md-12">
									<div class="input-group">
										<input type="number" name="visit_temp" class="form-control" value="<?php echo $visit->visitVitalTemperature ?>">
										<span class="input-group-addon input-group-label">
											°C (Temperature)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										</span>
									</div>
									<br>
									<div class="input-group">
										<input type="text" name="visit_pressure" class="form-control" value="<?php echo (($visit->visitVitalPressure != "") or ($visit->visitVitalPressure != null))?implode("/",explode(" ",$visit->visitVitalPressure)):'' ?>">
										<span class="input-group-addon input-group-label">mmHg (Blood Pressure)</span>
									</div>
									<br>
									<div class="input-group">
										<input type="text" name="visit_pulse" class="form-control" value="<?php echo $visit->visitVitalPulse; ?>" >
										<span class="input-group-addon input-group-label">BPM (Heart Rate/Pulse)</span>
									</div>
									<br>
									<div class="input-group">
										<input type="number" name="visit_weight" id="vital-weight" class="form-control" value="<?php echo $visit->visitVitalWeight ?>">
										<span class="input-group-addon input-group-label">
											Kg (Weight)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										</span>
									</div>
									<br>
									<div class="input-group">
										<input type="number" name="visit_height" id="vital-height" class="form-control" value="<?php echo $visit->visitVitalHeight ?>" >
										<span class="input-group-addon input-group-label">
											M (Height)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										</span>
									</div>
									<br>
									
								
								</div>
                            </div>
                            
                            <div class="col-md-8">
						<h3 class="patient-tab-title">Notes</h3>
						<div class="form-group">
							<textarea class="form-control" name="visit_notes" rows="4"><?php echo $visit->visitNotes; ?></textarea>
						</div>
						<h3 class="patient-tab-title">Provisional Diagnosis</h3>
						<div class="form-group">
							<textarea class="form-control" name="visit_provisional_diagnosis" rows="3"><?php echo $visit->visitDiagnosis; ?></textarea>
                        </div>
                    <?php if($user->belongs_to_group("doctor")){ ?><button type="submit" name="submit_notes" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-save"></i>  Save Vitals</button><?php } ?>
                    </form>
					</div>



        </div>
        <div id="prescription" class="tab-pane fade in <?php echo $pharm_active; ?>">
            <?php 
                $prescriptions = $db->get("visit_medication",array("visit_id","=",$visit->visit_Id)); 
                if(!count($prescriptions->results())){
            ?>
            <div id="prescription_table" class="container-fluid" >
                No prescriptions yet. Click button below to prescribe a drag for <strong><?php echo strtoupper($patient_details['name']); ?></strong>
                <br />
                <a href="javascript:void(0)" onclick="addPresc(<?php echo $patient_file; ?>)" class="btn bg-theme-light btn-sm pull-right" >Prescribe</a>
            </div>
                <?php }else{ if(!$user->belongs_to_group("pharmacist")){ ?>
                <div class="col-md-12 text-right" ><button class="btn btn-sm bg-theme-light" onclick="addPresc(<?php echo $patient_file; ?>)" ><i class="glyphicon glyphicon-plus-sign" ></i> Add Drug</button><hr /></div><?php } ?>
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
                                    echo "<tr><form method='post' action='u.php?v=drug&id=${id}&patient=${patient}' ><td><input type='text' name='visit_med_Id' value='{$prescription->visit_med_Id}' hidden>${med_name}</td><td>{$med_id->first()->med_type}</td><td><input class='form-control med_dosage' type='text' name='dose' value='{$prescription->visit_med_dosage}' ></td><td><input class='form-control' type='text' name='duration' value='{$prescription->visit_med_duration}' ></td><td><input class='form-control' type='text' name='quantity' value='{$prescription->visit_med_quantity}' ></td><td><input type='submit' value='Save' class='btn btn-sm bg-theme-success' ><button class='btn btn-sm btn-danger' ><i class='glyphicon glyphicon-trash' ></i></button></td></form></tr>";
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
                                    echo "<tr><td>${med_name} &nbsp;&nbsp;&nbsp;${served}</td><td>{$med_id->first()->med_type}</td><td class='med_dose'>{$prescription->visit_med_dosage}</td><td>{$prescription->visit_med_duration}</td><td>{$prescription->visit_med_quantity}</td>${serve}</tr>";
                                }
                        }
                            ?>
                        </tbody>
                    </table>
                <?php } ?>

        </div>
        
        <div id="med_form" class="tab-pane fade">
            <h3 class="no-print" ><span class="text-theme" ><?php echo ucwords($patient_details['name']); ?></span> Medical Form</h3>
            <button onclick="javascript:printMedicalForm()" class="btn btn-sm btn-danger" ><i class="glyphicon glyphicon-print"></i>  Print</button>
            <div id="referral" class="col-md-8 referral">
						
						<div id="med-form-outer">
							<div class="med-form-inner">
								<div class="col-md-4" style="float: left; width: 33.33%; height: 130px;">
                                    
                                    <section style="padding: 4px; border: 2px solid  rgb(5, 58, 49); margin-top: 1.5em; color:  rgb(5, 58, 49); text-align: center; font-size: 1.5em;" >
                                        <?php echo Config::get("system/name_short"); ?>
                                    </section>
								</div>
								<div class="col-md-8" style="float: left; width: 66.67%; height: 130px; text-align: right;">
									<h3 style="text-transform: uppercase;">Medical Form</h3>
									<p><b>Date:</b>  <?php echo date('Y-m-j'); ?></p>
								</div>
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-4 form-group" style="float: left; width: 33.33%;">
											<label>Name of Patient</label>
											<input style="border: 0px !important; box-shadow: none;	border-bottom: 1px solid #DDD !important;" type="text" class="form-control" name="patient_name" value="<?php echo ucwords($patient_details['name']); ?>">
										</div>
										<div class="col-md-4 form-group" style="float: left; width: 33.33%;">
											<label>Age</label>
											<input style="border: 0px !important; box-shadow: none;	border-bottom: 1px solid #DDD !important;" type="text" class="form-control" name="patient_age" value="<?php echo $patient_details['age']; ?>">
										</div>
										<div class="col-md-4 form-group" style="float: left; width: 33.33%;">
											<label>Gender</label>
											<input style="border: 0px !important; box-shadow: none;	border-bottom: 1px solid #DDD !important;" type="text" class="form-control" name="patient_gender" value="<?php echo $patient_details['gender']; ?>">
										</div>
										<div class="col-md-8 form-group" style="float: left; width: 66.67%;">
											<label>Address</label>
											<input style="border: 0px !important; box-shadow: none;	border-bottom: 1px solid #DDD !important;" type="text" class="form-control" name="patient_address" value="<?php echo $patient_details['address']; ?>">
										</div>
										<div class="col-md-4 form-group" style="float: left; width: 33.33%;">
											<label>Phone</label>
											<input style="border: 0px !important; box-shadow: none;	border-bottom: 1px solid #DDD !important;" type="text" class="form-control" name="patient_contact" value="<?php echo $patient_details['phone']; ?>">
										</div>
										<div class="col-md-12 form-group" style="float: left; width: 100%;">
											<label>Notes</label>
											<textarea rows="6" style="border: 0px !important; box-shadow: none;	border-bottom: 1px solid #DDD !important;" class="form-control" name="referral_notes"><?php echo $visit->visitNotes; ?></textarea>
										</div>
										<div class="col-md-12 form-group" style="float: left; width: 100%;">
											<label>Diagnosis</label>
											<textarea rows="6" style="border: 0px !important; box-shadow: none;	border-bottom: 1px solid #DDD !important;" class="form-control" name="referral_diagnosis"><?php echo $visit->visitDiagnosis; ?></textarea>
										</div>
										<div class="col-md-8 form-group" style="float: left; width: 66.67%;">
											<label>Doctor's Name</label>
											<input style="border: 0px !important; box-shadow: none;	border-bottom: 1px solid #DDD !important;" type="text" class="form-control" name="doctor_name" >
										</div>
										<div class="col-md-4 form-group" style="float: left; width: 33.33%;">
											<label>Signature</label>
											<input style="border: 0px !important; box-shadow: none;	border-bottom: 1px solid #DDD !important;" type="text" class="form-control" value="">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>


        </div>

        <div id="charts" class="tab-pane fade">
            <h3>Blood Pusle & Pressure Monitor</h3>
            <canvas id="patientChart" class="col-m-12"></canvas>
        </div>
        </div>
    </section>
</section>
<div id="selectLabTestModal" class="modal fade" role="dialog" >
    <div class="modal-dialog" >
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
         <h4 class="modal-title">Choose lab test</h4>
      </div>
      <div class="modal-body">
            <div>
                <?php $lab_tests = $db->query("SELECT * FROM laboratory_test");  ?>
                <select id="tests" class="form-control form-control-sm" >
                    <?php foreach($lab_tests->results() as $tests){
                        echo "<option value='{$tests->test_name}' name='{$tests->test_name}' >{$tests->test_name}</option>";
                    } ?>
                </select>
                <button class="btn btn-sm bg-theme-light" style="margin-top: 4px;" onclick="addTest(document.getElementById('tests').value,document.getElementById('tests').name)" ><i class="glyphicon glyphicon-plus-sign" ></i>Add test</button>
            </div>
            <div id="view_repo" class="card"></div>
      </div>
      <div class="modal-footer">
        <button id="submitLab" class="btn btn-sm bg-theme" >Submit test</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>   
    </div>
</div>

            
            <!-- Prescribe Modal -->
            <div id="prescribe" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Choose drugs for <?php echo strtoupper($patient_details['name']); ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <input class="form-control form-control-sm" type="text" name="drag_search" placeholder="Enter drag name" onkeyup="populateResults(this.value);" >
                            <br />
                            <div id="drag_results" >
                                <!-- to display all drug results -->
                            </div>
                            <br />
                            <div id="drug_container" >
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button id="submitPresc" class="btn btn-sm bg-theme" >Submit Prescription</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                    </div>

                </div>
            </div>
<script>
var ctx = document.getElementById("patientChart").getContext('2d');

Chart.defaults.scale.ticks.beginAtZero = false;
var patientChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ["Day 1", "Day 2", "Day 3", "Day 4", "Day 5", "Day 6"],
        datasets: [{
            label: 'Systolic',
            fill: false,
            lineTension: 0.1,
            data: [120, 138, 125, 156, 130, 138],
            backgroundColor: "rgba(130, 99, 255, 0.2)",
            borderColor: "rgba(130, 99, 255, 1)",
            borderCapStyle: 'butt',
            borderDash: [],
            borderDashOffset:0.0,
            borderJoinStyle:'miter',
            borderPointColor:"rgba(130, 99, 255, 1)",
            pointBackgroundColor: "#fff",
            pointBorderWidth: 1,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(130, 99, 255, 1)",
            pointHoverBorderColor: "rgba(130, 99, 255, 1)",
            pointHoverBorderWidth: 2,
            pointRadius: 1,
            borderWidth: 1
        },
        {
            label: 'Diatolilc',
            fill: false,
            lineTension: 0.1,
            data: [89, 102, 100, 96, 88, 100],
            backgroundColor: "rgba(255, 72, 132, 0.2)",
            borderColor: "rgba(255, 72, 132, 1)",
            borderCapStyle: 'butt',
            borderDash: [],
            borderDashOffset:0.0,
            borderJoinStyle:'miter',
            borderPointColor:"rgba(255, 72, 132, 1)",
            pointBackgroundColor: "#fff",
            pointBorderWidth: 1,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(255, 72, 132, 1)",
            pointHoverBorderColor: "rgba(255, 72, 132, 1)",
            pointHoverBorderWidth: 2,
            pointRadius: 1,
            borderWidth: 1
        },
        {
            label: 'Pulse',
            fill: false,
            lineTension: 0.1,
            data: [69, 72, 80, 78, 72, 76],
            backgroundColor: "rgba(99, 255, 132, 0.2)",
            borderColor: "rgba(99, 255, 132, 1)",
            borderCapStyle: 'butt',
            borderDash: [],
            borderDashOffset:0.0,
            borderJoinStyle:'miter',
            borderPointColor:"rgba(99, 255, 132, 1)",
            pointBackgroundColor: "#fff",
            pointBorderWidth: 1,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(99, 255, 132, 1)",
            pointHoverBorderColor: "rgba(99, 255, 132, 1)",
            pointHoverBorderWidth: 2,
            pointRadius: 1,
            borderWidth: 1
        }
        ]
    },
    options: {
        scales: {
            yAxes: [{
                type: "logarithmic",
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});

    var note = document.getElementById("msg");
    var view_repo = document.getElementById("view_repo");
    var drug_container = document.getElementById("drug_container");
    
    var checks = ["a","b","c","d","e","f"]
    var tests = [];
    var pres = [];


    

    function addDrug(name,color){
        pres.push({name:name});

            let tab = document.createElement('span');
            let closePill_drug = document.createElement('button');
            
            closePill_drug.id=name+"_close";
            closePill_drug.innerText = "x";
            closePill_drug.style = "background: #666; border: 0px; border-radius: 50%;";

            tab.id = name;
            tab.style = "background-color: "+color+"; padding: 4px; border-radius: 4px; margin-left: 4px; color: #fff;";
            tab.innerText = name;
            tab.appendChild(closePill_drug);
            drug_container.appendChild(tab);
            $("#"+name+"_close").addClass("remove_drug");

            $("#"+name+"_close").click(function(){
                pres.forEach((item) => {
                    if(item.name == name){
                        pres.splice(pres.indexOf(item),1);
                        drug_container.removeChild(document.getElementById(name));
                    };
                });
            });
    }

    function addTest(name){
        tests.push({name:name});

            let elem = document.createElement('span');
            let closePill = document.createElement('button');

            closePill.id=name.replace(" ","_")+"_close";
            closePill.innerText = "x";
            closePill.style = "background: #666; border: 0px; border-radius: 50%;";

            elem.id = name.replace(" ","_");
            elem.style = "margin-left: 1em;";
            elem.innerText = name;
            elem.appendChild(closePill);
            view_repo.appendChild(elem);
            $("#"+name.replace(" ","_")+"").addClass("btn btn-sm btn-primary");
            $("#"+name.replace(" ","_")+"_close").addClass("remove_test");

            $("#"+name.replace(" ","_")+"_close").click(function(){
                tests.forEach((item) => {
                    if(item.name == name){
                        tests.splice(tests.indexOf(item),1);
                        view_repo.removeChild(document.getElementById(name.replace(" ","_")));
                    };
                });
            });
    }

  /*  setInterval(()=>{
        var last = 0;
        
        //console.log(tests[0].name);
        for(i = 0; i < tests.length; i++){
            if(tests[i].id == last){
                break;
            }
            let elem = document.createElement('span');
            elem.id = tests[i].name;
            last = tests[i].name;
            elem.innerText = tests[i].id;
            view_repo.appendChild(elem);
        }
    },1000);*/
    function sendToLabQue(){

        
        if (window.XMLHttpRequest) {
                        // code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp_pharm = new XMLHttpRequest();
                        } else {
                            // code for IE6, IE5
                            xmlhttp_pharm = new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        xmlhttp_pharm.onreadystatechange = function() {
                            if (xmlhttp_pharm.readyState == 4 && xmlhttp_pharm.status == 200) {
                                note.style.display = "block";
                                setTimeout(() => {
                                    note.style.display = "none";
                                }, 1000);
                                return true;
                            }
                        };
                        xmlhttp_pharm.open("GET","drags.php?q="+str,true);
                        xmlhttp_pharm.send();

                      return false;
    }

    function notification(message){
        
        let elem = document.createElement('span');
        let sound = document.createElement('audio');
            elem.id = "notification";
            elem.style = "border-radius: 0.4em; border: 2px solid rgb(26, 245, 7); background: rgba(0,200,0,0.7); padding: 16px; width: 20em; z-index: 99999; height: 4em; position: fixed; top: 4em; right: 2em;";
            elem.innerHTML = "<i class='glyphicon glyphicon-check' > "+message+"</i>";

            sound.id = "sound";
            sound.src = "assets/audio/message.mp3";
            sound.autoplay = "true";
            
            document.getElementById("wrapper").appendChild(elem);
            document.getElementById("wrapper").appendChild(sound);
            setTimeout(() => {
                document.getElementById("wrapper").removeChild(elem);
            document.getElementById("wrapper").removeChild(sound);
            }, 2000);

    }

    function notificationError(message){
        
        let elem = document.createElement('span');
        let sound = document.createElement('audio');
            elem.id = "notification";
            elem.style = "border-radius: 0.4em; color: white; border: 2px solid rgb(245, 26, 7); background: rgba(200,0,0,0.7); padding: 16px; width: 40em; z-index: 99999; height: 4em; position: fixed; top: 4em; right: 2em;";
            elem.innerHTML = "<i class='glyphicon glyphicon-info-sign' > "+message+"</i>";

            sound.id = "sound";
            sound.src = "assets/audio/message_error.wav";
            sound.autoplay = "true";
            
            document.getElementById("wrapper").appendChild(elem);
            document.getElementById("wrapper").appendChild(sound);
            setTimeout(() => {
                document.getElementById("wrapper").removeChild(elem);
            document.getElementById("wrapper").removeChild(sound);
            }, 5000);

    }

    function sendToPharmQue(file){
        var durated = false;
        var med_dosage = document.getElementsByClassName('med_dosage');
        //console.log(med_dosage);
        for(var i = 0; i<med_dosage.length; i++){
            if((med_dosage[i].value !== "") && (med_dosage[i].value !== null)){
                durated = true;
            }else{ durated = false; }
        }

        if(durated){

        if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttpPharmQue = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttpPharmQue = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttpPharmQue.onreadystatechange = function() {
                if (xmlhttpPharmQue.readyState == 4 && xmlhttpPharmQue.status == 200) {
                    //alert("completed ... ");
                    notification("Append Pharmacy Successful!!");
                }
            };

                //console.log("start_visit.php?que=laboratory&file="+file+""+url);
                xmlhttpPharmQue.open("GET","add_to_que.php?queue=pharmacy&file="+file,true);
                xmlhttpPharmQue.send();
                
        }else{ notificationError("You need to provide prescriptions before you submit!"); }
    }

    function addPresc(file){
        if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttpPharmQue = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttpPharmQue = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttpPharmQue.onreadystatechange = function() {
                if (xmlhttpPharmQue.readyState == 4 && xmlhttpPharmQue.status == 200) {
                    //alert("completed ... ");
                    $("#prescribe").modal("hide");
                }
            };
            
            $("#prescribe").modal("show");
            document.getElementById("submitPresc").addEventListener("click",()=>{  
                let url = "";
                for(i = 0; i < pres.length; i++){
                    url += "&"+checks[i]+"="+pres[i].name;
                }
                //console.log("add_drug.php?file="+file+""+url);
                xmlhttpPharmQue.open("GET","add_drug.php?file="+file+""+url,true);
                xmlhttpPharmQue.send();
            });
    }

    function sendToDocQue(file){
        if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttpPharmQue = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttpPharmQue = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttpPharmQue.onreadystatechange = function() {
                if (xmlhttpPharmQue.readyState == 4 && xmlhttpPharmQue.status == 200) {
                    //alert("completed ... ");
                    notification("Send to Doctor Successful!!");
                }
            };
            xmlhttpPharmQue.open("GET","start_visit.php?que=doctor&file="+file,true);
            xmlhttpPharmQue.send();
    }

function sendToLabQue(file){

        if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttpLabQue = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttpLabQue = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttpLabQue.onreadystatechange = function() {
                if (xmlhttpLabQue.readyState == 4 && xmlhttpLabQue.status == 200) {
                    //alert("completed ... ");
                    notification("Append laboratory Successful!!");
                }
            };
                //console.log("start_visit.php?que=laboratory&file="+file+""+url);
                xmlhttpLabQue.open("GET","add_to_que.php?queue=laboratory&file="+file,true);
                xmlhttpLabQue.send();
}

function addLab(file){
        if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttpLabQue = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttpLabQue = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttpLabQue.onreadystatechange = function() {
                if (xmlhttpLabQue.readyState == 4 && xmlhttpLabQue.status == 200) {
                    //alert("completed ... ");
                    $("#selectLabTestModal").modal("hide");
                }
            };
            $("#selectLabTestModal").modal("show");
            document.getElementById("submitLab").addEventListener("click",()=>{  
                let url = "";
                for(i = 0; i < tests.length; i++){
                    url += "&"+checks[i]+"="+tests[i].name;
                }
                //console.log("start_visit.php?que=laboratory&file="+file+""+url);
                xmlhttpLabQue.open("GET","add_lab.php?file="+file+""+url,true);
                xmlhttpLabQue.send();
            });
}

 function populateResults(str){
    if (str.length==0) { 
    document.getElementById("drag_results").innerHTML="";
    document.getElementById("drag_results").style.border="0px";
    return;
  }

    if (window.XMLHttpRequest) {
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttpDrag = new XMLHttpRequest();
                      } else {
                          // code for IE6, IE5
                          xmlhttpDrag = new ActiveXObject("Microsoft.XMLHTTP");
                      }
                      xmlhttpDrag.onreadystatechange = function() {
                          if (xmlhttpDrag.readyState == 4 && xmlhttpDrag.status == 200) {
                              document.getElementById("drag_results").innerHTML = xmlhttpDrag.responseText;
                            return true;
                          }
                      };
                      xmlhttpDrag.open("GET","drags.php?q="+str,true);
                      xmlhttpDrag.send();

                      return false;
 }

 (function(){
     var visit_labs = document.getElementById("visit_labs");
     setInterval(() => {
        if (window.XMLHttpRequest) {
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttpGetVisitLabs = new XMLHttpRequest();
                } else {
                    // code for IE6, IE5
                    xmlhttpGetVisitLabs = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttpGetVisitLabs.onreadystatechange = function() {
                    if (xmlhttpGetVisitLabs.readyState == 4 && xmlhttpGetVisitLabs.status == 200) {
                        
                        visit_labs.innerHTML = xmlhttpGetVisitLabs.responseText;
                    }
                };

                    console.log("get_lab_tests.php?id=<?php echo $_GET['id'].'&from='.$visit->visitStartTimeStamp; ?>");
                    xmlhttpGetVisitLabs.open("GET","get_lab_tests.php?id=<?php echo $_GET['id'].'&from='.$visit->visitStartTimeStamp; ?>",true);
                    xmlhttpGetVisitLabs.send();

     },1500);
 }());

 function printMedicalForm(){
     var printable = document.getElementById("referral");
     ///alert(printable.innerText);
     window.print();
 }
</script>
