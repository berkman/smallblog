function rUser()
{
	var user = document.resetUser.user.value;
	
	if (user != "")
	{
		check = confirm("Reset user " + user + "?");
		
		if (check)
		{
			document.resetUser.action = document.resetUser.action + '?u=' + user;
			document.resetUser.submit();
		
			return true;
		}
		else
			document.resetUser.user.value = "";
	}
	else
		alert('Select a user to reset.');
	
	return false;
}

function dUser()
{
	var user = document.deleteUser.user.value;
	
	if (user != "")
	{
		check = confirm("Delete user " + user + "?");
		
		if (check)
		{
			document.deleteUser.action = document.deleteUser.action + '?u=' + user;
			document.deleteUser.submit();
		
			return true;
		}
		else
			document.deleteUser.user.value = "";
	}
	else
		alert('Select a user to delete.');
	
	return false;
}