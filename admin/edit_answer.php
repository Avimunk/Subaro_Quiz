<?php
    require_once('functions.php');
    $item  = R::load( 'answers', $_POST['id']);
    $item->name = $_POST['name'];
    $item->points = $_POST['points'];
    $id = R::store( $item );
    header('Location: ' . $_SERVER['HTTP_REFERER']);