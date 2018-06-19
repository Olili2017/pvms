<div id="reloaded" >
    
		<!-- start preloader -->
		<div class="preloader">
			<div class="sk-spinner sk-spinner-wave">
     	 		<div class="sk-rect1"></div>
       			<div class="sk-rect2"></div>
       			<div class="sk-rect3"></div>
      	 		<div class="sk-rect4"></div>
      			<div class="sk-rect5"></div>
     		</div>
    	</div>
    	<!-- end preloader -->
</div>
<!-- select attendant Modal -->
<div id="selectAtendantModal" class="modal fade" role="dialog">
<div class="modal-dialog">

<!-- Modal content-->
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal">&times;</button>
<h4 class="modal-title">Choose visit host</h4>
</div>
<div class="modal-body">
<button class="btn btn-sm bg-theme-light col-md-12" name="doctor" onclick="addToQue(this.name,document.getElementById('selectAtendantModal').name)" >Doctor</button>
<button class="btn btn-sm bg-theme-light col-md-12" style="margin-top: 4px;" name="pharmacy" onclick="addToQue(this.name,document.getElementById('selectAtendantModal').name)" >Pharmacy</button>
<button class="btn btn-sm bg-theme-light col-md-12" style="margin-top: 4px;" name="laboratory" onclick="addToQue(this.name,document.getElementById('selectAtendantModal').name)" >Laboratory</button>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>

</div>

<script>

        var nofurther = false;
    $(document).ready(
        setInterval(function(){
            loadPatients();
        },500)
    );

    function activePatientLoader(){
        
    }
    function searchPatients(searchTerm){
        nofurther = true;
        if((searchTerm == "") || (searchTerm == null)){
            nofurther = false;
            return;
        }
        
        
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            reloadXmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            reloadXmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        reloadXmlhttp.onreadystatechange = function() {
            if (reloadXmlhttp.readyState == 4 && reloadXmlhttp.status == 200) {
                document.getElementById("reloaded").innerHTML = reloadXmlhttp.responseText;
            }
        };
        reloadXmlhttp.open("GET","get_patients.php?search="+searchTerm,true);
        reloadXmlhttp.send();
    }
      
    var lowerLimit = 0;

    function loadPatients(){
        
        if(nofurther){
            return;
        }

        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            reloadXmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            reloadXmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        reloadXmlhttp.onreadystatechange = function() {
            if (reloadXmlhttp.readyState == 4 && reloadXmlhttp.status == 200) {
                document.getElementById("reloaded").innerHTML = reloadXmlhttp.responseText;
            }
        };
        reloadXmlhttp.open("GET","get_patients.php?lower_limit="+lowerLimit,true);
        reloadXmlhttp.send();
    }

    function addToQue(que,patient){
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttpQue = new XMLHttpRequest();
            xmlhttpStart = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttpQue = new ActiveXObject("Microsoft.XMLHTTP");
            xmlhttpStart = new ActiveXObject("Microsoft.XMLHTTP");
        }
        
        xmlhttpQue.onreadystatechange = function() {
                    if (xmlhttpQue.readyState == 4 && xmlhttpQue.status == 200) {
                        //document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
                        //alert("start visit was successful");
                        $('#selectAtendantModal').modal("hide");
                    }
                };

        xmlhttpStart.onreadystatechange = function() {
            if (xmlhttpStart.readyState == 4 && xmlhttpStart.status == 200) {

                
                xmlhttpQue.open("GET","start_visit.php?file="+patient+"&que="+que,true);
                xmlhttpQue.send();
            }
        };
        xmlhttpStart.open("GET","start_visit.php?file="+patient,true);
        xmlhttpStart.send();
        //alert(que+" ... "+patient);
    }
 function process(file) {
    document.getElementById('selectAtendantModal').name = file;
                $('#selectAtendantModal').modal("show");
        /*if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                //document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
                //alert("start visit was successful");
                document.getElementById('selectAtendantModal').name = file;
                $('#selectAtendantModal').modal("show");
            }
        };
        xmlhttp.open("GET","start_visit.php?file="+file,true);
        xmlhttp.send();*/
}
 function reprocess(patient) {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                //document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
                //alert("start visit was successful");
                document.getElementById('selectAtendantModal').name = patient;
                $('#selectAtendantModal').modal("show");
            }
        };
        xmlhttp.open("GET","start_visit.php?patient="+patient,true);
        xmlhttp.send();
}

    
function endVisit(file){
    if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttpEndVisit = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttpEndVisit = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttpEndVisit.onreadystatechange = function() {
            if (xmlhttpEndVisit.readyState == 4 && xmlhttpEndVisit.status == 200) {
                notification("Visit ended successfully!!");
            }
        };
        xmlhttpEndVisit.open("GET","start_visit.php?end=true&file="+file,true);
        xmlhttpEndVisit.send();
    }

</script>
