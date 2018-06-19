<?php


if(Input::exists()){
    
    if(Token::check(Input::get('token'))){
    $validate = new Validate();
    $validation = $validate->check($_POST, array(
        "med_id" => array( "required" => true),
        "med_name" => array( "required" => true),
        "med_type" => array( "required" => true),
        "med_color" => array( "required" => true),
        "med_cost" => array( "required" => true),
        "med_status" => array( "required" => true)
    ));

    
    if($validate->passed()){

        try{
            $db = new Database();

            $db->update("medication", "med_id", Input::get("med_id"), array(
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


if(isset($_GET['id'])){
    $db = new Database();
    $result = $db->get("medication", array("med_id","=",$_GET['id']));
    $medication = $result->first();
    
    ?>
        <form class="form" method="post">
            <input type="hidden" name="med_id" value="<?php echo $medication->med_id; ?>" >
            <section class="form-group">
                <label>Name</label>
                <input type="text" name="med_name" value="<?php echo $medication->med_name; ?>" class="form-control" >
            </section>
            <section class="form-group">
                <label>Type</label>
                <select name="med_type" value="<?php echo $medication->med_type; ?>" class="form-control" >
                    <option></option>
                    <option value="tablet" >Tablet</option>
                    <option value="ointment" >Ointment</option>
                    <option value="injectable" >Injectable</option>
                    <option value="oral liguid" >Oral liguid</option>
                </section>
            </section>
            <section class="form-group">
                <label>Colour</label>
                <input type="color" value="<?php echo $medication->med_color; ?>" name="med_color" >
            </section>
            <section class="form-group">
                <label>Price @</label>
                <input type="number" value="<?php echo $medication->med_cost; ?>" name="med_cost" class="form-control" >
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
    <?php
}
?>