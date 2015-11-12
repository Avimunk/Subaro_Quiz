<?php
require_once('admin/functions.php');
if(isset($_GET['id']))
    $items = R::getRow('select * from tests where id = '.$_GET['id']);
else
    $items = R::getRow('select * from tests where id = 1');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Quiz</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="description" content="Sliding Quiz with jQuery" />
    <meta name="keywords" content="jquery, sliding, quiz, css3, javascript"/>
    <link rel="stylesheet" href="/css/demo.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="/css/sliding.quiz.min.css" type="text/css" media="screen"/>
    <script type="text/javascript" src="/js/jquery.min.js"></script>

    <!--
    //compress include-->
    <script type="text/javascript" src="/js/sliding.quiz.js"></script>


    <script type="text/javascript" src="/js/smoke.js"></script>


    <style type="text/css">

    </style>
</head>
<body style="direction: rtl!important;text-align:right">
<div class="tabContaier">
    <div class="userNameHello">


    </div>
    <div class="tabDetails">
        <div id="tab1" class="tabContents">




            <a href="/" class="btn btn-primary btn-md pull-left">Back to Tests list</a><br/><br/>
            <div id="quiz-content1"></div>
        </div><!-- //tab1 -->
        <div id="tab2" class="tabContents">
            <div id="quiz-content2"></div>
        </div><!-- //tab2 -->
    </div><!-- //tab Details -->
</div><!-- //Tab Container -->















<div id="myModal" class="modal fade" role="dialog" data-backdrop="static"
     data-keyboard="false" >
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="color: #000000"></h4>
            </div>
            <form role="form" method="post" id="userAdd">
                <div class="modal-body">
                    <br/><br/><br/><br/><br/>
                    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#Login">Login</button>
                    <button type="button" class="btn btn-info btn-lg" style="float:left" data-toggle="modal" data-target="#Register">Register</button>
                    <br/><br/><br/><br/><br/>
                </div>
                <div class="modal-footer">

                </div>
            </form>
        </div>

    </div>
</div>

<div id="Login" class="modal fade" role="dialog" data-backdrop="static"
     data-keyboard="false" >
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="color: #000000">Enter Your details</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="email">Email address:</label>
                    <input type="email" class="form-control" name="email" id="email">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="checkUser();return false;" class="btn btn-success">Login</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>



<div id="Register" class="modal fade" role="dialog" data-backdrop="static"
     data-keyboard="false" >
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="color: #000000">Enter Your details</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="email">Email address:</label>
                    <input type="email" aria-describedby="name-format" required class="form-control" name="email" id="email">
                </div>
                <div class="form-group">
                    <label for="pwd">Name:</label>
                    <input type="text" aria-describedby="name-format" required class="form-control" name="name" id="name">
                </div>
                <div class="form-group phoneclass">
                    <label for="pwd">Phone:</label>
                    <div style="clear: both"></div>
                    <div class="col-md-7"></div>

                    <div class="col-md-2">
                        <input type="text" disabled="disabled" value="05" class="form-control"/>
                    </div>
                    <div class="col-md-3" style="padding: 0;">
                        <input type="number" aria-describedby="name-format" required class="form-control col-md-10" name="phone" id="phone">
                    </div>
                    <div style="clear: both"><br/><br/></div>
                </div>
                <input type="hidden" value="<?=$_GET['id']?>" class="form-control" name="test" id="test">
            </div>
            <div class="modal-footer">
                <button type="button" onclick="registerUser();return false;" class="btn btn-success">Register</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


<div id="Info" class="modal fade" role="dialog" data-backdrop="static"
     data-keyboard="false" >
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="color: #000000"></h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <a type="button" class="btn btn-default" href="/">Back to test list</a>
            </div>

        </div>

    </div>
</div>





















