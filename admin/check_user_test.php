<?php
    require_once('functions.php');
    if(
        $_POST['email']
    ){

        $exist = R::getRow('select id from users where email = "'.$_POST['email'].'"');
        $the_time = R::getRow('select the_time from testsstarted where user = "'.$exist['id'].'" and test = '.$_POST['test'].' order by the_time DESC LIMIT 1');
        $difference = strtotime('now') - strtotime($the_time['the_time']);
        if($difference > $config['timeForNextTry'] ){
            $tests_started = R::dispense('testsstarted');
            $tests_started->user = $exist['id'];
            $tests_started->test = $_POST['test'];
            $tests_startedId = R::store( $tests_started );
            echo 1;
        }else{
            die('tried');
        }
    }else{
        die('close');
    }
