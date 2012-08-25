<div class="titlepage">
	<h1><?php echo $title; ?></h1>	
</div>
<?php
		if ($this->session->flashdata('message')){
				echo "<div class='message'>";
				echo $this->session->flashdata('message');
				echo "</div>";
		}			
		$err = validation_errors();
		if(!empty($err))
			echo '<div class="error">'.$err.'</div>';
		
		?>	
<br /><br />
<form id="frmadminadmininfo" method="post" action="<?php echo base_url(); ?>admin/info/update">
<div style="float:left;width:45%">	
	<label>Username</label><br />	
	<div style="width:220px;float:left;padding:12px 0;"><?php echo $admin['username']; ?></div>	
	<div class="clear"></div>	
	<label>Phone</label>
	<div class="clear"></div>	
	<input type="text" name="phone" maxlength="20" value="<?php echo $admin['phone']; ?>" />
</div>
<div style="float:left;width:45%">
	<label>Email</label><br />
	<input type="text" name="email" maxlength="40" value="<?php echo $admin['email']; ?>" />
	<div class="clear"></div>	
	<label>Password</label><br />
	<input type="text" name="password" value="" />
	<div class="clear"></div>	
	<a class="button" onclick="$('#frmadminadmininfo').submit();return false" href="#">SAVE CHANGES</a>
</div>
</form>
