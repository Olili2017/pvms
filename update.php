<?php
    require_once 'core/init.php';
    $userId = null;
    if(isset($_GET['id'])){
        $userId = $_GET['id'];
    }
    $user = new User();

    if(!$user->isLoggedIn()){
        Redirect::to('index.php');
    }

    if(isset($_GET['delete'])){

        $user->delete(array("user_Id","=",$_GET['delete']));
        Redirect::to('index.php');
    }

    $user->find($userId);

    if(Input::exists()){
        if(Token::check(Input::get('token'))){
            $validate = new Validate();
            $validation= $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                    'min' => 2,
                    'max' => 50
                )
                ));

                if($validate->passed()){
                    try{
                        $user->update(array(
                            'user_alias' => Input::get('name')
                        ));

                        Redirect::to('index.php');
                    }catch(Exception $ex){
                        die($ex->getMessage());
                    }
                }else{
                    foreach($validate->errors() as $error){
                        echo $error." <br />";
                    }
                }
        }
    }
?>

<form action="" method="post" >
    <div class="form-group">
        <label for="name" >Name</label>
        <input class="form-control" type="text" name="name" value="<?php echo escape($user->data()->user_alias); ?>">
    </div>
    <div class="form-group" >
        <input type="submit" class="btn btn-sm bg-theme-light" value="Update" >
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" >
    </div>
</form>