<div id="signin_form">
	<img src="img/logo.jpg" />
	<br /><br />
	<?php
		if ($this->session->flashdata('message')){
				echo "<div class='error'>";
				echo $this->session->flashdata('message');
				echo "</div>";
		}		
	?>	
	<br /><br />
	<div>
		<span>Please login below</span><br />
		<span>by entering your username and password</span>	
	</div>
	<div id="logininput" style="width:200px;margin:0 auto;">
		<?php echo form_open('login/verify','id="frmlogin"'); ?>
			<fieldset>	     
				<br /><br />    			
				<?php echo form_input('username','Username','id="username"'); ?>
				<br /><br />                        
				<?php echo form_password('password','Password','id="password" '); ?>
				<br /><br />            
				<div id="rememberbox" >
					<input type="checkbox" name="remember"  /><label for="remember">Remember me</label>
				</div>
				<div id="submitbox" style="float:right">					
					<a class="button" onclick="$('#frmlogin').submit();return false;">&nbsp;&nbsp;Login&nbsp;&nbsp;</a>
				</div>				
				<br /><br />                       
			</fieldset>
		<?php echo form_close(); ?>
	</div>               
	<br /><br />
	<div>
		<span class="small">You should not save password information<br />
		on public or shared computers</span><br /><br />
		<span class="small">Need help? <a href="mailto:<?php echo $admininfo['email'];?>">Contact Admin</a></span>
	</div>
</div>
