<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
<link rel="stylesheet" href="assets/css/main.css" />
<?php
    require_once "core/init.php";
    $user = new User();

    if(isset($_GET['r'])){
        echo $user->tabulate(ucfirst($_GET['t']),array("user_role","=",intval($_GET['r'])));
    }else{
        echo $user->tabulate("Users");
    }
?>