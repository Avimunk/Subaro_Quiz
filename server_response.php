<?php
error_reporting(0);


print_r($_REQUEST);

require_once('admin/functions.php');


$name = $_REQUEST['name'];
$email = $_REQUEST['email'];
$phone = $_REQUEST['phone'];
$message_post = $_REQUEST['message'];
$results = $_REQUEST['results'];
$score_table = '<table width="100%" border="1" cellspacing="0" cellpadding="0">';
$score_table .= '<tr><th style="text-align:center; padding: 5px;"><strong>ID</strong></th><th style="text-align:center; padding: 5px;"><strong>Question</strong></th><th style="text-align:center; padding: 5px;"><strong>User\'s Answer</strong></th><th style="text-align:right; padding: 5px;"><strong>Score</strong></th></tr>';




$user = R::getRow('select id from users where email = "'.$_REQUEST['userEmail'].'"');
foreach ($results as $result) {
    print 'select id from answers where name = "'.html_entity_decode($result['answer']).'" <br>';
    $theAnswer = R::getRow('select id, question from answers where name = \''.html_entity_decode($result['answer']).'\'');
    if($theAnswer['id'] > 0){

    }else{
        $theAnswer = R::getRow('select id, question from answers where name = "'.html_entity_decode($result['answer']).'"');

    }

    $answer = R::dispense('useranswers');
    $answer->user = $user['id'];
    $answer->question = $theAnswer['question'];
    $answer->answer = $theAnswer['id'];
    $answer->points = $result['score'];
    $answer->test = $_REQUEST['test'];
    $id = R::store($answer);
}

$item = R::dispense('results');
$item->user = $user['id'];
$item->points = $_REQUEST['user_score'];
$item->test = $_REQUEST['test'];
$item->total = $_REQUEST['total'];
$id = R::store($item);



$lastStarted = R::getRow('select id from testsstarted where user = "'.$user['id'].'" and test = '.$_REQUEST['test'].' order by the_time DESC LIMIT 1');
$tests_started = R::load('testsstarted', $lastStarted['id']);
$tests_started->user = $user['id'];
$tests_started->test = $_REQUEST['test'];
$tests_started->time_end = date("Y-m-d H:i:s",time());
$tests_startedId = R::store( $tests_started );
?>
