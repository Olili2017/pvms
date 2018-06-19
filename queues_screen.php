<link rel="stylesheet" href="assets/css/bootstrap.min.css" >
<link rel="stylesheet" href="assets/css/main.css" >
<section class="container-fluid" >
    hello
</section>
<section id="queuea" class="container-fluid"  >
    <section id="doctor" class="col-md-4" >f</section>
    <section id="laboratory" class="col-md-4" >d</section>
    <section id="pharmacy" class="col-md-4" >d</section>
</section>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js" ></script>
<script>
    setInterval(function(){
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttpQ = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttpQ = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttpQ.onreadystatechange = function() {
            if (xmlhttpQ.readyState == 4 && xmlhttpQ.status == 200) {
                document.getElementById("doctor").innerHTML = xmlhttpQ.responseText;
            return true;
            }
        };
        xmlhttpQ.open("GET","get_queues.php?group=doctor&for=doctor",true);
        xmlhttpQ.send();

    },2000);
</script>