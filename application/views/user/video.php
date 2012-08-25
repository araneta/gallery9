<div style="float:left;width:45%">	
	<div class="vidmenucont">
		<ul class="vidmenu">
			<li><div  class="vidsel"><a onclick="changevid(this,1);">How to Sign Up to Volunteer for a Day</a></div></li>
			<li><div><a onclick="changevid(this,2);">How to Delete a Sign-Up</a></div></li>
			<li><div><a onclick="changevid(this,3);">How to Change Your Contact Info</a></div></li>
			<li><div><a onclick="changevid(this,4);">How to Get Help</a></div></li>
		</ul>
	</div>
</div>
<div style="float:left;width:45%">
	<div class="swfcont" id="vidtutswf">
		
	</div>
</div>
<script type="text/javascript">
	function changevid(e,n){
		var content='';
		switch(n){
			case 1:
				content = '';
			break;
			case 2:
				content = '';
			break;
			case 3:
				content = '';
			break;
			case 4:
				content = '';
			break;
		}
		$('#vidtutswf').html(content);
		$('.vidsel').removeClass('vidsel');
		$(e).parent().addClass('vidsel');
	}
</script>
