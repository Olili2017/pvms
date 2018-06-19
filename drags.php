
<?php 
    require('core/init.php');
    $db = new Database();

    if(isset($_GET["q"])){
        $result = $db->search("medication","med_name",$_GET["q"]);
        if($result->count()){
            ?>
            
            <div class="table-responsive table-bordered">
                        <table class="table" >
                            <thead>
                                <tr>
                                    <th>Drug</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
            <?php
            foreach($result->results() as $item){
                ?>
                <tr>
                    <td><?php echo $item->med_name; ?></td>
                    <td><?php echo $item->med_type; ?></td>
                    <td><?php echo ucfirst($item->med_status); ?></td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick='addDrug("<?php echo $item->med_name; ?>","<?php echo $item->med_color; ?>")' ><i class="glypicon glyphicon-plus"></i></button>
                    </td>
                </tr>
                <?php
            };
            ?>

                            </tbody>
                        </table>
                    </div>
            <?php

}else{
            echo "could not find ".strtoupper($_GET["q"]);
        }
    }
?>
<script>


    /*function addDrugToObj(str){
        //pres.drugs += str;
        alert(pres.drugs);
    }*/
</script>