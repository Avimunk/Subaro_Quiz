<?php
    require_once('admin/functions.php');
    $items = R::getAll('select * from tests');
    $i = 0;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Sliding Quiz with jQuery</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="description" content="Sliding Quiz with jQuery" />
        <meta name="keywords" content="jquery, sliding, quiz, css3, javascript"/>
        <link rel="stylesheet" href="/css/demo.css" type="text/css" media="screen"/>
        <link rel="stylesheet" href="/css/sliding.quiz.min.css" type="text/css" media="screen"/>
        <script type="text/javascript" src="/js/jquery.min.js"></script>

        <!--
        //compress include-->
        <script type="text/javascript" src="/js/sliding.quiz.min.js"></script>

        <!--//uncompress include
        <script type="text/javascript" src="js/smoke.js"></script>
        <script type="text/javascript" src="js/sliding.quiz.js"></script>
        -->
        <style type="text/css">
            .tabDetails{
                background:white!important
            }
        </style>
    </head>
    <body style="direction: rtl!important;text-align:right">
        <div class="tabContaier">
            <div class="userNameHello">


            </div>

            <div class="tabDetails">
                <div id="tab1" class="tabContents">
                    <div id="quiz-content1"></div>
                </div><!-- //tab1 -->
                <div id="tab2" class="tabContents">
                    <div id="quiz-content2">


                        <div class="container" style="max-width:100%;direction:ltr">
                            <div class="row col-md-12 custyle">
                                <table class="table table-striped custab" style="direction: rtl!important;">
                                    <thead>

                                    <tr>
                                        <th>ID</th>
                                        <th>Test</th>
                                        <th class="text-center"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        foreach($items as $item){
                                            $i++;
                                    ?>
                                        <tr>
                                            <td style="color:black"><?=$i?></td>
                                            <td style="color:black">

                                                    <?=$item['name']?>

                                            </td>
                                            <td class="text-center">
                                                <a class="btn btn-info btn-xs" data-id="<?=$item['id']?>"  href="/test.php?id=<?=$item['id']?>">
                                                    <span class="glyphicon glyphicon-view"></span>
                                                    Start
                                                </a>

                                            </td>
                                        </tr>
                                    <?php
                                        }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
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
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>

                </div>

            </div>
        </div>

















        <script type="text/javascript">
            /*************************************/
            /*slide quiz*/
            /*************************************/
            $(document).ready(function(){
                var userEmail = localStorage.getItem("userEmail");
                if(userEmail){
                    $('.userNameHello').append(' <button type="button" onclick="logout();return false;" class="btn btn-danger btn-lg">Logout</button>'+'Hi, '+localStorage.getItem("userEmail"));
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
            function validateEmail(email) {
                var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
                return re.test(email);
            }
            function logout(){
                localStorage.removeItem("userEmail");
                window.location.href = '/';
            }
            function checkIfAllowed(obj){
                var userEmail = localStorage.getItem("userEmail");
                if(userEmail) {
                    $.ajax({
                        type: 'post',
                        url: '/admin/check_user_test.php',
                        data: {email: userEmail, test: $(obj).attr('data-id')}
                    }).success(function (result) {
                        if (result == '1') {

                        } else {
                            $(obj).attr('href', '#')
                        }
                    });
                }
            }

            $(document).ready(function(){

            })
        </script>


        <style>
            .userNameHello{
                width:100%;
                font-size:25px;
                background: #ffffff;
            }
        </style>











        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
</html>