<script type="text/javascript" src="js/demo.js"></script>
<script type="text/javascript">
    /*************************************/
    /*slide quiz*/
    /*************************************/
    $(document).ready(function(){
        var userEmail = localStorage.getItem("userEmail");
        if(userEmail){
            $('.userNameHello').append(' <button type="button" onclick="logout();return false;" class="btn btn-danger btn-lg">Logout</button>'+'Hi, '+localStorage.getItem("userEmail"));
            $.ajax({
                type :'post',
                url : '/admin/check_user_test.php',
                data : {email: userEmail, test: <?=$_GET['id']?>}
            }).success(function(result) {
                if(result == '1'){

                }else{
                    $('#Info').modal('show');
                    $('#Info .modal-body').empty().html('לא ניתן לערוך את המבחן');
                }
            });
        }else{
            $('#myModal').modal('show');
        }
    });

    function checkUser(){
        var email = $('#Login #email').val();
        $.ajax({
            type :'post',
            url : '/admin/check_user.php',
            data : {email: email}
        }).success(function(result) {
            if(result == '1'){
                localStorage.setItem("userEmail", email);
                window.location.href = '/';
            }else{
                $('#Info').modal('show');
                $('#Info .modal-body').empty().html('User not exist. Register please');
            }
        });
        return false;
    }

    function registerUser(){
        var email = $('#Register #email').val();
        var name = $('#Register #name').val();
        var phone = $('#Register #phone').val();
        if(email.length < 1){
            $('#Register #email').css('border', '2px solid red');
            return false;
        }


        if(phone  < 1){
            $('#Register #phone').css('border', '2px solid red');
            return false;
        }
        if(name  < 1){
            $('#Register #name').css('border', '2px solid red');
            return false;
        }
        $.ajax({
            type :'post',
            url : '/admin/register_user.php',
            data : {email: email, phone: phone, name: name }
        }).success(function(result) {
            if(result == 'exist'){
                $('#Info').modal('show');
                $('#Info .modal-body').empty().html('You already registered. Please Log in');
            }else{
                localStorage.setItem("userEmail", email);
                window.location.href = '/';
            }
        });
        return false;
    }
    function logout(){
        localStorage.removeItem("userEmail");
        window.location.href = '/';
    }

    //        function addUser(){
    //            localStorage.setItem("userEmail", $("#userAdd #email").val());
    //            $.ajax({
    //                type :'post',
    //                url : '/admin/add_user.php',
    //                data : $("#userAdd").serialize()
    //            }).success(function(result) {
    //                if(result == 'close'){
    //
    //                }else if(result == 'tried'){
    //                    $('.modal-title').empty().css('color', 'red').append('You already tried this test today');
    //                }
    //                else if(result == 'new'){
    //                    localStorage.setItem("user", result);
    //                    $('#myModal').modal('hide');
    //                    $('#myModal form input').each(function(){
    //                        $(this).val('');
    //                    })
    //                }else{
    //                    localStorage.setItem("user", result);
    //                    $('#myModal').modal('hide');
    //                    $('#myModal form input').each(function(){
    //                        $(this).val('');
    //                    })
    //                }
    //
    //            });
    //        }
    $(function()
    {
        <?php
            //http://pictures.dealer.com/a/albanysubarusoa/0061/1369a52f489c79a101cd706d104df874x.jpg
        ?>

        $("#quiz-content1").sliding_quiz ({
            'instruction':
            {
                'title': 'סובארו 2015',
                'description' : '<h1>מבחן ידע  סובארו</h1>   <br> <img style="width:90%" src="<?=$items['img']?>">'
            },
            'questions':
                [
                    <?php
                        $questions = R::getAll('select * from questions where test = '.$_GET['id']);
                        $q=0;
                        foreach($questions as $question){
                        $q++;
                            $points = R::getAll('select points from answers where question = '.$question['id']);
                            $answers = R::getAll('select name from answers where question = '.$question['id']);


                    ?>
                    {
                        'id'        : <?=$q?>,
                        'question'  : '<?=$question['name']?>',
                        'answers'   : [<?php $n=0; foreach($answers as $answer){$n++; if($n == 1){echo '';}else{echo ', ';} echo " '".htmlentities($answer['name'], ENT_QUOTES)."'"; }?>],
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

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<style>
    .modal-dialog{
        top:100px;
    }
    label{
        color:gray;
    }
    #show-submit-score{
        display:none
    }
    .userNameHello{
        width:100%;
        font-size:25px;
        background: #ffffff;
    }
    .resultset span.label{
        float: left;
        margin-bottom: 20px;
        display:block;
    }
</style>
</body>
</html>