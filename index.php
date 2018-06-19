<?php

/**
 *  Patient management system
 * 
 * @author Olili daniel
 * @copyright 2014-2018 PidScrypt Inc
 * @package Final_year
 * @version v1.0
 * @since 2014
 */
 /** define system required files */
 require_once "core/init.php";

/**
 * defines system current state
 * @param development
 * @param production
 * @param testing
 * PHP will display errors based on what statge of the project you are
 */

DEFINE("ENVIRONMENT","development");

if(defined("ENVIRONMENT")){
    switch (ENVIRONMENT) {
        case 'development':
            error_reporting(E_ALL);
            break;
        case "testing":
            case "production":
                error_reporting(0);
        default:
            break;
    }
}
//$user = Database::getInstance()->get("user_roles", array("can_issue_bill", "=", 1));
/**
 * initialising system variables
 */
$user = new User();

if(!$user->isLoggedIn()){
    Redirect::to('login.php');
}

if($user->belongs_to_group('administrator')){
    $horizontal_menu_items = array(
    );
    $verticle_menu_items = array(
        "Users" => "index.php?page=users",
        "Drugs" => "index.php?page=meds",
        "Tests" => "index.php?page=tests",
        "Queues" => "index.php?page=queues",
        "Reports" => "index.php?page=report"
    );
}else if($user->belongs_to_group('doctor')){
    $horizontal_menu_items = array(
    );
    $verticle_menu_items = array(
        "My Queue" => "index.php",
        "Lab Que" => "index.php?check=true"
    );
}else if($user->belongs_to_group('receptionist')){
    $horizontal_menu_items = array(
    );
    $verticle_menu_items = array(
        "Patients" => "index.php?page=patients_view",
        "Queues" => "javascript:void(0)",
        "Doctor`s Que" => "index.php?page=check&group=doctor",
        "Lab Que" => "index.php?page=check&group=laboratory"
    );
}else if($user->belongs_to_group('pharmacist')){
    $horizontal_menu_items = array(
    );
    $verticle_menu_items = array(
        "Queue" => "index.php",
        "Drugs" => "index.php?page=meds"
    );
}else if($user->belongs_to_group('laboratory')){
    $horizontal_menu_items = array(
    );
    $verticle_menu_items = array(
        "My Queue" => "index.php",
        "Lab Tests" => "index.php?page=tests"
    );
}

//$horizontal_menu = Menu::horizontal_menu($horizontal_menu_items);
//$verticle_menu = Menu::verticle_menu($items);

// end system variables


