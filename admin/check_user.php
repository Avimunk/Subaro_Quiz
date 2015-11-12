<?php
    require_once('functions.php');
    if(
        $_POST['email']
    ){
        $item = R::dispense( 'users' );
        $exist = R::getRow('select id from users where email = "'.$_POST['email'].'"');
        if($exist['id'] > 0){
            echo 1;

        }else{
            echo 'not exist';
        }
    }else{
        die('close');
    }
