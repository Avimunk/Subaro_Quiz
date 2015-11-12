<?php
    require_once('functions.php');
    if(
        $_POST['email']
    ){
        $item = R::dispense( 'users' );
        $exist = R::getRow('select id from users where email = "'.$_POST['email'].'"');
        if($exist['id'] > 0){
            echo 'exist';

        }else{
            $item = R::dispense( 'users' );
            $item->name = $_POST['name'];
            $item->phone = $_POST['phone'];
            $item->email = $_POST['email'];
            $id = R::store( $item );
            echo $_POST['email'];
        }
    }else{
        die('close');
    }