// PHTML CODE TO SERVE TO CLIENT ON REQUEST
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo Config::get('system/name'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/main.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/side-bar.css" />
    <style>
        .wrapper {
            display: flex;
            align-items: stretch;
        } 
        .queue {
            border: 1px ridge #999;
            float: left;
            clear: both;
            background-color: #ffffff;
            position: fixed;
            z-index: 99999;
            animation: expand forwards;
            animation-duration: 1s;
            display: block;
        }

        @keyframes expand {
            0% {
            width: 0%;
            height: 0%;
            margin: 50%;
            }
            100% {
            width: 100%;
            height: 100%;
            margin: 0%;
            }
        }

        /* Style the tab */
.tab {
    float: left;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
    width: 30%;
    height: 300px;
}

/* Style the buttons that are used to open the tab content */
.tab button {
    display: block;
    background-color: inherit;
    color: black;
    padding: 22px 16px;
    width: 100%;
    border: none;
    outline: none;
    text-align: left;
    cursor: pointer;
    transition: 0.3s;
}

/* Change background color of buttons on hover */
.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current "tab button" class */
.tab button.active {
    background-color: #ccc;
}

    </style>

    <script src="assets/js/jquery.min.js" ></script>
    <script src="assets/js/bootstrap.min.js" ></script>
    <script src="assets/js/Chart.min.js" ></script>
    <script src="assets/js/wow.js" ></script>
    <script src="assets/js/main.js" ></script>

</head>
<body>
        <!-- the queue screen -->
        <?php if(isset($_GET["queue"])){
            include_once "queue.php";
        } ?>
        <!-- end queue screen -->
    <section class="bg-theme header-wrapper container-fluid" >
        <span class="font-large col-sm-8 col-md-8 col-lg-6 pad-top text-no-deco text-theme btn-link "><a href="index.php" ><?php echo ucfirst(Config::get('system/name')); ?></a></span>
        <section class="pull-right pad-top" >
            <div class="dropdown">
                <button style="background: transparent; padding: 0px" class="dropdown-toggle img img-circle" type="button" data-toggle="dropdown">
                    <img class="img img-circle" width="30" src="assets/images/users/<?php echo $user->userImage(); ?>" alt="user_ico" title="click to view options" />
                </button>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" ><span><?php echo $user->data()->user_fname." ".$user->data()->user_lname; ?>&nbsp;&nbsp;<i class="glyphicon glyphicon-menu-down"></i></span></a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li class="dropdown-header">
                        <a href="?page=profile&user=<?php echo escape($user->data()->user_alias); ?>" alt="" ><img src="assets/images/users/<?php echo $user->userImage(); ?>" class="img img-circle pad-lg" width="60"  alt=""/></a>
                        <!--a href="?page=profile&user=<?php //echo escape($user->data()->user_alias); ?>" alt="" ><span class="text-theme-light" ><strong><?php //echo $user->get_user_name(); ?></strong></span></a-->
                    </li>
                    <li class="divider"></li>
                    <!--li><a href='logout.php'><span class='glyphicon glyphicon-log-out'></span> Logout</a></li-->
                    <li><a href='?page=profile_update'><span class='glyphicon glyphicon-user text-theme'></span> Update profile</a></li>
                    <!--li><a href='logout.php'><span class='glyphicon glyphicon-cog'></span> Settings</a></li-->
                    <li class="divider"></li>
                    <li><a href='logout.php'><span class='glyphicon glyphicon-log-out text-theme'></span> Logout</a></li>
                </ul>
            </div>
        </section>
    </section>

    <!-- start content -->



    <?php echo Menu::horizontal_menu($horizontal_menu_items); ?>
    <div id="wrapper" class="container-fluid bg-theme-pale toggled" >

                <!-- Sidebar -->
                <div id="sidebar-wrapper" class="bg-theme" >
                  <?php 
                  echo Menu::verticle_menu($verticle_menu_items); 
                  ?>
                    <div id="queCount" class="sidebar-nav" style="top: 50%; left: 1em;" >
                        <h3 class="" style="color: #666;"  >All Queues</h3>
                        <p class="" style="color: #666;" >Doctor <span class="badge" >0</span></p>
                        <p class="" style="color: #666;" >Pharmacy <span class="badge" >0</span></p>
                        <p class="" style="color: #666;" >Laboratory <span class="badge" >0</span></p>
                        <p class="" style="color: #666;" ><button class="btn btn-sm bg-theme">Extend to External Display</button></p>
                              <!-- take the queues in the main page -->
                    </div>
                </div>
        <!-- /#sidebar-wrapper -->
        <div id="page-content-wrapper" >
        
        <div id="content-main" class="col-md-12" >
            <?php include_once "content.php"; ?>
        </div>
    </div>
    </div>


    <!-- quick note -->
        <div id="msg" style="display: none; padding: 1em; position: fixed; bottom: 2em; right: 20px; width: 300px; border: 2px solid rgb(0,255,0); background-color: rgba(0,200,0,0.5); color: #fff; font-weight: 600;" >
            <span id="msg_content" >Something here ...<span>
        </div>
    <!-- end quick note-->

     <!-- Bootstrap core JavaScript -->
     <!--script src="vendor/jquery/jquery.min.js"></script-->

    <!-- Menu Toggle Script -->
    <script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    </script>

    <script>
         (function(){
                setInterval(
                   function(){
                      if (window.XMLHttpRequest) {
                          // code for IE7+, Firefox, Chrome, Opera, Safari
                          xmlhttpQ = new XMLHttpRequest();
                      } else {
                          // code for IE6, IE5
                          xmlhttpQ = new ActiveXObject("Microsoft.XMLHTTP");
                      }
                      xmlhttpQ.onreadystatechange = function() {
                          if (xmlhttpQ.readyState == 4 && xmlhttpQ.status == 200) {
                              document.getElementById("queCount").innerHTML = xmlhttpQ.responseText;
                            return true;
                          }
                      };
                      xmlhttpQ.open("GET","fetch_que.php?count=all",true);
                      xmlhttpQ.send();

                      return false;
              }, 2000);
            })();
    </script>
</body>
</html>
