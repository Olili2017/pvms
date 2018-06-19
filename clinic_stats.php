<?php 
    require_once("core/init.php");
    $db = new Database();
?>
<div class="col-md-7 card" >
    <h3 class="text-theme" >Department Daily reception average</h3>
    <canvas id="line" style="max-height: 400px;"></canvas>
</div>
<div class="col-md-4 card" >
    <h3 class="text-theme" >Patient density avarages</h3>
    <canvas id="density" width="400" height="400"></canvas>
</div>
<div class="col-md-12" >
    <h3 class="text-theme">Performing Staff</h3>
    <section class="col-md-3 card" >
        <img src="assets/images/users/default.png" alt="" class="img img-circle col-md-4" />
        <div class="col-md-8" >
            <h4>Alex Miiru</h4>
            <div><i>malex@pvms.com</i></div>
            <div class="text-theme" ><b>Doctor</b></div>
        </div>
    </section>
    <section class="col-md-3 card" >
        <img src="assets/images/users/default.png" alt="" class="img img-circle col-md-4" />
        <div class="col-md-8" >
            <h4>Maria klein</h4>
            <div><i>kmaria@pvms.com</i></div>
            <div class="text-theme" ><b>Laboratory</b></div>
        </div>
    </section>
    <section class="col-md-3 card" >
        <img src="assets/images/users/default.png" alt="" class="img img-circle col-md-4" />
        <div class="col-md-8" >
            <h4>Scovia Blain</h4>
            <div><i>bscovia@pvms.com</i></div>
            <div class="text-theme" ><b>Reception</b></div>
        </div>
    </section>
</div>
<script>
    const DOUGHNUT = document.getElementById("density");
    const LINE = document.getElementById("line");

    Chart.defaults.scale.ticks.beginAtZero = true;

    let dough = new Chart(DOUGHNUT,{
        type: 'doughnut',
        data: {
            labels: ['Influx','Recovered','Deaths','Referals'],
            datasets: [
                {
                    label: 'Patient density avarages',
                    backgroundColor: ['#f1c40f','#e67e22','#ff0000','#2980b9'],
                    data: [189,151,9,39]
                }
            ]
        },
        options: {
            cutoutPercentage: 30,
            animation: {
                animateScale: true
            }
        }
    });

    let line = new Chart(LINE,{
        type:'bar',
        data: {
            labels: ['pediatrics','gynachology','anti-natal','dental','opthulmology'],
            datasets: [
                {
                    label: 'Points',
                    backgroundColor: ['blue','red','pink','yellow','aqua'],
                    data: [1,3,5,8,1]
                }
            ]
        },
        options: {
            
        }
    });
</script>