<?php
    require_once('admin/functions.php');
    if(isset($_GET['id']))
        $items = R::getAll('select * from tests where id = '.$_GET['id']);
    else
        $items = R::getAll('select * from tests where id = 1');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Sliding Quiz with jQuery</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="description" content="Sliding Quiz with jQuery" />
    <meta name="keywords" content="jquery, sliding, quiz, css3, javascript"/>
    <link rel="stylesheet" href="css/demo.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="css/sliding.quiz.min.css" type="text/css" media="screen"/>
    <script type="text/javascript" src="js/jquery.min.js"></script>

    <!--
    //compress include-->
    <script type="text/javascript" src="js/sliding.quiz.min.js"></script>

    <!--//uncompress include
    <script type="text/javascript" src="js/smoke.js"></script>
    <script type="text/javascript" src="js/sliding.quiz.js"></script>
    -->
    <style type="text/css">

    </style>
</head>
<body style="direction: rtl!important;text-align:right">
<div class="tabContaier">

    <div class="tabDetails">
        <div id="tab1" class="tabContents">
            <div id="quiz-content1"></div>
        </div><!-- //tab1 -->
        <div id="tab2" class="tabContents">
            <div id="quiz-content2"></div>
        </div><!-- //tab2 -->
    </div><!-- //tab Details -->
</div><!-- //Tab Container -->
<div style="background: #fff;margin: 20px auto;width: 70%;min-width:900px;padding: 10px;">
    <h3>Your result</h3><pre id="server-response" style="background: #f0f0f0;padding: 20px;"></pre>
</div>

<script type="text/javascript" src="js/demo.js"></script>
<script type="text/javascript">
    /*************************************/
    /*slide quiz*/
    /*************************************/

    $(function()
    {

        $("#quiz-content1").sliding_quiz ({
            'instruction':
            {
                'title': 'סובארו 2015',
                'description' : 'מבחן ידע  סובארו   <br> <img style="width:50%" src="http://t2.gstatic.com/images?q=tbn:ANd9GcRt2dqjL3Dx5WghWps1klzPtVCpiGL6fRKVJcfpm8vO1ykBvCA2Gw">'
            },
            'questions':
                [
                    <?php
                        $questions = R::getAll('select * from questions where test = '.$_GET['id']);
                        foreach($questions as $question){
                            $points = R::getAll('select points from answers where question = '.$question['id']);
                            $answers = R::getAll('select name from answers where question = '.$question['id']);


                    ?>
                    {
                        'id'        : <?=$question['id']?>,
                        'question'  : '<?=$question['name']?>',
                        'answers'   : [<?php $n=0; foreach($answers as $answer){$n++; if($n == 1){echo '';}else{echo ', ';} echo " '".htmlentities($answer['name'])."'"; }?>],
                        'weight'     : [<?php $n=0; foreach($points as $point){$n++; if($n == 1){echo '';}else{echo ', ';} echo htmlentities($point['points']); }?>]
                    },

                    <?php } ?>

                ],
            'locale': //optional
            {
                'msg_no_more_selected' : 'You can not choose more than %n answer(s)',
                'msg_not_found' : 'Cannot find questions',
                'msg_please_select_an_option' : 'Please select an option',
                'msg_question' : 'Question',
                'msg_you_scored' : 'You scored',
                'msg_click_to_review' : 'Click to Question button to review your answers',
                'bt_next' : 'Next',
                'bt_back' : 'Back',
                'bt_finish' : 'Finish',
                'bt_contact' : 'Submit Your Score',
                'contact_heading' : 'Submit Your Score',
                'contact_name' : 'Name',
                'contact_email' : 'Email',
                'contact_phone' : 'Phone',
                'contact_message' : 'Message',
                'contact_thankyou' : 'Thank you for your submission. <a href="http://goo.gl/kPdmL" target="_blank">Click here to check mail</a>'
            },
            'when_finish_submit_url': 'server_response.php',
            'contact_form_submit_url': 'submit_answers.php',
            //'score_method': 'default', //percentage or default
            'effect': 'fade'
        });
    });
</script>
</body>
</html>