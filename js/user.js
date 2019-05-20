function getUserModules()
{
    $.ajax({
        method: "GET",
        url: "utility/getUserModules.php",
        success: function(response){
            if(response.status === "success")
            {
                var module;
                var tr;
                var type;
		
		if($('#assignedModules').hasClass('dataTable'))
                {
                    $('#assignedModules').DataTable().destroy();
                }

		$('#assignedModules tbody').html('');
		
		for(var i = 0; i < response.data.length; i++)
		{
		    module = response.data[i];

		    tr = '<tr>';
			tr += '<td>' + module.name + '</td>';
			tr += '<td class="checkTd">';
			    if(module.pdf == 1)
			    {
				type = "pdf";
				tr += '✔';
			    }
			    else
			    {
				type = "module";
				tr += '✘';
			    }
			tr += '</td>';
			tr += '<td class="checkTd">';
			    if(module.viewed == 1)
			    {
				tr += '✔';
			    }
			    else
			    {
				tr += '✘';
			    }
			tr += '</td>';
			tr += '<td class="buttonTd"><a href="' + type + '.php?moduleId=' + module.moduleId + '">\n\
				<input type="button" value="View" class="btn btn-primary btn-sm"></a>\n\
			</td>';
		    tr += '</tr>';

		    $('#assignedModules tbody').append(tr);
		}
		
		$('#assignedModules').DataTable({
		    scrollX: true,
		    bAutoWidth: false,
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

function getUserDetails()
{
    $.ajax({
        method: "GET",
        url: "utility/getUserDetails.php",
        success: function(response){
            if(response.status === "success")
            {
		var user = response.data;
		var tr;
		
		for(var detail in user)
		{
		    tr = '<tr>';
			tr += '<td>' + detail + '</td>';
			tr += '<td>' + user[detail] + '</td>';
		    tr += '<tr>';
		    
		    $('#userDetails').append(tr);
		}
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

$(function(){
    getUserModules();
    getUserDetails();
    
    $('form[name="editPassword"]').validate({
	rules: {
	    password: {
		required: true
	    },
	    confirmPassword: {
		required: true,
		equalTo: document.forms['editPassword'].elements['password']
	    }
	},
	messages: {
	    password: {
		required: "Password is required"
	    },
	    confirmPassword: {
		required: "Password confirmation is required",
		equalTo: "These passwords don't match"
	    }
	},
	submitHandler: function(form){
	    var submitBtn = $(form).find('button[type="submit"]');
	    
	    submitBtn.button('loading');
	    
	    $.ajax({
		method: "POST",
		url: "utility/editPassword.php",
		data: {
		    password: form.elements['password'].value
		},
		success: function(response){
		    submitBtn.button('reset');
		    
		    if(response.status === "success")
		    {
			form.reset();
			
			$('#editPasswordModal').modal('hide');
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
});