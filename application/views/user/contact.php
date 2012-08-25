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
<label class="contactlbl">Contact:</label>
<label><?php echo $admin['username']; ?></label>
<br /><br />
<label class="contactlbl">Phone:</label>
<label><?php echo $admin['phone']; ?></label>
<br /><br />
<label class="contactlbl">Email:</label>
<label><?php echo $admin['email']; ?></label>
<br /><br />
<br /><br />
<h3>Send a message below</h3>
<br />
<form id="frmcontact" method="post" action="<?php echo base_url(); ?>user/contact/send">
<label class="contactlbl">Subject:</label>
<input type="text" name="subject" value="<?php echo set_value('subject',''); ?>" />
<br /><br />
<label class="contactlbl">Message:</label>
<textarea name="message" cols="30" rows="10" ><?php echo set_value('subject',''); ?></textarea>
<br /><br />
<label class="contactlbl">&nbsp;</label>
<a class="button" onclick="$('#frmcontact').submit();return false" href="#">SEND</a>

</form>
