function printcal(){
	window.open(base_url+'user/calendar/printcal/'+$('#hfyear').val()+'/'+$('#hfmonth').val());
}
function printcaluser(){
	window.open(base_url+'user/calendar/printcaluser/'+$('#hfyear').val()+'/'+$('#hfmonth').val());
}
$(document).ready(function() {
	$('.volunteer a').click(function() {
		if($('#hfusername').val()!=$(this).text())
			return false;
		var day_num = $(this).parent().parent().prev().html();
		var selDate = day_num+'/'+$('#hfmonth').val()+'/'+$('#hfyear').val();
		var idvolunteer = $(this).parent().attr('idvolunteer');
		var answer = confirm('You are signed up for '+selDate+'. Do you want to delete this date?');
		if(!answer)
			return false;
		$.post(
			base_url+'user/calendar/unregister',
			{
				idvolunteer:idvolunteer,
				year:$('#hfyear').val(),
				month:$('#hfmonth').val(),
				day:day_num										
			},
			function(data){
				if(data!=null)
				{						
					var y = jQuery.parseJSON(data);
					alert(y.msg);
					if(y.success){												
						location.reload();
					}
				}
			}
		);
		return false;
	});
	
		
	
	$('.calendar .day_num').click(function() {			
		var day_num = $(this).html();										
		$.post(
			base_url+'user/calendar/register',
			{
				year:$('#hfyear').val(),
				month:$('#hfmonth').val(),
				day:day_num
			},
			function(data){
				if(data!=null)
				{						
					var y = jQuery.parseJSON(data);						
					//status:0 message
					//status:1 run function
					if(y.status==0){//msg
						alert(y.msg);
					}else if(y.status==1){//eval
						eval(y.msg);
					}
					if(y.success){
						location.reload();
					}
				}
			}
		);
	});
	$('.notelink').click(function(){
		var id = $(this).parent().attr('idvolunteer');
		if($(this).html()=='[+]'){
			entry_note(id,'add');
		}else{
			
			entry_note(id,'edit');
		}
		return false;
	});
	function saveNote(id,text){
		$.post(
			base_url+'user/calendarnote/savenote',
			{
				id:id,
				text:text								
			},
			function(data){
				if(data!=null)
				{						
					var y = jQuery.parseJSON(data);
					alert(y.msg);
					if(y.success){												
						location.reload();
					}
				}
			}
		);
	}
	$( "#note-form" ).dialog({
		autoOpen: false,
		height: 250,
		width: 280,
		modal: true,
		buttons: [
					{
						id:'btnSave',
						text:'Save',
						click: function() {
							var bValid = true;
							//var html =  tinyMCE.get('txtdirection').getContent(); 
							var html =  $('#txtnote').val();
							bValid = html!='';
							if ( bValid ) {                                                                                                                           
								saveNote($("#hfidvolunteer").val(),html);
								//$( this ).dialog( "close" );
							}
						}
					},
					{
						id:'btnCancel',
						text:'Close',
						click: function() {
							$( this ).dialog( "close" );
						}
					}
				]
		,
		close: function() {                    
		},
		open:function(){                
			var mode = $(this).data('mode');			
			if(mode=='add'){
				$( "#note-form" ).load(base_url+'user/calendarnote/addDlg/'+$(this).data('id'));                
				$( "#note-form" ).dialog('option','title','Add Note');
			}else if(mode=='edit'){
				$( "#note-form" ).load(base_url+'user/calendarnote/editDlg/'+$(this).data('id'));                
				$( "#note-form" ).dialog('option','title','Edit Note');
			}
		},
		beforeclose: function(event, ui) {
			//tinyMCE.get('txtdirection').setContent('');
			//tinyMCE.execCommand('mceRemoveControl', false, 'txtdirection');
			$('#txtnote').val('');
		}
	});
	function entry_note(id,mode){
		$('#note-form')
		.data('mode', mode) 
		.data('id', id) 	
		.dialog( "open" );
	}
	
});
