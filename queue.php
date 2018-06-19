
<?php
require_once "core/init.php";

$user = new User();

if(!$user->has_permission("display_queue")){
    die("you are not a queue manager");
}

?>

<section class="container-fluid one queue" id="queue" >
    <a id="close_queue" href="#" class="close">&times;</a>
    <div class="container-fluid">
        <div id="output" class="col-md-12"></div>
        <section class="col-md-4" >
            <header><h1>Doctor Que</h1></header>
            <section class="col-md-12" id="doctor">
            </section>
        </section>
        <section class="col-md-4" >
            <header><h1>Laboratory Que</h1></header>
            <section class="col-md-12" id="laboratory">

            </section>
        </section>
        <section class="col-md-4" >
            <header><h1>Pharmacy Que</h1></header>
            <section class="col-md-12" id="pharmacy">

            </section>
        </section>
    </div>
</section>
<script>

    window.addEventListener("load",function(){
        setInterval(function(){
            fetchDoctorQue();
            fetchLaboratoryQue();
            fetchPharmacyQue();
        },2000);
    });
 function fetchDoctorQue() {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("doctor").innerHTML = xmlhttp.responseText;
               
               return true;
            }
        };
        xmlhttp.open("GET","fetch_que.php?type=doctor",true);
        xmlhttp.send();

        return false;
}

 function fetchPharmacyQue() {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp_P = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp_P = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp_P.onreadystatechange = function() {
            if (xmlhttp_P.readyState == 4 && xmlhttp_P.status == 200) {
                document.getElementById("pharmacy").innerHTML = xmlhttp_P.responseText;
                //alert("start visit was successful");
                /** 
                 * @TODO: reload page here
                 * 
                 * */
            }
        };
        xmlhttp_P.open("GET","fetch_que.php?type=pharm",true);
        xmlhttp_P.send();
}

 function fetchLaboratoryQue() {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp_L = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp_L = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp_L.onreadystatechange = function() {
            if (xmlhttp_L.readyState == 4 && xmlhttp_L.status == 200) {
                document.getElementById("laboratory").innerHTML = xmlhttp_L.responseText;
                
                return true;
            }
        };
        xmlhttp_L.open("GET","fetch_que.php?type=lab",true);
        xmlhttp_L.send();
    return false;
}

</script>