$(function() {
    $.sliding_quiz = {
        version: '1.0'
    };
    $.fn.sliding_quiz = function(config){
        config = $.extend({}, {
            instruction: null,
            questions: null,
            locale: null,
            when_finish_submit_url : '',
			contact_form_submit_url : '',
            score_method: 'default', //percentage
            effect: 'slide',
            callback: function() {}
        }, config);

        //check question
        if(config.questions == null || config.questions == undefined){
            return false;
        }
        // initial container object
        var container = $(this);
        container.addClass('quiz-content');
        // number of questions
        var numOfQuestions = config.questions.length;
        // array user choice answer
        var rightAnswers = [];
        var rightAnswersIndex = [];
        var userAnswers = [];
        var userAnswersIndex = [];
        // array user choice answer for email
        var userAnswersForEmail = [];
        var numOfSelectedAnswerPerQuestion = [];
        /*
        current position of fieldset / navigation link
        */
        var current 	= 1;

        //set locale
        var locale = {
            'msg_no_more_selected' : 'You can not choose more than %n answer(s)',
            'msg_not_found' : 'Cannot find questions',
            'msg_please_select_an_option' : 'Please select an option',
            'msg_question' : 'Question',
            'msg_you_scored' : 'You scored',
            'msg_click_to_review' : 'Click to Question button to review your answers',
            'bt_start' : 'Start',
            'bt_next' : 'Next',
            'bt_back' : 'Back',
            'bt_finish' : 'Finish',
            'contact_heading' : 'Submit Your Score',
            'contact_name' : 'Name',
            'contact_email' : 'Email',
            'contact_phone' : 'Phone',
            'contact_message' : 'Message',
            'contact_show_form_button' : 'Submit Your Score',
            'contact_submit_button' : 'Submit',
            'contact_thankyou' : 'Thank you for your submission.'
        };
        if(config.locale != null){
            $.each(locale, function(index, value) {
                if(config.locale[index] == undefined){
                    config.locale[index] = value;
                }
            })
        }else{
            config.locale = locale;
        }

        /*
            Compare 2 arrays
            Example:
                var a = [1, 2, 3];
                var b = [2, 3, 4];
                var c = [3, 4, 2];

                jQuery.compare(a, b);
                // false

                jQuery.compare(b, c);
                // true
        */
        jQuery.extend({
            compare: function (arrayA, arrayB) {
                if (arrayA.length != arrayB.length) { return false; }
                // sort modifies original array
                // (which are passed by reference to our method!)
                // so clone the arrays before sorting
                var a = jQuery.extend(true, [], arrayA);
                var b = jQuery.extend(true, [], arrayB);
                a.sort();
                b.sort();
                // console.log(a);
                // console.log(b);
                for (var i = 0, l = a.length; i < l; i++) {
                    if (a[i] !== b[i]) {
                        return false;
                    }
                }
                return true;
            }
        });

        /*Initial question*/
        if (numOfQuestions === 0) {
            container.html('<div class="quiz-wrapper"><div class="steps"><form id="formElem" name="formElem" action="" method="post"><fieldset class="step"><legend>'+config.locale['msg_not_found']+'</legend></fieldset></form></div></div>');
            return;
        }

        /*Instruction Page*/
		var welcomePage = '';
		if(config.instruction != null){
			welcomePage = '<div class="quiz-wrapper" id="quiz-instruction-container">\n\
                    <div class="steps">\n\
                    <form>\n\
                    <fieldset class="step"><legend>' + config.instruction['title']+'</legend><div style="padding: 20px 10px;">'+config.instruction['description']+'</div></fieldset></form></div>\n\
                    <div id="quiz-navigation" style=""><ul style="float:right;margin-right:20px;"><li><a href="javascript:;;" id="btn-start-quiz">'+config.locale['bt_start']+'</a></ul></div>\n\
                    </div>';
		}

        /*Contact Page Form*/
		var competitionForm = '';

		var showQuiz = (welcomePage == '')  ? 'show_container' : 'hide_container';
        var quizContent = '<div class="quiz-wrapper '+showQuiz+'" id="main-quiz-container"><div class="steps"><form id="formElem" name="formElem" action="" method="post">';
        for (questionIdx = 0; questionIdx < numOfQuestions; questionIdx++) {
            if(config.questions[questionIdx] != undefined){
                show = (questionIdx==0) ? "show" : "";
                var $holderQuestion = $('<div>');
                $holderQuestion.append(config.questions[questionIdx].question);

                var $formatQuestion = $holderQuestion.html();
                $formatQuestion = $formatQuestion.replace("<br>", "&lt;br&gt;");
                $formatQuestion = $('<div>').append($formatQuestion).text();

                quizContent += '<fieldset class="step '+show+'" question="'+(questionIdx+1)+'" choice=""><legend>'+$formatQuestion+'</legend>';
                //get image and iframe in question and print it to body
                if($holderQuestion.find('img').length || $holderQuestion.find('iframe').length){
                    var imageString  = $holderQuestion.html();
                    imageString  = imageString.replace($holderQuestion.text(), "");
                    quizContent += imageString;
                }
                for (answerIdx = 0; answerIdx < config.questions[questionIdx].answers.length; answerIdx++) {
                    quizContent += '<p answer="'+(answerIdx+1)+'">'+config.questions[questionIdx].answers[answerIdx]+'</p>';
                }

                var $formatAnswer = [];
                var $formatAnswerIdx = [];
                var $answerHasScore = 0;
                for($i=0; $i < config.questions[questionIdx].weight.length; $i++){
                    if(config.questions[questionIdx].weight[$i] > 0){
                        $formatAnswerIdx[$i] = String($i+1);
                        $formatAnswer[$i] = String(config.questions[questionIdx].weight[$i]);
                        $answerHasScore++
                    }
                }
                $formatAnswer = $formatAnswer.filter(function(v){return v!==''});
                numOfSelectedAnswerPerQuestion[config.questions[questionIdx].id] = $answerHasScore;

                rightAnswers[questionIdx+1] = $formatAnswer;
                rightAnswersIndex[questionIdx+1] = $formatAnswerIdx;

                quizContent += '</fieldset>';
            };
        }

        NextFinishButton = '<a href="#" id="next-quiz">'+config.locale['bt_next']+'</a><a href="#" id="finish-quiz" style="display: none;"><i class="qicon-asterisk"></i>&nbsp;'+config.locale['bt_finish']+'</a>';
        if(numOfQuestions == 1){
            NextFinishButton = '<a href="#" id="finish-quiz"><i class="qicon-asterisk"></i>&nbsp;'+config.locale['bt_finish']+'</a>';
        }
        quizContent += '</form></div><div id="quiz-notice"><span class="label label-important"><i class="qicon-exclamation-sign qicon-white"></i>&nbsp;'+config.locale['msg_please_select_an_option']+'</span></div><div id="quiz-navigation" style="display:none;"><ul><li class="disabled"><a href="#" id="back-quiz">'+config.locale['bt_back']+'</a></li><li class="page-number"><a href="#">1/'+numOfQuestions+'</a></li><li class="">'+NextFinishButton+'</li></ul></div><div class="progress-container"><div class="progress"></div></div></div></div>';
        container.html(welcomePage+quizContent);

        /*Initial object*/
		// initial progress object
		var progress = container.find('.progress');
		var progressContainer = container.find('.progress-container');
        var progressWidth = progressContainer.width() - 40;
        // initial steps object
        var steps = container.find('.steps'),
        notice = container.find('#quiz-notice'),
        page_number = container.find(".page-number a"),
        next_button = container.find("#next-quiz"),
        back_button = container.find("#back-quiz"),
        finish_button = container.find("#finish-quiz");
        var $totalWeightage = 0;
        var $finalWeightageArray = Array();
        /*
        sum and save the widths of each one of the fieldsets
        set the final sum as the total width of the steps element
        */
        var widths      = new Array();
        if(config.effect == 'slide'){
            var stepsWidth  = 0;
            steps.find('.step').each(function(i){
                var $step       = $(this);
                widths[i]       = stepsWidth;
                stepsWidth      += $step.width();//600
            });
            steps.width(stepsWidth);
        }else{
            var checkFirstStep = 0;
            $('#main-quiz-container').find('.steps').find('.step').each(function(i){
                if(checkFirstStep == 0){
                    $(this).fadeIn();
                }else{
                    $(this).fadeOut();
                }
                checkFirstStep++;
            });
        }

        // Function to get the Max value in Array
        Array.max = function( array ){
            return Math.max.apply( Math, array );
        };
        Array.sum = function( my_array ){
            my_array.reduce(function(pv, cv) { return parseFloat(pv) + parseFloat(cv); }, 0);
        };
        /**
         * Get max length of weightage array
         */
        var $listWeightageLength = Array();
        for (var i = 0, toLoopTill = config.questions.length; i < toLoopTill; i++) {
            // $totalWeightage += parseFloat(Array.sum(config.questions[i].weight));
            for (var y = 0; y < config.questions[i].weight.length; y++) {
                $totalWeightage += parseFloat(config.questions[i].weight[y]);
            };
            $weightageLength = config.questions[i].weight.length;
            $listWeightageLength[i] = $weightageLength;
        }
        var $maxWeightageLength = Array.max($listWeightageLength);

        /*
        Answer selected
        */
        container.find('p').click(function () {
            var thisAnswer = $(this);

            //check the number selected
            var parentContainer = thisAnswer.parent();
            var numberOfChecked = parentContainer.find('p.selected').length + 1;
            var questionId = parentContainer.attr('question');

            if(!thisAnswer.hasClass('selected') && numberOfChecked > numOfSelectedAnswerPerQuestion[questionId]){
                msg = config.locale['msg_no_more_selected'];
                msg = msg.replace('%n', numOfSelectedAnswerPerQuestion[questionId]);
                smoke.alert(msg,{},function(){  });
                console.log(msg);
                return;
            }

            if (thisAnswer.hasClass('selected')) {
                thisAnswer.removeClass('selected');
                thisAnswer.parents('.step').attr('choice', '');
            } else {
                // thisAnswer.parents('.step').children('p').removeClass('selected');
                thisAnswer.addClass('selected');
                // thisAnswer.parents('.step').attr('choice', thisAnswer.attr('answer'));
            }
        });

        /*
		show the navigation bar
		*/
        container.find('#quiz-navigation').show();

		/*
		*bind Start button click
		*/
		container.find('#btn-start-quiz').bind('click', function(){
            container.find('#quiz-instruction-container').fadeOut(500, function() {
				$(this).next().fadeIn(500);
				$(this).next().find('#quiz-navigation').show();
			});
		});
        /*
		when clicking on a next link
		the form slides to the next corresponding fieldset
		*/
        next_button.click(function(e){
            current = steps.find(".show");

            //calculate index
            index   = current.index() + 1;
            index ++;
            slider.next(index);

            e.preventDefault();
        });

        /*
		when clicking on a back link
		the form slides to the back corresponding fieldset
		*/
        back_button.click(function(e){

            current = steps.find(".show");

            //calculate index
            index   = current.index() + 1;
            index--;
            slider.back(index);

            e.preventDefault();
        });

        /*
		when clicking on a finish link
		show the user results
		*/
        finish_button.click(function(e){
            slider.finish();
            e.preventDefault();
        });

        var slider = {
            saveSelectedAnswer : function(){
                $currenShowContainer = container.find('.show');

                //Save user answer
                $selectAnswer = '';
                $.each($currenShowContainer.find('.selected'), function(key, value){
                    $selectAnswer += '::'+$(this).attr('answer');
                });
                if($selectAnswer.length > 0){
                    $selectAnswer = $selectAnswer.substring(2, $selectAnswer.length);
                }

                $currenShowContainer.attr('choice', $selectAnswer);
            },
            checkUserSelect : function(){
                $currenShowContainer = container.find('.show');

                //Check has selected answer
                if ($currenShowContainer.attr('choice').length === 0) {
                    notice.slideDown(300);
                    return false;
                }
                return true;
            },
            next: function(index){
                this.saveSelectedAnswer();

                if(!this.checkUserSelect()){
                    return false;
                }

                notice.slideUp();
                if(widths[index-1] != undefined && config.effect == 'slide'){
                    steps.stop().animate({
                        marginLeft: '-' + widths[index-1] + 'px'
                    },500,function(){
                        //mark current slide as show
                        steps.find(".show").removeClass('show');
                        current.next('fieldset').addClass('show');

                        //increase page number
                        page_number.html(index+"/"+numOfQuestions);

                        //enable first back button
                        back_button.parent().removeClass("disabled");
                        //last next button become finish
                        if(numOfQuestions == index){
                            next_button.hide();
                            finish_button.show();
                        }
                    });
                }else{
    				steps.find(".show").fadeOut(500, function() {
    					//mark current slide as show
    					$(this).removeClass('show');
    					current.next('fieldset').addClass('show');
    					current.next('fieldset').fadeIn(500);

    					//increase page number
    					page_number.html(index+"/"+numOfQuestions);

    					//enable first back button
    					back_button.parent().removeClass("disabled");
    					//last next button become finish
    					if(numOfQuestions == index){
    						next_button.hide();
    						finish_button.show();
    					}

                    });
                }
				//Calculate & Animate progress bar
				progress.animate({width: progress.width() + Math.round(progressWidth / numOfQuestions)}, 300);
            },
            back: function(index){
                notice.slideUp();

                if(index-1 >= 0 && config.effect == 'slide'){
                    steps.stop().animate({
                        marginLeft: '-' + widths[index-1] + 'px'
                    },500,function(){
                        //mark current slide as show
                        steps.find(".show").removeClass('show');
                        current.prev('fieldset').addClass('show');

                        //decrease page number
                        page_number.html(index+"/"+numOfQuestions);

                        //disabled first back button
                        if((index - 1) <= 0){
                            back_button.parent().addClass("disabled");
                        }
                        //enabled last next button
                        if(numOfQuestions > index){
                            finish_button.hide();
                            next_button.show();
                            next_button.parent().removeClass("disabled");
                        }
                    });
                }else{
    				steps.find(".show").fadeOut(500, function() {
    					//mark current slide as show
    					$(this).removeClass('show');
    					current.prev('fieldset').addClass('show');
    					current.prev('fieldset').fadeIn(500);

    					//decrease page number
    					page_number.html(index+"/"+numOfQuestions);

    					//disabled first back button
    					if((index - 1) <= 0){
    						back_button.parent().addClass("disabled");
    					}
    					//enabled last next button
    					if(numOfQuestions > index){
    						finish_button.hide();
    						next_button.show();
    						next_button.parent().removeClass("disabled");
    					}


                    });
                }
				//Calculate & Animate progress bar
				progress.animate({width: progress.width() - Math.round(progressWidth / numOfQuestions)}, 300);

            },
            finish: function(){
                this.saveSelectedAnswer();

                if(!this.checkUserSelect()){
                    return false;
                }

                //get user answer
                container.find('.step').each(function (index) {
                    questionNumber = $(this).attr('question');
                    userSelect = $(this).attr('choice');

                    if(typeof userSelect == 'undefined'){
                        //do nothing
                    }else{
                        userSelect = userSelect.split('::');

                        userSelectScore = [];
                        for (var i = 0; i < userSelect.length; i++) {
                            userSelectScore[i] = String(config.questions[questionNumber-1].weight[userSelect[i]-1])
                        };
                        userAnswersIndex[questionNumber] =  userSelect;
                        userAnswers[questionNumber] =  userSelectScore;
                    }
                });


                //quiz result
                var numOfRightAnswer = 0;
                var finalScore = 0,
                questionList = '',
                answerList = '';

                for (i = 0; i < rightAnswers.length; i++) {
                    if(config.questions[i] == undefined) {
                        continue;
                    }


                    bt_rightOrwrong = 'label-important';
                    sign_rightOrwrong = '&nbsp;<i class="qicon-remove qicon-white"></i>';

                    if (jQuery.compare(rightAnswers[i+1], userAnswers[i+1])) {
                        numOfRightAnswer++;
                        bt_rightOrwrong = 'label-success';
                        sign_rightOrwrong = '&nbsp;<i class="qicon-ok qicon-white"></i>';
                    }

                    questionList += '<span class="label '+bt_rightOrwrong+' link-white"><a href="#q' + (i + 1)+'" questionNumber="' + (i + 1)+'" class="quiz-result">'+config.locale['msg_question']+' ' + (i + 1)+'</a>'+sign_rightOrwrong +'</span>';
                    answerList += '<div id="q' + (i + 1)+'" class="final-result">';
                    answerList += '<h3>'+config.questions[i].question+'</h3>';
                    var emailContentList = {};
                     //add question and user ans to email content
                    emailContentList['question_id'] = config.questions[i].id;
                    emailContentList['question'] = config.questions[i].question;
                    emailContentList['answer'] = '';
                    emailContentList['score'] = 0;

                    for (answersIndex = 0; answersIndex < config.questions[i].answers.length; answersIndex++) {
                        var rightOrwrong = '';

                        // if (config.questions[i].weight[answersIndex] > 0 && $.inArray(String(parseFloat(answersIndex + 1)), rightAnswersIndex[i+1]) !== -1) {
                        //     // rightOrwrong += 'right';
                        // }
                        //ktra cau nay user co check ko?
                        if( $.inArray(String(answersIndex + 1), userAnswersIndex[i+1]) !== -1 ) {
                            rightOrwrong = 'selected right';//+rightOrwrong;

                            if(config.score_method != "percentage"){
                                emailContentList['score']  += parseFloat(config.questions[i].weight[answersIndex]);
                            }
                            //if the answer exist in the right answer list

                            if( $.inArray(String(answersIndex + 1), rightAnswersIndex[i+1]) === -1 ) {
                                rightOrwrong += ' wrong';
                            }
                            emailContentList['answer'] += ","+config.questions[i].answers[answersIndex];
                        }

                        var weightSign = '';
                        if(config.score_method == "percentage"){
                            weightSign = (config.questions[i].weight[answersIndex] > 0) ? 'yes' : 'no';
                        }else{
                            weightSign = config.questions[i].weight[answersIndex];
                        }
                        answerList += '<p class="' + rightOrwrong + '"><span class="weight">'+weightSign+'</span>' + config.questions[i].answers[answersIndex] + '</p>';
                        // answerList += '<div class="the-answer ' + rightOrwrong + '">' + config.questions[i].answers[answersIndex] + '</div>';
                    }

                    if(config.score_method == "percentage"){
                        emailContentList['score']  = 'yes';
                        for(a=0; a<userAnswersIndex[i+1].length; a++){
                            if($.inArray(userAnswersIndex[i+1][a], rightAnswersIndex[i+1]) === -1) {
                                emailContentList['score']  = 'no';
                                break;
                            }
                        }
                    }else{
                        //kiem tra neu co bat cu 1 cau nao sai thi cho ve 0
                        // for(a=0; a<userAnswersIndex[i+1].length; a++){
                        //     if($.inArray(userAnswersIndex[i+1][a], rightAnswersIndex[i+1]) === -1) {
                        //         emailContentList['score'] = 0;
                        //         break;
                        //     }
                        // }
                    }

                    //add to email content
                    emailContentList['answer'] = emailContentList['answer'].substring(1);
                    userAnswersForEmail.push(emailContentList);

                    //explanation
                    expId = 'inline'+i;
                    expLink = 'href="#inline'+i+'"';
                    explain = config.questions[i].explanation;
                    if(typeof explain == 'undefined'){
                        explain = '';
                    }else{
                        explain = '<div id="'+expId+'" class="explanation-text"  style="display: none;">'+config.questions[i].explanation+'</div>';
                    }
                    showExplainRightAnswer = '';//(config.locale['msg_right_answer'].length === 0 ) ? '' : '<span class="label"><i class="qicon-star"></i> '+config.locale['msg_right_answer']+'</span>';
                    answerList += '<div class="quiz-explain">'+explain+'</div></div><a name="q'+(i + 1)+'"></a>';

                }

                var score = 0;
                if(config.score_method == 'percentage'){
                    score = Math.round((numOfRightAnswer / numOfQuestions) * 100);
                }else{
                    for (var i = 0; i < userAnswers.length; i++) {
                        if(typeof userAnswers[i+1] == "undefined"){
                            continue;
                        }else{
                            for (var y = 0; y < userAnswers[i+1].length; y++) {
                                score += parseFloat(userAnswers[i+1][y]);
                            };
                        }
                    };
                }
                var contact_button = (config.contact_form_submit_url != '') ? '<li><a href="/" class="btn btn-primary btn-md pull-left">Back to Tests list</a></li>' : '';
                var resultContent = '<div class="quiz-wrapper">\n\
                    <div class="steps">\n\
                    <form id="competitionForm" name="competitionForm" action="submit_answers.php" method="post">\n\
                    <input type="hidden" value="'+score+'" id="quiz_user_score">\n\
                    <h3 class="skill">' +config.locale['msg_you_scored']+' {{score}}. </h3>\n\
                    <fieldset class="step">\n\
                    <div class="resultset">'+questionList+'</div>\n\
                    <div id="your-score" class="final-result">\n\
                        <span class="label label-warning"><i class="qicon-exclamation-sign qicon-white"></i> '+config.locale['msg_click_to_review']+'</span>\n\
                    </div>'+answerList+'</fieldset>'+competitionForm+'</form></div>\n\
                    <div id="quiz-navigation" style=""><ul style="float:right"><li><a href="#" id="btn-submit-score">'+config.locale['contact_submit_button']+'</a><img src="img/ajax-loader.gif" alt="loading" id="ajax-loader"></li>'+contact_button+'</ul></div>\n\
                    </div>';

                if(config.score_method == 'percentage'){
                    resultContent = resultContent.replace('{{score}}', score+'%');
                }else{
                    resultContent = resultContent.replace('{{score}}', score+'/'+$totalWeightage);
                }
                //parse HTML
                container.html(resultContent);

                //when click finish - submit score to server
                if(config.when_finish_submit_url != ''){
                    var par = getSearchParameters();
                    var params = {
                        'user_score': score,
                        'results': userAnswersForEmail,
                        'userEmail': localStorage.getItem("userEmail"),
                        'test': par.id,
                        'total': $totalWeightage
                    };
                    if(config.score_method == 'percentage'){
                        params['user_score'] = score+'%';
                    }

                    jQuery.ajax({
                        type:'POST',
                        data: params,
                        url: config.when_finish_submit_url,
                        success: function(response) {
                            if($('#server-response').length){
                                $('#server-response').html(response);
                            }
                        }
                    });
                }

                //unbind select answer
                container.find('p').unbind('click');
                //bind explanation click event
                container.find('.simple-modal-link').click(function (e) {
                    $this = $(this);
                    $href = $this.attr('href');
                    $($href).modal();
                    return false;

                });

                //validate form
                var competition = {
                    isValidEmailAddress : function(emailAddress){
                        var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
                        return pattern.test(emailAddress);
                    },
                    validate : function(){
                        var error = 1;
                        var hasError = false;

                        $('#competitionForm').find(':input:not(button)').each(function(){
                            var $this       = $(this);

                            if($this.hasClass('required') && !$this.is(':disabled')){
                                var valueLength = jQuery.trim($this.val()).length;

                                if(valueLength == '' || (!$this.prop("checked") && $this.attr('type') == 'checkbox') ){
                                    $this.css('background-color','#FFEDEF');
                                    if($this.attr('type') == 'checkbox'){
                                        $this.parent().addClass('red');
                                    }
                                    hasError = true;
                                }else{
                                    $this.css('background-color','#FFFFFF');
                                    if($this.attr('type') == 'checkbox'){
                                        $this.parent().removeClass('red');
                                    }
                                }
                            }
                        });

                        if($('#competitionName').hasClass('letters_only')){
                            var $this = $('#competitionName');
                            var valueLength = jQuery.trim($this.val()).length;
                            var letterOnly = /^[a-zA-Z ]+$/;

                            if (valueLength == '' || !letterOnly.test($this.val())) {
                                $this.css('background-color','#FFEDEF');
                                hasError = true;
                            }else{
                                $this.css('background-color','#FFFFFF');
                            }
                        }

                        if(competition.isValidEmailAddress($('#competitionEmail').val()) == false){
                            $('#competitionEmail').css('background-color','#FFEDEF');
                            $('#competitionEmail').focus();
                            hasError = true;
                        }else{
                            $('#competitionEmail').css('background-color','#FFFFFF');
                        }

                        if(hasError){
                            error = -1;
                        }
                        return error;
                    }
                }

                //submit score and send to email
                container.find('#btn-submit-score').bind('click', function(){
                    $this = $(this);
                    $ajaxLoader = $('#ajax-loader');

                    $ajaxLoader.show();
                    $this.hide();

                    noError = competition.validate();
                    if(noError == 1){
                        var params = {
                            'name': $('#competitionName').val(),
                            'email': $('#competitionEmail').val(),
                            'phone': $('#competitionPhone').val(),
                            'message': $('#competitionMessage').val(),
							'user_score': $('#quiz_user_score').val(),
							'results': userAnswersForEmail
                        };
                        url = config.contact_form_submit_url;

                        jQuery.ajax({
                            type:'POST',
                            data:params,
                            url: url,
                            success: function(response) {
                                var thanksContent = '<div class="quiz-wrapper">\n\
                                    <div class="steps">\n\
                                    <form id="competitionForm" name="competitionForm" action="quiz_submit_score.php" method="post">\n\
                                    <fieldset class="step" style="display:block">\n\
                                    <legend>'+config.locale['contact_thankyou']+'</legend>\n\
                                    </fieldset></form></div>\n\
                                    </div>';

                                //parse HTML
                                container.html(thanksContent);
                            }
                        });
                    }else{
                        $ajaxLoader.hide();
                        $this.show();
                    }
                });

                //bind show submit score form
                container.find('#show-submit-score').bind('click', function(){
                    $(this).hide();
                    container.find('.step:first').fadeOut(500, function() {
                        $('#btn-submit-score').attr('style', 'display: block');
                        $(this).next().fadeIn(500);
                    });
                });

                //bind show result event
                container.find('.quiz-result').parent('span').bind('click', function(){
					$this = $(this).find('a');
					$('.quiz-result').removeClass('active');
					$this.addClass('active');
					questionNumber = $this.attr('questionNumber');
					$currentQues = container.find('#q' + questionNumber);
                    $explainText = $currentQues.find('.explanation-text').html();
					$explainText = (typeof $explainText == "undefined") ? "" : $explainText;
					if($explainText.length > 0){
						smoke.confirm($currentQues.html(),function(e){
							if (e){
								$('.quiz-result').removeClass('active');
							}else{
								smoke.alert("<p class=\"explanation-text\">"+$explainText+"</p>", {},function(){
									$('.quiz-result').removeClass('active');
								});
							}
						}, {ok: "X",cancel:"?"});
					}else{
						smoke.alert($currentQues.html(),{},function(){ $('.quiz-result').removeClass('active'); });
					}
                });

				//if(numOfQuestions == 1){ container.find('.quiz-result').trigger('mouseenter'); }
            }
        };
    };
});

function getSearchParameters() {
    var prmstr = window.location.search.substr(1);
    return prmstr != null && prmstr != "" ? transformToAssocArray(prmstr) : {};
}

function transformToAssocArray( prmstr ) {
    var params = {};
    var prmarr = prmstr.split("&");
    for ( var i = 0; i < prmarr.length; i++) {
        var tmparr = prmarr[i].split("=");
        params[tmparr[0]] = tmparr[1];
    }
    return params;
}