<?php
    require_once('functions.php');
    $item = R::dispense( 'questions' );
    $item->name = $_POST['name'];
    $item->test = $_POST['test'];
    $id = R::store( $item );
    header('Location: ' . $_SERVER['HTTP_REFERER']);