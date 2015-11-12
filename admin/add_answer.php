<?php
    require_once('functions.php');
    $item = R::dispense( 'answers' );
    $item->name = $_POST['name'];
    $item->question = $_POST['question'];
    $item->points = $_POST['points'];
    $id = R::store( $item );
    header('Location: ' . $_SERVER['HTTP_REFERER']);