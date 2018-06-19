<?php
    require_once "core/init.php";

    if(Input::exists()){
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            "user_fname" => array(
                "required" => true,
                "min" => 2, 
                "max" => 15
            ),
            "user_lname" => array(
                "required" => true,
                "min" => 2, 
                "max" => 15
            ),
            "user_alias" => array(
                "required" => true,
                "min" => 2, 
                "max" => 30,
                "unique" => "users"
            ),
            "user_password" => array(
                "required" => true,
                "min" => 6
            ),
            "user_role" => array(
                "required" => true
            ),
            ));

            if($validate->passed()){
                $user = new User();

                $salt = Hash::salt(32);
                try{
                    $user->create( array(
                        "user_fname" => Input::get('user_fname'),
                        "user_lname" => Input::get('user_lname'),
                        "user_alias" => Input::get('user_alias'),
                        "user_password" => Hash::make(Input::get('user_password'), $salt),
                        "contact" => Input::get("contact"),
                        "user_email" => Input::get("email"),
                        "dob" => Input::get("dob"),
                        "gender" => Input::get("gender"),
                        "salt" => Input::get('salt'),
                        "user_role" => get_user_role(Input::get('user_role'))
                    ));

                    //Redirect::to('index.php');
                    include_once "includes/message.php";
                }catch(Exception $ex){
                    die($ex->getMessage());
                }
            }else{
                foreach($validate->errors() as $error){
                    echo $error ."<br />";
                }
            }
    }

    function get_user_role($role = null){
        switch ($role) {
            case 'administrator':
                return 1;
            case 'receptionist':
                return 2;
            case 'doctor':
                return 3;
            case 'laboratory':
                return 4;
            case 'pharmacist':
                return 5;
            case 'patient':
                return 6;
            default:
                return null;
        }
    }
?>
    <section>
        <h3 class="text-theme">Add New User</h3>
    
        <form action="" method="POST" >
            <section class="form-group col-md-6" >
                <label for="user_fname"  >First name: </label>
                <input name="user_fname" class="form-control" type="text" placeholder="First name" autocomplete="off" />
            </section>
            <section class="form-group col-md-6" >
                <label for="user_lname"  >Last name: </label>
                <input name="user_lname" class="form-control" type="text" placeholder="Last name" autocomplete="off" />
            </section>
            <section class="form-group" >
                <label for="user_alias"  >username: </label>
                <input name="user_alias" class="form-control" type="text" placeholder="username" autocomplete="off" />
            </section>
            <section class="form-group" >
                <label for="user_password"  >Password: </label>
                <input name="user_password" value="12345" class="form-control" type="text" placeholder="Default is 12345" autocomplete="off" />
            </section>
            <section class="form-group col-md-4" >
                <label for="contact"  >Telephone: </label>
                <input name="contact" class="form-control" type="telephone" placeholder="Phone" autocomplete="off" />
            </section>
            <section class="form-group col-md-4" >
                <label for="dob"  >Date of Birth: </label>
                <input name="dob" class="form-control" type="date" autocomplete="off" />
            </section>
            <section class="form-group col-md-4" >
                <label for="gender"  >Gender: </label>
                <select name="gender" class="form-control" >
                    <option></option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </section>
            <section class="form-group col-md-4" >
                <label for="user_role"  >Position: </label>
                <select name="user_role" class="form-control" >
                    <option></option>
                    <option value="administrator" >Admin</option>
                    <option value="receptionist" >Receptionist</option>
                    <option value="doctor" >Doctor</option>
                    <option value="laboratory" >Laboratory</option>
                    <option value="pharmacist" >Pharmacist</option>
                    <option value="patient" >Patient</option>
                </select>
            </section>
            <section class="form-group col-md-8" >
                <label for="email"  >E-Mail: </label>
                <input name="email" class="form-control" type="email" placeholder="E-mail" autocomplete="off" />
            </section>
            <section class="form-group col-md-4" >
                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                <input type="hidden" name="salt" value="<?php echo Token::generate(); ?>" />
                <input name="register" class="form-control btn btn-primary" type="submit" value="Add employee" />
            </section>
        </form>
    </section>