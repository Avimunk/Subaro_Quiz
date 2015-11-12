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
        <title>Quiz</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="description" content="Sliding Quiz with jQuery" />
        <meta name="keywords" content="jquery, sliding, quiz, css3, javascript"/>
        <link rel="stylesheet" href="css/demo.css" type="text/css" media="screen"/>
        <link rel="stylesheet" href="css/sliding.quiz.min.css" type="text/css" media="screen"/>
        <script type="text/javascript" src="js/jquery.min.js"></script>

        <!--
        //compress include-->
        <script type="text/javascript" src="js/sliding.quiz.js"></script>

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

                    <!-- Modal -->
                    <div id="myModal" class="modal fade" role="dialog" data-backdrop="static"
                         data-keyboard="false" >
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" style="color: #000000">Enter Your details</h4>
                                </div>
                                <form role="form" method="post" id="userAdd">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="email">Email address:</label>
                                        <input type="email" class="form-control" name="email" id="email">
                                    </div>
                                    <div class="form-group">
                                        <label for="pwd">Name:</label>
                                        <input type="text" class="form-control" name="name" id="name">
                                    </div>
                                    <div class="form-group">
                                        <label for="pwd">Phone:</label>
                                        <input type="tel" class="form-control" name="phone" id="tel">
                                    </div>
                                    <input type="hidden" value="<?=$_GET['id']?>" class="form-control" name="test" id="test">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" onclick="addUser();" class="btn btn-default">Start</button>
                                </div>
                                </form>
                            </div>

                        </div>
                    </div>




                    <div id="quiz-content1"></div>
                </div><!-- //tab1 -->
                <div id="tab2" class="tabContents">
                    <div id="quiz-content2"></div>
                </div><!-- //tab2 -->
            </div><!-- //tab Details -->
        </div><!-- //Tab Container -->


        <script type="text/javascript" src="js/demo.js"></script>
		<script type="text/javascript">
        /*************************************/
        /*slide quiz*/
        /*************************************/
        $(window).load(function(){
            $('#myModal').modal('show');
        });
        function addUser(){
            localStorage.setItem("userEmail", $("#userAdd #email").val());
            $.ajax({
                type :'post',
                url : '/admin/add_user.php',
                data : $("#userAdd").serialize()
            }).success(function(result) {
                if(result == 'close'){

                }else if(result == 'tried'){
                    $('.modal-title').empty().css('color', 'red').append('You already tried this test today');
                }
                else if(result == 'new'){
                    localStorage.setItem("user", result);
                    $('#myModal').modal('hide');
                    $('#myModal form input').each(function(){
                        $(this).val('');
                    })
                }else{

                }

            });
        }
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
                            'answers'   : [<?php foreach($answers as $answer){ echo "'".$answer['name']."' ,"; }?>],
                            'weight'     : [<?php foreach($points as $point){ echo '"'.$point['points'].'", '; }?>]
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
        </style>
</body>
</html>