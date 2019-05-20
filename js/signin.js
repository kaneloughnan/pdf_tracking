function loginMessage(message, type)
{
    $('#loginMessage').addClass(type);
    $('#loginMessage').html(message);
    $('#loginMessage').show();
}

$(function(){
    $('form[name="login"]').validate({
	submitHandler: function(form){
	    $.ajax({
		method: "POST",
		url: "utility/login.php",
		data: {
		    username: form.elements['username'].value,
		    password: form.elements['password'].value
		},
		success: function(response){
		    if(response.status === "success")
		    {
			location.href = 'index.php';
		    }
		    else if(response.status === "fail")
		    {
			loginMessage('Incorrect username or password', 'error');
			
			form.elements['password'].value = "";
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
    });
});