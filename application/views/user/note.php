<textarea id="txtnote" rows="5" cols="22"><?php if(!empty($text))echo $text;?></textarea>
<input type="hidden" id="hfidvolunteer" value="<?php if(!empty($id))echo $id;?>" />
<script type="text/javascript">
$(document).ready(function() {
<?php
	if(!empty($disablesave) && $disablesave==TRUE){				
		echo '$("#btnSave").button().hide();';
	}else{		
		echo '$("#btnSave").button().show();';
	}
?>
});
</script>
