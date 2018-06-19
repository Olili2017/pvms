
<?php 
    require('core/init.php');
    $db = new Database();

    if(isset($_GET["q"])){
        $result = $db->search("medication","med_name",$_GET["q"]);
        if($result->count()){
            ?>
            
            <div class="table-responsive table-bordered">
                        <table class="table" >
                            <thead class="table-header" >
                                <tr>
                                    <th>Drug name</th>
                                    <th>Type</th>
                                    <th>Units in Stock</th>
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
                    <td><?php echo $item->med_type; ?></td>
                    <td><?php echo ucfirst($item->med_status); ?></td>
                    <td>
                        <a href="index.php?page=med_edit&id=<?php echo $item->med_id; ?>" class="btn btn-sm btn-primary" >Edit</a>
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
</script>