<?php

if(isset($_GET["page"])){
    switch ($_GET["page"]) {
        case 'add_patient':
            include_once("addPatient.php");
            break;
        case 'profile_update':
            include_once("update.php");
            break;
        case 'profile':
            include_once("profile.php");
            break;
        case 'patients_view':
            include_once("patients.php");
            break;
        case 'add_staff':
            include_once("register.php");
            break;
        case 'admit':
            include_once("admitPatient.php");
            break;
        case 'patient_file':
            include('patient.php');
            break;
        case 'queue':
            include('fetch_que.php');
            break;
        case 'users':
            /*if(isset($_GET["r"])){
                $id = $_GET["r"];
                $position = $_GET["t"];
            }
            echo "<iframe style='height: 430px; border: 0px;' class='col-md-12 card-form-form' src='users.php?r={$id}&t={$position}' ></iframe>";
            */include('users.php');
            break;
        case 'report':
            include('report.php');
            break;
        case 'meds':
            include('medication.php');
            break;
        case 'med_edit':
            include("edit_med.php");
            break;
        case 'tests':
            include("tests.php");
            break;
        case 'check':
            include("get_queues.php");
            break;
        default:
            //header('HTTP/1.0 404 Not Found');
            include_once('includes/errors/404.php');
            break;
    }
}else{
    //require_once "core/init.php";
    $user = new User();
    if($user->belongs_to_group("administrator")){
        include_once "report.php";
    }else if($user->belongs_to_group("receptionist")){
        include_once "patients.php";
    }else if($user->belongs_to_group("doctor")){
        include_once "get_queues.php";
    }else if($user->belongs_to_group("pharmacist")){
        include_once "get_queues.php";
    }else if($user->belongs_to_group("laboratory")){
        include_once "get_queues.php";
    }
    //include_once("main.php");
}
?>