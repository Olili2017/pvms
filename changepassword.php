<?php
    require_once 'core/init.php';

    $user = new User();

    if(!$user->isLoggedIn()){
        Redirect::to('index.php');
    }

    if(Input::exists()){
        if(Token::check(Input::get('token'))){
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'password_current' => array(
                    'required' => true,
                    'min' => 6
                ),
                'password_new' => array(
                    'required' => true,
                    'min' => 6
                ),
                'password_new_again' => array(
                    'required' => true,
                    'min' => 6,
                    'matches' => 'password_new'
                )
                ));

                if($validate->passed()){
                    if(Hash::make(Input::get('password_current'), $user->data()->salt) !== $user->data()->user_password){
                        echo "Current password Incorrect!";
                    }else{
                        $salt = Hash::salt(32);
                        $user->update(array(
                            'user_password' => Hash::make(Input::get('password_new'), $salt),
                            'salt' => $salt
                        ));

                        Redirect::to('index.php');
                    }
                }else{
                    foreach($validate->errors() as $error){
                        echo $error."<br />";
                    }
                }
        }
    }

?>

<form action="" method="post" >
    <section class="form-group" >
        <label  for="password_current" >Current Password</label>
        <input placeholder="Enter you current password" type="password" class="form-control" name="password_current" id="password_current" >
    </section>
    
    <section class="form-group" >
        <label  for="password_new" >New Password</label>
        <input placeholder="Enter new password" class="form-control"  type="password" name="password_new" id="password_new" >
    </section>
    
    <section class="form-group" >
        <label  for="password_new_again" >New Password again</label>
        <input placeholder="New password again" class="form-control" type="password" name="password_new_again" id="password_new_again" >
    </section>

    <section class="form-group" >
        <input class="btn btn-sm bg-theme-light" type="submit" value="Change">
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" >
    </section>
</form>