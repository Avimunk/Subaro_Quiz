<?php
    require_once('functions.php');
    $item = R::dispense( 'tests' );
    $item->name = $_POST['name'];
    $id = R::store( $item );
header('Location: ' . $_SERVER['HTTP_REFERER']);