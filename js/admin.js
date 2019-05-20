function getModules()
{
    $.ajax({
	method: "GET",
	url: "utility/getModules.php",
	success: function(response){
	    if(response.status === "success")
	    {
		var module;
		var tr;
		var trReport;
		var trAssign;
                
                if($('#modulesReport').hasClass('dataTable'))
                {
                    $('#modulesReport').DataTable().destroy();
                }
		
		if($('.modulesAssignTable').hasClass('dataTable'))
                {
                    $('.modulesAssignTable').DataTable().destroy();
                }
		
		$('#modulesReport tbody').html('');
		$('.modulesAssignTable tbody').html('');
		
		for(var i = 0; i < response.data.length; i++)
		{
		    module = response.data[i];

		    tr = '<tr>';
			tr += '<td>' + module.name + '</td>';
			tr += '<td class="checkTd">';
			    if(module.pdf == 1)
			    {
				tr += '✔';
			    }
			    else
			    {
				tr += '✘';
			    }
			tr += '</td>';
			tr += '<td class="checkTd">';
			    if(module.quiz == 1)
			    {
				tr += '✔';
			    }
			    else
			    {
				tr += '✘';
			    }
			tr += '</td>';
			trReport = tr + '<td class="buttonTd"><a href="reporting.php?moduleId=' + module.moduleId + '"><input type="button" class="btn btn-primary btn-sm" value="View reports"></a></td></tr>';
			trAssign = tr + '<td class="checkboxTd"><input type="checkbox" data-moduleId="' + module.moduleId + '"></td></tr>';

		    $('#modulesReport tbody').append(trReport);
		    $('.modulesAssignTable tbody').append(trAssign);
		}

		$('#modulesReport').DataTable({
		    scrollX: true,
		    bAutoWidth: false,
		    aoColumnDefs: [{
			bSortable: false, 
			aTargets: [3] 
		    }]
		});
		
		$('.modulesAssignTable').DataTable({
		    bPaginate: false,
		    bFilter: false,
		    bInfo: false,
		    aoColumnDefs: [{
			bSortable: false, 
			aTargets: [3] 
		    }]
		});
	    }
	    else
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

function getUsers()
{
    $.ajax({
	method: "GET",
	url: "utility/getUsers.php",
	success: function(response){
	    if(response.status === "success")
	    {
		var user;
		var tr;
                
                if($('#users').hasClass('dataTable'))
                {
                    $('#users').DataTable().destroy();
                }
		
		$('#users tbody').html('');
		
		for(var i = 0; i < response.data.length; i++)
		{
		    user = response.data[i];

		    tr = '<tr>';
			tr += '<td>' + user.fname + ' ' + user.lname + '</td>';
			tr += '<td class="buttonTd"><input type="button" class="btn btn-primary btn-sm" value="Assign modules" data-loading-text="Loading..." onclick="assignModulesModal(' + user.userId + ');"></td>';
		    tr += '</tr>';

		    $('#users tbody').append(tr);
		}

		$('#users').DataTable({
		    scrollX: true,
		    bAutoWidth: false,
		    aoColumnDefs: [{
			bSortable: false, 
			aTargets: [1] 
		    }]
		});
	    }
	    else
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

function assignModulesModal(userId)
{
    var assignBtn = $(event.target);
    assignBtn.button('loading');
    
    $.ajax({
	method: "POST",
	url: "utility/getUserModules.php",
	data: {
	    userId: userId
	},
	success: function(response){
	    assignBtn.button('reset');
	    
	    if(response.status === "success")
	    {
		var modules = response.data;
		
		$('#assignModulesExistingModal .modulesAssignTable input[type="checkbox"]').each(function(){
		    for(var i = 0; i < modules.length; i++)
		    {
			if(modules[i].moduleId == $(this).attr('data-moduleId'))
			{
			    $(this).replaceWith('<span class="alreadyAssigned" data-moduleId="' + $(this).attr('data-moduleId') + '">✔</span>');
			}
		    }
		});
		
		$('#assignModulesBtn').one('click', function(){
		    assignModules(userId);
		});
		
		$("#assignModulesExistingModal").modal();
	    }
	    else
	    {
		alert('An error has occurred');

		console.error(response.message);
	    }
	},
	error: function(response){
	    assignBtn.button('reset');
	    
	    alert('An error has occurred');

	    console.error(JSON.stringify(response));
	}
    });
}

function assignModules(userId)
{
    var submitBtn = $('#assignModulesBtn');
    var moduleIds = [];

    submitBtn.button('loading');
    
    $('#assignModulesExistingModal .modulesAssignTable input[type="checkbox"]').each(function(){
	if($(this).prop('checked'))
	{
	    moduleIds.push($(this).attr('data-moduleId'));
	}
    });
    
    $.ajax({
	method: "POST",
	url: "utility/assignModules.php",
	data: {
	    userId: userId,
	    moduleIds: JSON.stringify(moduleIds)
	},
	success: function(response){
	    submitBtn.button('reset');

	    if(response.status === "success")
	    {
		deselectAllAssignedModules();

		$('#assignModulesExistingModal').modal('hide');
	    }
	    else
	    {
		alert('An error has occurred');

		console.error(response.message);
	    }
	},
	error: function(response){
	    submitBtn.button('reset');

	    alert('An error has occurred');

	    console.error(JSON.stringify(response));
	}
    });
}

function deselectAllAssignedModules(type)
{
    $(type).find('input[type="checkbox"]').prop('checked', false);
}

$(function(){
    getModules();
    getUsers();
    
    $('form[name="addUser"]').validate({
	rules: {
	    username: {
		required: true,
		remote: {
		    type: "POST",
		    url: "utility/checkUserExists.php",
		    data: {
			column: 'username',
			value: function() {
			    return document.forms['addUser'].elements['username'].value;
			}
		    }
		}
	    },
	    password: {
		required: true
	    },
	    confirmPassword: {
		required: true,
		equalTo: document.forms['addUser'].elements['password']
	    },
	    fname: {
		required: true
	    },
	    lname: {
		required: true
	    },
	    email: {
		required: true,
		email: true,
		remote: {
		    type: "POST",
		    url: "utility/checkUserExists.php",
		    data: {
			column: 'email',
			value: function() {
			    return document.forms['addUser'].elements['email'].value;
			}
		    }
		}
	    }
	},
	messages: {
	    username: {
		required: "Username is required"
	    },
	    password: {
		required: "Password is required"
	    },
	    confirmPassword: {
		required: "Password confirmation is required",
		equalTo: "These passwords don't match"
	    },
	    fname: {
		required: "First name is required"
	    },
	    lname: {
		required: "Last name is required"
	    },
	    email: {
		required: "Email address is required",
		email: "Please enter a valid email address"
	    }
	},
	submitHandler: function(form){
	    var submitBtn = $(form).find('button[type="submit"]');
	    var moduleIds = [];
	    
	    submitBtn.button('loading');
	    
	    $('#assignModulesNewModal .modulesAssignTable input[type="checkbox"]').each(function(){
		if($(this).prop('checked'))
		{
		    moduleIds.push($(this).attr('data-moduleId'));
		}
	    });
	    
	    $.ajax({
		method: "POST",
		url: "utility/addUser.php",
		data: {
		    username: form.elements['username'].value,
		    password: form.elements['password'].value,
		    fname: form.elements['fname'].value,
		    lname: form.elements['lname'].value,
		    email: form.elements['email'].value,
		    moduleIds: JSON.stringify(moduleIds),
		    admin: function(){
			if(form.elements['admin'].checked)
			{
			    return 1;
			}
			else
			{
			    return 0;
			}
		    }
		},
		success: function(response){
		    submitBtn.button('reset');
		    
		    if(response.status === "success")
		    {
			form.reset();
			deselectAllAssignedModules();
			
			$('#addUserSuccess').fadeIn();
			
			$(window).scrollTop($('#addUserPanel').offset().top);
			
			getUsers();
		    }
		    else
		    {
			alert('An error has occurred');
		    
			console.error(response.message);
		    }
		},
		error: function(response){
		    submitBtn.button('reset');
		    
		    alert('An error has occurred');
		    
		    console.error(JSON.stringify(response));
		}
	    });
	}
    });

    $('form[name="addModule"]').validate({
	rules: {
	    name: {
		required: true,
		remote: {
		    type: "POST",
		    url: "utility/checkModuleExists.php",
		    data: {
			username: function() {
			    return document.forms['addModule'].elements['name'].value;
			}
		    }
		}
	    },
	    pdf: {
		require_from_group: [1, ".pdf-group"],
		accept: "application/pdf"
	    },
	    text: {
		require_from_group: [1, ".pdf-group"]
	    }
        //correctAnswer: {
        //    required: function(element){
        //        return $(element).closest('.panel').find('[name="question"]').val() != "";
        //    }
        //},
        //otherAnswer1: {
        //    required: function(element){
        //        return $(element).closest('.panel').find('[name="correctAnswer"]').val() != "";
        //    }
        //}
	},
	messages: {
	    name: {
		required: "You must enter a name for the module"
	    },
	    pdf: {
		require_from_group: "PDF is required if you don't enter text",
		accept: "Please select a PDF file"
	    },
	    text: {
		require_from_group: "Text is required if you don't select a PDF"
	    },
	    correctAnswer: {
            required: 'You must write a correct answer to your question.'
        },
        otherAnswer1: {
            required: 'You must write at least one other answer to your question.'
        }
	},
	submitHandler: function(form){
	    var submitBtn = $(form).find('#submitModule');
	    var formData = new FormData(form);

	    submitBtn.button('loading');

	    $.ajax({
		method: "POST",
		url: "utility/addModule.php",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		success: function(response){
		    submitBtn.button('reset');

		    if(response.status === "success")
		    {
			form.reset();
			answerCount = 1;
			$('.extraQuestion').remove();
			$('#addAnswer').removeAttr('disabled');

			$('#addModuleSuccess').fadeIn();

			$(window).scrollTop($('#addModulePanel').offset().top);
			
			getModules();
		    }
		    else
		    {
			alert('An error has occurred');

			console.error(response.message);
		    }
		},
		error: function(response){
		    submitBtn.button('reset');

		    alert('An error has occurred');

		    console.error(JSON.stringify(response));
		}
	    });
	}
    });
    
    var questionCount = 0;

    function addQuestion() {
        $('.collapse').collapse()
        questionCount++;
        var $newQuestionDom = $('<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse' + questionCount + '">Question ' + questionCount + '</a></h4></div><div id="collapse' + questionCount + '" class="panel-collapse collapse in"><div class="row"><div class="form-group"><label class="col-md-2 control-label">Question ' + questionCount + '</label><div class="col-md-10"><input type="text" name="question[' + questionCount + ']" placeholder="Question" class="form-control"></div></div></div><div class="row"><div class="form-group"><label class="col-md-2 control-label">Correct answer</label><div class="col-md-10"><input type="text" name="correctAnswer[' + questionCount + ']" placeholder="Correct answer" class="form-control"></div></div></div><div class="row"><div class="form-group"><label class="col-md-2 control-label">Addition answer 1</label><div class="col-md-10"><input type="text" name="otherAnswer1[' + questionCount + ']" placeholder="Addition answer 1" class="form-control"></div></div></div><div class="row"><div class="form-group"><label class="col-md-2 control-label">Additional answer 2</label><div class="col-md-10"><input type="text" name="otherAnswer2[' + questionCount + ']" placeholder="Addition answer 2" class="form-control"></div></div></div><div class="row"><div class="form-group"><label class="col-md-2 control-label">Additional answer 3</label><div class="col-md-10"><input type="text" name="otherAnswer3[' + questionCount + ']" placeholder="Addition answer 3" class="form-control"></div></div></div></div></div></div>');
        $('#accordion').append($newQuestionDom);
        //$("[name^=question]").each(function () {
        //    $(this).rules("add", {
        //        required: true,
        //        checkValue: true
        //    });
        //});
        //$("[name^=otherAnswer1]").each(function () {
        //    $(this).rules("add", {
        //        required: true,
        //        checkValue: true
        //    });
        //});
        $("[name^=correctAnswer]").each(function () {
            $(this).rules("add", {
                required: function(element){
                    return $(element).closest('.panel').find('[name^="question"]').val() != "";
                }
                //checkValue: true
            });
        });
    }

    $('#addQuestion').on('click', function(e){
	e.preventDefault();
	e.stopPropagation();
	addQuestion();
    });

    addQuestion();
    
    $('#assignAllNewModules, #assignAllExistingModules').change(function(){
	if($(this).prop('checked'))
	{
	    $(this).closest('.modal').find('.modulesAssignTable input[type="checkbox"]').prop('checked', true);
	}
	else
	{
	    $(this).closest('.modal').find('.modulesAssignTable input[type="checkbox"]').prop('checked', false);
	}
    });
    
    $('.modulesAssignTable').on('change', 'input[type="checkbox"]', function(){
	var allChecked = true;
	
	$('.modulesAssignTable input[type="checkbox"]').each(function(){
	    if(!$(this).prop('checked'))
	    {
		allChecked = false;
		return false;
	    }
	});
	
	if(allChecked)
	{
	    $(this).closest('.assignAllModules').prop('checked', true);
	}
	else
	{
	    $(this).closest('.assignAllModules').prop('checked', false);
	}
    });
    
    $('#assignModulesExistingModal').bind('hidden.bs.modal', function(){
	$(this).find('.modulesAssignTable .alreadyAssigned').each(function(){
	    $(this).replaceWith('<input type="checkbox" data-moduleid="' + $(this).attr('data-moduleId') + '">');
	});
	
	deselectAllAssignedModules(this);
    });
});