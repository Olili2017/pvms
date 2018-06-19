<?php
    require_once "core/init.php";
    $user = new User();
    if(!$user->isLoggedIn()){
        Redirect::to('index.php');
    }

    if(!$user->has_permission("add_patient")){
        die("You dont have the permision to acces this feature!");
    }

    /**
     * prefill with empty spaces
     */
    $f_name = "";
    $l_name = "";
    $p_phone = "";
    $p_mail = "";
    $p_address = "";
    $p_gender = "";
    $p_age = "";
    $p_marrital = "";
    $p_nok_name = "";
    $p_nok_phone = "";
    $p_nok_rel = "";

    if(Input::exists()){
        if(Token::check(Input::get('token'))){
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                "patient_fname" => array(
                    "required" => true,
                    "min" => 2
                ),
                "patient_lname" => array(
                    "required" => true,
                    "min" => 2
                ),
                "phone_no" => array(
                    "required" => true,
                    "min" => 10
                ),
                "email" => array(
                    "required" => false,
                    "min" => 6
                ),
                "patient_address" => array(
                    "required" => true
                ),
                "gender" => array(
                    "required" => true
                ),
                "dob" => array(
                    "required" => false
                ),
                "marital_status" => array(
                    "required" => false
                ),
                "nok" => array(
                    "required" => true
                ),
                "nok_contact" => array(
                    "required" => true
                ),
                "nok_relationship" => array(
                    "required" => false
                )
            ));

            if($validate->passed()){

                $patient = new Patient();
                $db = new Database();

                    try{
                        $patient->add(array(
                            "fname" => Input::get("patient_fname"),
                            "lname" => Input::get("patient_lname"),
                            "phone_no" => Input::get("phone_no"),
                            "email" => Input::get("email"),
                            "patient_address" => Input::get("patient_address"),
                            "gender" => Input::get("gender"),
                            "dateOfBirth" => str_replace("/","-",Input::get("dob")),
                            "marital_status" => Input::get("marital_status"),
                            "nok" => Input::get("nok"),
                            "nok_contact" => Input::get("nok_contact"),
                            "nok_relationship" => Input::get("nok_relationship"),
                            "discharged" => 0
                        ));

                        $patient_id = $db->query("SELECT MAX(patient_Id) AS res_Id FROM patients")->first()->res_Id;

                        $db->insert("visits",array(
                            "patient_Id" => $patient_id,
                            "admition_executer_Id" => $user->data()->user_Id,
                            "visitStatus" => "terminated"
                        ));                        

                        //$role = $db->get("users",array("user_Id","=",Input::get("doctor_Id")))->first()->user_role;
                        /*
                        $db->insert("queues",array(
                            "visit_Id" => $db->query("SELECT MAX(visit_Id) AS res_Id FROM visits")->first()->res_Id,
                            "queue_atendant_group" => "doctor",
                            "queue_response" => "processing"
                        ));*/

                        //Redirect::to('index.php');
                        include "includes/message.php";
                    }catch(Exception $ex){
                        $f_name = Input::get("patient_fname");
                        $l_name = Input::get("patient_lname");
                        $p_phone = Input::get("phone_no");
                        $p_mail = Input::get("email");
                        $p_address = Input::get("patient_address");
                        $p_gender = Input::get("gender");
                        $p_age = Input::get("dob");
                        $p_marrital = Input::get("marital_status");
                        $p_nok_name = Input::get("nok");
                        $p_nok_phone = Input::get("nok_contact");
                        $p_nok_rel = Input::get("nok_relationship");
                        
                        die($ex->getMessage());
                    }
            }else{
                foreach($validate->errors() as $error){
                    echo $error ."<br />";
                }

                $f_name = Input::get("patient_fname");
                $l_name = Input::get("patient_lname");
                $p_phone = Input::get("phone_no");
                $p_mail = Input::get("email");
                $p_address = Input::get("patient_address");
                $p_gender = Input::get("gender");
                $p_age = Input::get("dob");
                $p_marrital = Input::get("marital_status");
                $p_nok_name = Input::get("nok");
                $p_nok_phone = Input::get("nok_contact");
                $p_nok_rel = Input::get("nok_relationship");
            }
    }
    }
?>

<form action="" method="post" >
    <h2 class="text-theme" >New Patient</h2>
        <section class="col-xs-12 col-sm-12 col-md-12">
            <section class="form-group col-md-4" >
                <label for="patient_fname" >First name</label>
                <input class="form-control" type="text" value="<?php echo $f_name; ?>" name="patient_fname" >
            </section>

            <section class="form-group col-md-4">
                <label for="patient_lname" >Last name</label>
                <input class="form-control" type="text" value="<?php echo $l_name; ?>" name="patient_lname" >
            </section>

            <section class="form-group col-md-4 clear-left" >
                <label for="phone_no" >Phone contact</label>
                <input class="form-control" type="telephone" value="<?php echo $p_phone; ?>" name="phone_no" >
            </section>
            
            <section class="form-group col-md-4" >
                <label for="email" >E-Mail</label>
                <input class="form-control" type="email" value="<?php echo $p_mail; ?>" name="email" >
            </section>

            
            <section class="form-group col-md-4" >
                <label for="patient_address" >Address</label>
                <input class="form-control" type="text" name="patient_address" value="<?php echo $p_address; ?>" >
            </section>
            
            <section class="form-group col-md-4" >
                <label for="gender" >Gender</label>
                <select class="form-control" name="gender" value="<?php echo $p_gender; ?>" >
                    <option>Choose one</option>
                    <option value="Male" >Male</option>
                    <option value="Female" >Female</option>
                </select>
            </section>
            
            <section class="form-group col-md-4" >
                <label for="dob" >Date of birth</label>
                <input class="form-control" type="date" name="dob" value="<?php echo $p_age; ?>" >
            </section>

            <section class="form-group col-md-4 clear-right" >
                <label for="marital_status" >Marital Status</label>
                <select class="form-control" name="marital_status" value="<?php echo $p_marrital; ?>" >
                    <option >Choose one</option>
                    <option value="married" >Married</option>
                    <option value="single" >Single</option>
                    <option value="divorsed" >Divorsed</option>
                    <option value="other" >Other</option>
                </select>
            </section>
            
            <section class="form-group col-md-4 clear-both" >
                <h4 class="text-theme" >Next of Kin Details</h4>
                <hr  class="bg-theme"/>
                <section class="col-md-12">
                    <label for="nok" >NOK name</label>
                    <input class="form-control" type="text" name="nok" value="<?php echo $p_nok_name; ?>" >
                </section>
                
                <section class="col-md-12" >
                    <label for="nok_contact" >NOK phone contact</label>
                    <input class="form-control" type="text" name="nok_contact" value="<?php echo $p_nok_phone; ?>" >
                </section>
                
                <section class="col-md-12">
                    <label for="nok_relationship" >NOK Relationship</label>
                    <select class="form-control" name="nok_relationship" value="<?php echo $p_nok_rel; ?>" >
                        <option>Choose relation</opption>
                        <option value="father" >Father</option>
                        <option value="mother" >Mother</option>
                        <option value="sibling" >Sibling</option>
                        <option value="friend" >Friend</option>
                        <option value="other" >Other</option>
                    </select>
                </section>
            </section>
            <section class="form-group col-md-4 clear-both" >
                <input class="form-control btn btn-primary" type="submit" value="Add patient" >
                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
            </section>
</form>