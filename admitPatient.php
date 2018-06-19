<?php
    require_once "core/init.php";
    $user = new User();
    if(!$user->isLoggedIn()){
        Redirect::to('index.php');
    }

    if(!$user->has_permission("add_patient")){
        die("You dont have the permision to acces this feature!");
    }

    if(Input::exists()){
        if(Token::check(Input::get('token'))){
            $validate = new Validate();
            $patient = new Patient();
            $db = new Database();
            $validation = $validate->check($_POST, array(
                "patient_Id" => array(
                    "required" => true
                )
            ));

            if($validate->passed()){

                $patient = new Patient();
                
                if($patient->find(Input::get("patient_Id"))){

                    try{
                        $db->insert("visits",array(
                            "patient_Id" => Input::get("patient_Id"),
                            "doctor_Id" => Input::get("doctor_Id"),
                            "admition_executer_Id" => $user->data()->user_Id,
                            "visitStatus" => "in queue",
                            "visitVitalTemperature" => Input::get("visitVitalTemperature"),
                            "visitVitalWeight" => Input::get("visitVitalWeight"),
                            "visitVitalHeight" => Input::get("visitVitalHeight"),
                            "visitVitalPressure" => Input::get("visitVitalPressure_sys")." ".Input::get("visitVitalPressure_dia"),
                            "visitVitalPulse" => Input::get("visitVitalPulse"),
                            "visitNotes" => Input::get("visitNotes")
                        ));
                        $role = $db->get("users",array("user_Id","=",Input::get("doctor_Id")))->first()->user_role;
                        
                        $db->insert("queues",array(
                            "visit_Id" => $db->query("SELECT MAX(visit_Id) AS res_Id FROM visits")->first()->res_Id,
                            "queue_atendant_id" => Input::get("doctor_Id"),
                            "queue_atendant_group" => $db->get("groups",array("Id","=",$role))->first()->alias,
                            "queue_response" => "processing"
                        ));

                        //Redirect::to('index.php');
                        include "includes/message.php";
                    }catch(Exception $ex){
                        die($ex->getMessage());
                    }
                }
            }else{
                foreach($validate->errors() as $error){
                    echo $error ."<br />";
                }
            }
    }
    }
?>

<form action="" method="post" >
    <h2>New Patient file</h2>
        <section class="col-xs-12 col-sm-12 col-md-12">
            <section class="form-group col-md-4" >
                <label for="patient_Id" >Patient ID</label>
                <input class="form-control" type="number" name="patient_Id" >
            </section>

            <section class="form-group col-md-4">
                <label for="doctor_Id" >Assign Doctor</label>
                <input class="form-control" type="text" name="doctor_Id" >
            </section>

            <section class="form-group col-md-4" >
                <label for="visitVitalTemperature" >Vital Temperature</label>
                <input class="form-control" type="number" name="visitVitalTemperature" >
            </section>
            
            <section class="form-group col-md-4" >
                <label for="visitVitalWeight" >Vital Weight</label>
                <input class="form-control" type="text" name="visitVitalWeight" >
            </section>

            
            <section class="form-group col-md-4" >
                <label for="visitVitalHeight" >Vital Height</label>
                <input class="form-control" type="text" name="visitVitalHeight" >
            </section>
            
            <section class="form-group col-md-4" >
                <h3>Vital Pressure</h3>
                <section class="col-md-3">
                    <label for="sys" >Sys</label>
                    <input class="form-control" type="number" name="visitVitalPressure_sys" >
                </section>
                <section class="col-md-3" >
                    <label for="Dia" >Dia</label>
                    <input class="form-control" type="number" name="visitVitalPressure_dia" >
                </section>
            </section>
            
            <section class="form-group col-md-4" >
                <label for="visitVitalPulse" >Vital Pulse rate</label>
                <input class="form-control" type="number" name="visitVitalPulse" />
            </section>
            
            <section class="form-group col-md-4" >
                <label for="visitNotes" >comments</label>
                <textarea rows="5" class="form-control" type="text" name="visitNotes" >
                </textarea>
            </section>
            <section class="form-group col-md-4 clear-both" >
                <input class="form-control btn bg-theme-light" type="submit" value="Submit file" >
                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
            </section>
</form>