var isQuestion = false;

function getQuiz(moduleId) {

    return $.ajax({
        method: "GET",
        url: "utility/getQuiz.php?moduleId=" + moduleId,
        success: function (response) {
            if (response.status === "success") {

                if (response.data.length) {
                    isQuestion = true;
                    for (var i = 0; i < response.data.length; i++) {
                        var quiz = response.data[i];
                        setUpQuiz(quiz);
                        //if (response.data[i].moduleId == moduleId) { //found module
                        //    if (response.data[i].question != '') { //there is a question
                        //        isQuestion = true;
                        //        setUpQuestion(response.data[i]);
                        //    }
                        //    else { //do not show modal
                        //        isQuestion = false;
                        //    }
                        //}
                    }
                    $('.quiz').first().addClass('active');
                    if ($('.quiz:first').is($('.quiz:last'))){
                        $('#nextQuestion').hide();
                        $('#finishBtnx2').show();
                    }
                    else{
                        $('#nextQuestion').show();
                        $('#finishBtnx2').hide();
                    }
                }
                else{
                    isQuestion = false;
                }
            }
            else {
                alert('An error has occurred');
                console.error(response.message);
            }
        },
        error: function (response) {
            alert('An error has occurred');

            console.error(JSON.stringify(response));
        }
    });
}

var attempts = [];
var currQuizIndex = 1;

function setUpQuiz(quiz){
    console.log(quiz);
    attempts.push({quizId: quiz.quizId, attempts:0});
    var question = quiz.question;
    var answersJson = JSON.parse(quiz.answers);
    var answers = [];
    answers.push(answersJson.correctAnswer);
    if (answersJson.otherAnswer1 != '')
        answers.push(answersJson.otherAnswer1);
    if (answersJson.otherAnswer2 != '')
        answers.push(answersJson.otherAnswer2);
    if (answersJson.otherAnswer3 != '')
        answers.push(answersJson.otherAnswer3);

    var answersDom = '';
    for (var i = 0; i < answers.length; i++){
        answersDom += '<label><div class="input-group"><span class="input-group-addon"><input type="radio" name="check_list[]" data-answer="' + i + '" value="' + answers[i] + '" /></span><span class="answerText">' + answers[i] + '</span></div></label>';
    }
    $('#finish-reading-popup .modal-body').append('<div class="quiz" quiz-id="' + quiz.quizId + '"><div class="row"><div class="col-lg-12 question"></div></div><div class="row"><div class="col-lg-12 answers"></div></div></div>');
    $('[quiz-id="' + quiz.quizId + '"] .question').append('<h3>Question: ' + question + '</h3>');
    $('[quiz-id="' + quiz.quizId + '"] .answers').append(answersDom);

    var children = $('[quiz-id="' + quiz.quizId + '"] .answers').children().get().sort(function() { //randomise order of questions
        return Math.random() - 0.5;
    });
    $('[quiz-id="' + quiz.quizId + '"] .answers').append(children);

//    $('[quiz-id="' + quiz.quizId + '"] .answers .input-group').on('click',function(e){
//        $(this).find('input[type="radio"]').prop('checked', true).trigger('change');
//    });

    $('[quiz-id="' + quiz.quizId + '"] .answers input').on('change',function(el) {
        $('[quiz-id="' + quiz.quizId + '"] .answers .input-group').removeClass('correct-answer incorrect-answer');
        if ($(this).attr('data-answer') == 0){
            if ($(this).closest('.quiz').is('.quiz:last')){
                $('#finishBtnx2').removeAttr('disabled');
            }
            else{
                $('#nextQuestion').removeAttr('disabled');
            }
            $(this).closest('.input-group').addClass('correct-answer');
            //for (var i = 0; i < attempts.length; i++){
            //    if ()
            //}
        }
        else{
            if ($(this).closest('.quiz').is('.quiz:last')) {
                $('#finishBtnx2').attr('disabled', 'disabled');
            }
            else {
                $('#nextQuestion').attr('disabled', 'disabled');
            }
            $(this).closest('.input-group').addClass('incorrect-answer');
        }
        for (i=0;i<attempts.length;i++){
            if (attempts[i].quizId == quiz.quizId)
                attempts[i].attempts++;
        }
    });
}

function checkScroll(type)
{
    if (type == 'pdf') {
        if ($('#viewerContainer').scrollTop() + $('#viewerContainer').innerHeight() >= $('#viewerContainer')[0].scrollHeight) {
            //if (isQuestion)
            //    $('#finish-reading-popup').modal('show');
            //else
                $('#finishBtn').fadeIn();
        }
        else {
            //if (isQuestion)
            //    $('#finish-reading-popup').modal('hide');
            //else
                $('#finishBtn').fadeOut();

        }
    }
    else{
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight){
            //if (isQuestion)
            //    $('#finish-reading-popup').modal('show');
            //else
                $('#finishBtn').fadeIn();
        }
        else {
            //if (isQuestion)
            //    $('#finish-reading-popup').modal('hide');
            //else
                $('#finishBtn').fadeOut();

        }
    }
}