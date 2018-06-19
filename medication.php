<?php

require_once("core/init.php");

if(Input::exists()){
    
    if(Token::check(Input::get('token'))){
    $validate = new Validate();
    $validation = $validate->check($_POST, array(
        "med_name" => array( "required" => true),
        "med_type" => array( "required" => true),
        "med_color" => array( "required" => true),
        "med_cost" => array( "required" => true),
        "med_status" => array( "required" => true)
    ));

    
    if($validate->passed()){

        try{
            $db = new Database();

            $db->insert("medication", array(
                "med_name" => Input::get("med_name"),
                "med_type" => Input::get("med_type"),
                "med_color" => Input::get("med_color"),
                "med_cost" => Input::get("med_cost"),
                "med_status" => Input::get("med_status"),
            ));

        }catch(Exception $ex){
            die($ex->getMessage());
        }
    }else{
        foreach($validate->errors() as $error){
            echo $error ."<br />";
        }
}
}
}

?>
<section class="col-md-12" >
    <section class="col-md-12">
        <section class="col-md-6" >
            <input  placeholder="Search medicines" class="col-md-6 form-control" type="text" onkeyup="searchMeds(this.value)" name="" id="med_search" />
        </section>
        <section class="col-md-6" >
            <a href="#" class="btn btn-sm bg-theme-light pull-right" name="" data-toggle="modal" data-target="#med_form" ><i class="glyphicon glyphicon-plus" ></i> Add medicine</a>
        </section>
    </sectioin>
</section>

<section id="medication_table" class="col-md-12 pad-top" >
    <!-- takes the meds table -->
</section>

<!-- Modal -->
<div id="med_form" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">New medicine</h4>
      </div>
      <div class="modal-body">
        <form class="form" method="post">
            <section class="form-group">
                <label>Name</label>
                <input type="text" name="med_name" class="form-control" >
            </section>
            <section class="form-group">
                <label>Type</label>
                <select name="med_type" class="form-control" >
                    <option></option>
                    <option value="tablet" >Tablet</option>
                    <option value="ointment" >Ointment</option>
                    <option value="injectable" >Injectable</option>
                    <option value="oral liguid" >Oral liguid</option>
                </section>
            </section>
            <section class="form-group">
                <label>Colour</label>
                <input type="color" name="med_color" >
            </section>
            <section class="form-group">
                <label>Price @</label>
                <input type="number" name="med_cost" class="form-control" >
            </section>
            <section class="form-group">
                <label>Is Sufficient?</label>
                <label class="radio-inline"><input type="radio" name="med_status" value="available" >Yes</label>
                <label class="radio-inline"><input type="radio" name="med_status" value="finished" >No</label>
            </section>
            <section class="form-group" >
                <input type="hidden" name="token" value="<?php echo Token::generate() ?>" >
                <input type="submit" name="submit" value="submit" class="btn btn-sm btn-primary form-control" >
            </section>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<script>
    function searchMeds(str){

    if (window.XMLHttpRequest) {
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttpMeds = new XMLHttpRequest();
                      } else {
                          // code for IE6, IE5
                          xmlhttpMeds = new ActiveXObject("Microsoft.XMLHTTP");
                      }
                      xmlhttpMeds.onreadystatechange = function() {
                          if (xmlhttpMeds.readyState == 4 && xmlhttpMeds.status == 200) {
                              document.getElementById("medication_table").innerHTML = xmlhttpMeds.responseText;
                            return true;
                          }
                      };
                      xmlhttpMeds.open("GET","meds.php?q="+str,true);
                      xmlhttpMeds.send();

                      return false;
    }

    window.addEventListener("load",() => {
        searchMeds(" ");
    });
</script>