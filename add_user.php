<?php
    require_once('functions.php');
    if(
        $_POST['name'] &&
        $_POST['email'] &&
        $_POST['phone']
    ){
        $item = R::dispense( 'users' );
        $exist = R::getRow('select id from users where email = "'.$_POST['email'].'"');
        if($exist['id'] > 0){
            $the_time = R::getRow('select the_time from testsstarted where user = "'.$exist['id'].'" and test = '.$_POST['test'].' order by the_time DESC LIMIT 1');
            $difference = strtotime('now') - $the_time['the_time'];
            if($difference > $config['timeForNextTry'] ){
                $tests_started = R::dispense('testsstarted');
                $tests_started->user = $exist['id'];
                $tests_started->test = $_POST['test'];
                $tests_startedId = R::store( $tests_started );
            }else{
                echo 'tried';
                die('tried');
            }

        }else{
            $item->name = $_POST['name'];
            $item->phone = $_POST['phone'];
            $item->email = $_POST['email'];
            $id = R::store( $item );
            $tests_started = R::dispense('testsstarted');
            $tests_started->user = $id;
            $tests_started->test = $_POST['test'];
            $tests_startedId = R::store( $tests_started );
            echo 'new';
        }
    }else{
        die('close');
    }
