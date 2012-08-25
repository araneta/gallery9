function printcal(){
	window.open(base_url+'admin/calendar/printcal/'+$('#hfyear').val()+'/'+$('#hfmonth').val());
}
function printcaluser(){
	window.open(base_url+'admin/calendar/printcaluser/'+$('#hfyear').val()+'/'+$('#hfmonth').val());
}
function setMeeting(day_num){
	$.post(
		base_url+'admin/calendar/setmeeting',
		{
			year:$('#hfyear').val(),
			month:$('#hfmonth').val(),
			day:day_num
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
$(document).ready(function() {
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
				$( "#note-form" ).dialog('option','title','Note');
			}else if(mode=='edit'){
				$( "#note-form" ).load(base_url+'user/calendarnote/editDlg/'+$(this).data('id'));                
				$( "#note-form" ).dialog('option','title','Note');
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
	$('.notelink').click(function(){
		var id = $(this).parent().attr('idvolunteer');
		if($(this).html()=='[+]'){
			entry_note(id,'add');
		}else{
			
			entry_note(id,'edit');
		}
		return false;
	});
	$('.calendar .closed').click(function() {
		var day_num = $(this).parent().parent().find('.day_num').html();
		if(day_num.length==1)
			day_num = '0'+day_num;				
		setMeeting(day_num);		
	});
	$('.calendar .day_num').click(function() {
		var id = $(this).parent().find('.content div').attr('idperiod');
		var day_num = $(this).parent().find('.day_num').html();			
		
		//add new period
		if(id==null)
		{
			var answer = confirm ("Add Flag to designate New Period?");
			if (answer)
			{					
				$.post(
					base_url+'admin/calendar/addperiod',
					{
						year:$('#hfyear').val(),
						month:$('#hfmonth').val(),
						day:day_num
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
				
	});
	$('.calendar .flagperiod').click(function() {
		var answer = confirm('Delete Period Flag?');
			if (answer)
			{
				day_num = $(this).parent().parent().find('.day_num').html();
				$.post(
					base_url+'admin/calendar/delperiod',
					{
						year:$('#hfyear').val(),
						month:$('#hfmonth').val(),
						day:day_num
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
	});
	
});
