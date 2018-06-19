<?php
require_once "core/init.php";

if(!$username = Input::get('user')){
    Redirect::to('index.php');
}else{
    $user = new User($username);
    if(!$user->exists()){
        Redirect::to(404);
    }else{
        $data = $user->data();
    }
}

?>

<section class="container-fluid" >
<h2 class="text-center" ><?php if($user->data()->user_Id == Session::get(Config::get('session/session_name'))){ ?>Your Profile<?php }else{ echo ucfirst($user->data()->user_alias); ?>`s Profile <?php } ?></h2>
    <section class="col-sm-6 col-md-2" >
        <img class="img img-thumbnail" src="assets/images/users/<?php echo $user->userImage(); ?>" alt="my_pic" />
    </section>
    <section class="col-sm-6 col-md-8" >
        <section class="col-md-12">
            <h3><?php echo escape(ucwords($data->user_fname)." ".$data->user_lname." (".$data->user_alias.")")." <span class='text-theme-light' >[".$data->user_Id."]<span>"; ?></h3>
        </section>
        <hr class="col-md-12 bg-theme-light" />
        <section class="col-md-12" >
            <div class="col-md-3 text-right" ><b>Username</b></div>
            <div class="col-md-9" ><?php echo escape($data->user_alias." ".$data->user_lname); ?></div>
            <div class="col-md-3 text-right" ><b>Email address</b></div>
            <div class="col-md-9" ><?php echo escape($data->user_email); ?></div>
            <div class="col-md-3 text-right" ><b>Registration date</b></div>
            <div class="col-md-9" ><?php echo escape($data->reg_date); ?></div>
        </section>
        <section class="col-md-12 pad-top" >
            <section class="navbar navbar-inverse navbar-right">
                <ul class="nav navbar-nav" >
                    <li><a href="#update_profile" alt="" data-toggle="tab" >Update profile</a></li>
                    <li><a href="#change_password" alt="" data-toggle="tab" >Change password</a></li>
                </ul>
            </section>
        </section>
        <section class="col-md-12" >
            <div class="tab-content">
                <div id="change_password" class="tab-pane fade in">
                    <h3>Change password</h3>
                    <?php include_once "changepassword.php"; ?>
                </div>
                <div id="update_profile" class="tab-pane fade in">
                    <h3>Update profile</h3>
                    <?php include "update.php"; ?>
                </div>
            </div>
        </section>
    </section>
</section>