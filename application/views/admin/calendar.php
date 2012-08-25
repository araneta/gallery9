	
<div class="titlepage">
	<h1><?php echo $title; ?></h1>	
	<div style="float:left;">
		<span>Click on a date (number) to designate a new period.  Click <img src="<?php echo base_url();?>/images/flag.png" /> to delete the period flag.<br/> Click a username to delete the user.</span>		
	</div>
	<div style="float:right;">
		<a onclick="printcal();" class="button white"><img src="<?php echo base_url(); ?>images/printer.png" /><span class="text">Print Calendar</span></a>		
		<a onclick="printcaluser();" style="margin-left:10px;" class="button white"><img src="<?php echo base_url(); ?>images/printer.png" /><span class="text">Print Notes</span></a>
	</div>
</div>
<br />
<br />
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
<?php echo $calendar; ?>
<input type="hidden" id="hfyear" value="<?php echo $year;?>" />
<input type="hidden" id="hfmonth" value="<?php echo $month;?>" />	
<div id="note-form"></div>
