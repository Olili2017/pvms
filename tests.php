<?php
    $db = new Database();
    $dba = new Database();

    if(Input::exists()){
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            "name" => array(
                "required" => true
            )
        ));

        
        if($validate->passed()){
            $db->insert("laboratory_test",array(
                "test_name" => Input::get("name"),
                "test_description" => Input::get("description"),
                "test_cost" => Input::get("cost")
            ));
        }
    }
    $tests = $db->query("SELECT * FROM laboratory_test LIMIT ".(!isset($_GET['jumpto'])?0:$_GET['jumpto']).",10");
    

    if(isset($_GET['jumpto'])){
        $lower_limit = $_GET['jumpto'];
    }else{
        $lower_limit = 0;
    }

    

    $output = <<<HTML
    <section class="col-md-12" >
        <button class="btn btn-sm btn-success pull-right" data-target="#addTestsModal" data-toggle="modal"  ><i class="glyphicon glyphicon-plus" ></i>Add Test</button>
    </section>
    <table class="table col-md-12" >
        <thead class="table-header" >
            <tr>
                <th>NAME</th>
                <th>DESCRIPTION</th>
                <th>COST</th>
            </tr>
        </thead>
        <tbody>
HTML;

    if(count($tests)){
        foreach($tests->results() as $test){
            $output .= "<tr><td>{$test->test_name}</td><td>{$test->test_description}</td><td>{$test->test_cost}</td></tr>";
        }

        $output .= <<<HTML
        </tbody>
        </table>
HTML;

        echo $output;
    }else{
        echo "no tests yet";
    }



?>

<div id="addTestsModal" class="modal fade" role="modal-dialog" >
    <div class="modal-dialog" >
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add new Laboratory test</h4>
        </div>
        <div class="modal-body">
            <form method="post" class="form" >
                <section class="form-group">
                    <label>Name</label>
                    <input class="form-control" type="text" name="name" placeholder="test name"  >
                </section>
                <section class="form-group">
                    <label>Description</label>
                    <input class="form-control" type="text" name="description" placeholder="test description"  >
                </section>
                <section class="form-group">
                    <label>Unit cost</label>
                    <input class="form-control" type="text" name="cost" placeholder="Cost"  >
                </section>
                <input type='submit' value="ADD TEST" class="btn btn-sm bg-theme" >
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
</div>
<?php 

$page_count = ceil(count($dba->query("SELECT * FROM laboratory_test")->results()) / 10);
 ?>
<div class="container-fluid col-md-12 table-header footer-pager" >
    <div class="col-md-2" >
        <ul class="pagination" >Showing <?php echo count($tests->results()); ?> results of <?php echo count($dba->query("SELECT * FROM laboratory_test")->results()); ?></ul>
    </div>
    <div class="col-md-8" >
        <ul class="pagination">
            <?php 
                if($lower_limit){
                    //$active = 'active';
                    if($lower_limit == 0){
                        $default_active = 'active';
                        $target = null;
                        $active = null;
                    }else{
                        $target = ceil(($lower_limit + 10) / 10);
                        //die($target);
                        $default_active = null;
                        $active = null;
                    }
                }else{
                    $target = null;
                    $default_active = 'active';
                    $active = 'active';
                } for($i = 1; $i <= $page_count; $i ++){
                    if($i == $target){ $active = 'active'; }
                echo "<li class='${default_active} ${active}' ><a href='?page=tests&jumpto=".(($i * 10) - 10)."' >${i}</a></li>";
                $default_active = null;
                $active = null;
            } ?>
        </ul>
    </div>
    <div class="col-md-2 pull-right">
        <ul class="pager">
            <li class="previous"><a href="?page=tests&jumpto=<?php echo $lower_limit - 10; ?>" >Previous</a></li>
            <li class="next"><a href="?page=tests&jumpto=<?php echo $lower_limit + 10; ?>" >Next</a></li>
        </ul>
    </div>
</div>