function startReading(moduleId)
{
    $.ajax({
	method: "POST",
	url: "utility/startReading.php",
	data: {
	    moduleId: moduleId
	},
	success: function(response){
	    if(response.status === "error")
	    {
		alert('An error has occurred');
	    
		console.error(response.message);
	    }
	},
	error: function(response){
	    alert('An error has occurred');
	    
	    console.error(JSON.stringify(response));
	}
    });
}

function endReading(moduleId)
{
    $.ajax({
	method: "POST",
	url: "utility/endReading.php",
	data: {
	    moduleId: moduleId
	},
	success: function(response){
	    if(response.status === "success")
	    {
		if(!isQuestion)
		{
		    location.href = "index.php";
		}
	    }
	    else
	    {
		alert('An error has occurred');
		
		console.error(response);
	    }
	},
	error: function(response){
	    alert('An error has occurred');
	    
	    console.error(JSON.stringify(response));
	}
    });
}

function sendQuestionAttepts(moduleId)
{
    if(isQuestion)
    {
	$.ajax({
	    method: "POST",
	    url: "utility/questionAttempts.php",
	    data: {
		moduleId: moduleId,
		questionAttempts: JSON.stringify(attempts)
	    },
	    success: function(response){
		if(response.status === "success")
		{
		    location.href = "index.php";
		}
		else
		{
		    alert('An error has occurred');

		    console.error(response);
		}
	    },
	    error: function(response){
		alert('An error has occurred');

		console.error(JSON.stringify(response));
	    }
	});
    }
    else
    {
	location.href = "index.php";
    }
}

$(function(){
    $.when(getQuiz(moduleId)).always(function() {
	$('#finishBtn').click(function(){
        if(isQuestion)
        {
            $('#finish-reading-popup').modal('show');
        }
	    endReading(moduleId);
	});

    $('#nextQuestion').click(function() {
        $('.quiz.active').removeClass('active').next('.quiz').addClass('active');
        if ($('.quiz.active').is('.quiz:last')){
            $('#nextQuestion').hide();
            $('#finishBtnx2').show();
        }
    })

	checkScroll(moduleType);
    });

    $('#viewerContainer').scroll(function(){
	checkScroll(moduleType);
    });

    startReading(moduleId);

    $('#finishBtnx2').click(function(){
	sendQuestionAttepts(moduleId);
    });
});