<?php 
require_once "core/init.php";
$db = new Database();

if(isset($_GET['queue'])){
    $queue = $_GET['queue'];
    $file = $_GET['file'];

    $db->insert("queues",array(
        "visit_Id" => $file,
        "queue_atendant_group" => $queue,
        "queue_response" => "pending"
    ));
}

?>