<?php
    require_once('functions.php');
    $items = R::getAll('select * from tests');
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

            <div class="tabDetails">
                <div id="tab1" class="tabContents">
                    <div id="quiz-content1"></div>
                </div><!-- //tab1 -->
                <div id="tab2" class="tabContents">
                    <div id="quiz-content2">


                        <div class="container" style="max-width:100%;direction:ltr">
                            <div class="row col-md-12 custyle">
                                <table class="table table-striped custab">
                                    <thead>
                                    <a href="#" data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-xs pull-right"><b>+</b> Add new</a>
                                    <tr>
                                        <th>ID</th>
                                        <th>Test</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        foreach($items as $item){
                                    ?>
                                        <tr>
                                            <td style="color:black"><?=$item['id']?></td>
                                            <td style="color:black">
                                                <a href="/admin/questions.php?id=<?=$item['id']?>">
                                                    <?=$item['name']?>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a class="btn btn-info btn-xs" data-toggle="modal" data-target="#editQuestionModal-<?=$item['id']?>" href="#">
                                                    <span class="glyphicon glyphicon-edit"></span>
                                                    Edit
                                                </a>
                                                <a href="/admin/delete_test.php?id=<?=$item['id']?>" class="btn btn-danger btn-xs">
                                                    <span class="glyphicon glyphicon-remove"></span>
                                                    Del
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




        <?php
            foreach($items as $item){
        ?>
        <div class="modal" id="editQuestionModal-<?=$item['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                        <h4 class="modal-title" id="myModalLabel">
                            Edit test "<?=$item['name']?>"
                        </h4>
                    </div>
                    <div class="modal-body">
                        <form role="form" action="edit_test.php" method="post">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="name" id="name" class="form-control input-sm" value="<?=$item['name']?>" />
                                        <input type="hidden" name="id" value="<?=$item['id']?>" />
                                    </div>
                                </div>
                            </div>
                            <input type="submit" value="Add" class="btn btn-info btn-block">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
            }
        ?>








        <div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                        <h4 class="modal-title" id="myModalLabel">
                            Add new test
                        </h4>
                    </div>
                    <div class="modal-body">
                        <form role="form" action="add_test.php" method="post">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="name" id="name" class="form-control input-sm" placeholder="Test title">
                                    </div>
                                </div>
                            </div>
                            <input type="submit" value="Add" class="btn btn-info btn-block">
                        </form>
                    </div>
                </div>
            </div>
        </div>













        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
</html>