<?php
require_once("core/init.php");
//header("Content-Type: text/pdf");
//$db = new Database();
$count_db = new Database();
$count_db_ = new Database();
$count_db__ = new Database();

$most_lab_tests = $count_db->query("SELECT lab_test_id, COUNT(*) AS test_occuranse FROM visit_test GROUP BY lab_test_id ORDER BY test_occuranse DESC LIMIT 5");
//die(var_dump($most_lab_tests));
$tests = "";
if(count($most_lab_tests->results())){
    foreach($most_lab_tests->results() as $ttts){
        $test_name = $count_db_->get("laboratory_test",array("test_Id","=",$ttts->lab_test_id));
        $tests .= "<div class='list-group-item' >".$test_name->first()->test_name."<span><code class='pull-right' >{$ttts->test_occuranse}</code></span></div>";
    }
}
//die();
$test_count = $count_db__->query("SELECT * FROM visit_test");
//$test_name = $test_name->first()->test_name;

$percent = (($most_lab_tests->first()->test_occuranse) / count($test_count->results())) * 100;

?>
<?php 
  //echo "<div class='col-md-10' ><a class='btn btn-sm bg-theme-light pull-right' href='index.php?page=report&print=true' >Print Report</a></div>";
  $output = <<<HTML
  
  
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#traffic">CLINIC TRAFFIC</a></li>
  <li><a data-toggle="tab" href="#time">TIME MANAGEMENT</a></li>
  <li><a data-toggle="tab" href="#results">TESTS AND RESULTS</a></li>
</ul>
<div class="tab-content">
  <div id="traffic" class="tab-pane fade in active">
    <p class="col-md-10" >
    <canvas id="trafficChartNode" class="col-m-12"></canvas>
    </p>
  </div>
  <div id="time" class="tab-pane fade">
    <h3>TIME MANAGEMENT <big><tt>(Mins)</tt></big></h3>
    <div class="col-md-12" >
        <div class="card col-md-3" >
            <div class="card-body" >
                <h1 class="text-center" >15</h1>
            </div>
            <div class="card-foot bg-theme-light" >
                <span>Average waiting time</span>
            </div>
        </div>
        <div class="card col-md-3" >
            <div class="card-body" >
                <h1 class="text-center" >30</h1>
            </div>
            <div class="card-foot bg-theme-light" >
                <span>Average Lab response time</span>
            </div>
        </div>
        <div class="card col-md-3" >
            <div class="card-body" >
                <h1 class="text-center" >567</h1>
            </div>
            <div class="card-foot bg-theme-light" >
                <span>Avarage total visit time</span>
            </div>
        </div>
    </div>
  </div>
  <div id="results" class="tab-pane fade">
  <div class="col-md-4" >      
        <fieldset class="col-md-12" >
            <legend style="font-size: 1.3em;" >MOST REQUESTED TESTS</legend>
            <div class="col-md-12" >
                <div class="list-group" >${tests}</div>
                <!--p><?php //echo $percent; ?>% of all test</p-->
            </div>
        </fieldset>
        <fieldset class="col-md-12" style="margin-top: 2em;" >
            <legend style="font-size: 1.3em;" >LAB VISITS</legend>
            <div class="col-md-12" >
                <h4>Direct to Lab <code>56</code></h4>
                <h4>From doctors <code>76</code></h4>
            </div>
        </fieldset>
</div>
        <fieldset class="col-md-8" >
            <legend>
                <span>TESTS PERFORMED</span>
                &nbsp;
                <select id="period" name="period" class="pull-right" style="font-size: 0.8em;" >
                    <option value="today" >Today</opption>
                    <option value="thisweek" >This week</opption>
                    <option value="thismonth" >This month</opption>
                </select>
                <span class="pull-right" style="font-size: 0.9em; color: #666; margin-right: 1.5em;"  >Filter</span>
            </legend>
            <div id="performedtests" class="col-md-12" >
                 <!-- performed tests -->
            </div>
        </fieldset>
  </div>
</div>

HTML;

echo $output;

  if(isset($_GET["print"])){
    
    require_once "assets/php/dompdf/dompdf_config.inc.php";
    $dompdf = new DOMPDF();
    $dompdf->load_html($output);
    $dompdf->set_paper("a4", "portrait");
    $dompdf->render();
  
    $dompdf->stream("PVMS-".date("Ymd")."-P".rand(0,9999).".pdf",array("Attachment" => 0));
  
  /*
    $tmpfile = tempnam("/tmp", "dompdf_");
  file_put_contents($tmpfile, $output); // Replace $smarty->fetch()
                                                  // with your HTML string
  
  $url = "dompdf.php?input_file=" . rawurlencode($tmpfile) . "&paper=letter&output_file=" . rawurlencode("My Fancy PDF.pdf");
  
  header("Location: http://" . $_SERVER["HTTP_HOST"] . "/projects/restaurant/guest/dompdf/${url}");
  */
  }
  

?>

<script>


var trafficChartNode = document.getElementById("trafficChartNode").getContext('2d');

Chart.defaults.scale.ticks.beginAtZero = false;
var trafficChart = new Chart(trafficChartNode, {
    type: 'line',
    data: {
        //labels: ["7:00 (morning)", "12:00 (afternoon)", "16:00 (evening)", "21:00 (night)"],
        labels: ["7:00","8:00","9:00","10:00","11:00", "12:00","13:00","14:00","15:00", "16:00","17:00","18:00","19:00","20:00", "21:00", "22:00", "22:00", "00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00"],
        datasets: [{
            label: 'Patient influx',
            fill: false,
            lineTension: 0.1,
            data: [100, 78, 12, 56, 34, 59, 66, 86, 23, 87, 12, 98, 20, 56, 40, 6, 12, 20,48,48,53,49,39,45,40],
            backgroundColor: "rgba(130, 99, 255, 0.2)",
            borderColor: "rgba(130, 99, 255, 1)",
            borderCapStyle: 'butt',
            borderDash: [],
            borderDashOffset:0.0,
            borderJoinStyle:'miter',
            borderPointColor:"rgba(130, 99, 255, 1)",
            pointBackgroundColor: "#fff",
            pointBorderWidth: 1,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(130, 99, 255, 1)",
            pointHoverBorderColor: "rgba(130, 99, 255, 1)",
            pointHoverBorderWidth: 2,
            pointRadius: 1,
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                //type: "logarithmic",
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});

$(document).ready(
    function(){
        var performedtests = document.getElementById("performedtests");
        var period = document.getElementById("period");
        //performedtests.innerHTML = "hello world";
        setInterval(function(){
              
                        if (window.XMLHttpRequest) {
                        // code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp_tests = new XMLHttpRequest();
                        } else {
                            // code for IE6, IE5
                            xmlhttp_tests = new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        xmlhttp_tests.onreadystatechange = function() {
                            if (xmlhttp_tests.readyState == 4 && xmlhttp_tests.status == 200) {
                                performedtests.innerHTML = xmlhttp_tests.responseText;
                                return true;
                            }
                        };
                        console.log("tests_performed.php?period="+period.value);
                        xmlhttp_tests.open("GET","tests_performed.php?period="+period.value,true);
                        xmlhttp_tests.send();
        },500);
    }
);

</script>