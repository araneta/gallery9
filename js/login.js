$(function(){  
	autoClear('username','Username');
	autoClear('password','Password');
	function autoClear(id,text){
		$('#'+id).blur(function(){	
		if($('#'+id).val()=='')
			$('#'+id).val(text);
		});
		$('#'+id).click(function(){		
			if($('#'+id).val()==text)
				$('#'+id).val('');
		});
	}
});
