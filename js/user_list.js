$(document).ready(function(){			
	initPanels();
});
function edit_user(id,username,phone,email){	
	$('#hfedituserid').val(id);
	$('#txteditusername').val(username);
	$('#txteditusername2').html(username);
	$('#txteditphone').val(phone);
	$('#txteditemail').val(email);
}
function deleteuser(){
	var iduser = $('#hfedituserid').val();
	if(iduser=='')
		return;
	var answer = confirm('Are you sure you want to delete: '+$('#txteditusername').html()+'? This will also delete all their future sign-up dates.');	
	if (answer)
	{			
		$.post(
			base_url+'admin/user/delete',
			{
				iduser:iduser					
			},
			function(data){
				if(data!=null)
				{						
					var y = jQuery.parseJSON(data);
					location.reload();
				}
			}
		);
	}
	
}
