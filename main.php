                <section class="col-md-8" >
                <ul class="nav nav-tabs">
                  <li class="active"><a data-toggle="tab" href="#home">Home</a></li>
                  <li><a data-toggle="tab" href="#my_records">My records</a></li>
              <?php if(!$user->belongs_to_group("administrator")){ ?><li><a data-toggle="tab" href="#queues">My Queue <i id="Que_density" class="badge bg-danger">0</i></a></li><?php } ?>
                </ul>

<div class="tab-content">
  <div id="home" class="tab-pane fade in active">
    <div>
                    <?php 
                        $db = new Database();
                        $patient = new Patient();
                        
                        $table_active_visit = $db->get("visits",array("visitStatus","=","started"));
                        
                        if($table_active_visit->count()){
                          ?>
                            <h3>Active Visits</h3>
                            <div class="table-responsive table-bordered">
                              <table class="table" >
                                <thead class="table-header" >
                                  <tr>
                                    <th>Name</th>
                                    <th>Handler</th>
                                    <th>Time spent</th>
                                    <th>Note</th>
                                  </tr>
                                </thead>
                                <tbody>
                            <?php
                            foreach($table_active_visit->results() as $active){
                              $user->find($active->doctor_Id);
                              $patient->find($active->patient_Id);
                              $time_spent = intval((intval(time()) - intval($active->startMiscroTime))/86400);
                              echo "<tr><td>{$patient->data()->patient_name}</td><td>Dr. {$user->data()->user_fname} {$user->data()->user_lname}</td><td>${time_spent} day(s)</td><td>{$active->visitNotes}</td></tr>";
                            }
                            ?>
                              </tbody>
                            </table>
                          </div>
                          <?php
                        }
                        
                        $table_terminated_visit = $db->get("visits",array("visitStatus","=","terminated"));

                        if($table_terminated_visit->count()){
                          ?>
                            <h3>Recently Ended visits</h3>
                            <div class="table-responsive table-bordered">
                              <table class="table" >
                                <thead class="table-header" >
                                  <tr>
                                    <th>Name</th>
                                    <th>Handler</th>
                                    <th>Time spent</th>
                                    <th>Note</th>
                                  </tr>
                                </thead>
                                <tbody>
                            <?php
                            foreach($table_terminated_visit->results() as $terminate){
                              $user->find($terminate->doctor_Id);
                              $patient->find($terminate->patient_Id);
                              $time_spent = intval((intval($terminate->endMicroTime) - intval($terminate->startMiscroTime))/86400);
                              echo "<tr><td>{$patient->data()->patient_name}</td><td>Dr. {$user->data()->user_fname} {$user->data()->user_lname}</td><td>${time_spent} day(s)</td><td>{$terminate->visitNotes}</td></tr>";
                            }
                            ?>
                              </tbody>
                            </table>
                          </div>
                          <?php
                        }
                    ?></div>
  </div>
  <div id="my_records" class="tab-pane fade">
    <h3>My Activity</h3>
    <p>Some content on my records.</p>
  </div>
  <div id="queues" class="tab-pane fade">
    <h3>Que</h3>
    <?php ?>
    <div id="my_que" class="container-fluid" >
    <!-- my que here ... -->
    </div>
    <?php ?>
  </div>
</div>
                </section>
                <section style="border-left: 1px solid #cdcdcd;height: 630px;" class="col-md-4" >
                    <div class="panel-group" id="accordion">
                      <div class="panel">
                        <div class="panel-heading bg-theme" >
                          <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#queues">Queues</a>
                          </h4>
                        </div>
                        <div id="queues" class="panel-collapse collapse in">
                          <div class="panel-body">
                            <ul id="queCount" class="list-group">
                              <!-- take the queues in the main page -->
                            </ul>
                            <?php if($user->has_permission("display_queue")) echo '<a id="extend_queue" class="btn btn-sm btn-primary pull-right" href="?queue" alt="extend to screen" >Extend to screen</a>'; ?>
                            
                          </div>
                        </div>
                      </div>
                      <div class="panel">
                        <div class="panel-heading bg-theme">
                          <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#laboratory">Laboratory results</a>
                          </h4>
                        </div>
                        <div id="laboratory" class="panel-collapse collapse in">
                          <div class="panel-body">
                            <div class="panel" >
                              <section id="lab_count_stats" class="text-theme">
                                <!-- lab count stats -->
                              </section>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php //echo Database::getInstance()->query("SELECT roles FROM user_roles"); ?>
                </section>
                <script>
                  window.addEventListener("load",setInterval(() => {getQueDensity(); getLabCountStats(); viewMyQue(); },2000));
                   function getQueDensity() {
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
              }
                   function getLabCountStats() {
                      if (window.XMLHttpRequest) {
                          // code for IE7+, Firefox, Chrome, Opera, Safari
                          xmlhttpL = new XMLHttpRequest();
                      } else {
                          // code for IE6, IE5
                          xmlhttpL = new ActiveXObject("Microsoft.XMLHTTP");
                      }
                      xmlhttpL.onreadystatechange = function() {
                          if (xmlhttpL.readyState == 4 && xmlhttpL.status == 200) {
                              document.getElementById("lab_count_stats").innerHTML = xmlhttpL.responseText;
                            return true;
                          }
                      };
                      xmlhttpL.open("GET","fetch_que.php?count=laboratory",true);
                      xmlhttpL.send();

                      return false;
              }

              function viewMyQue() {
                if (window.XMLHttpRequest) {
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttpMY = new XMLHttpRequest();
                } else {
                    // code for IE6, IE5
                    xmlhttpMY = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttpMY.onreadystatechange = function() {
                    if (xmlhttpMY.readyState == 4 && xmlhttpMY.status == 200) {
                        document.getElementById("my_que").innerHTML = xmlhttpMY.responseText;
                        document.getElementById("Que_density").innerText = "<?php $db = new Database(); echo $db->getCount("queues",array("queue_atendant_id","=",$user->data()->user_Id))->first()->count; ?>";
                        
                      return true;
                    }
                };
                xmlhttpMY.open("GET","fetch_que.php?for=<?php echo $user->data()->user_Id; ?>",true);
                xmlhttpMY.send();

                return false;
              }

                </script>