<?php
    require_once('functions.php');
    $item  = R::load( 'tests', $_GET['id']);
    R::trash( $item );
header('Location: ' . $_SERVER['HTTP_REFERER']);