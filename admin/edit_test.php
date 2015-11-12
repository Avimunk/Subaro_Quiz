<?php
    require_once('functions.php');
    $item  = R::load( 'tests', $_POST['id']);
    $item->name = $_POST['name'];
    $id = R::store( $item );
header('Location: ' . $_SERVER['HTTP_REFERER']);