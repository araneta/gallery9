<div class="titlepage">
	<h1><?php echo $title; ?></h1>	
	<div style="float:left;"> 
		<span>Click on a date to sign up. We need 1 volunteer per day. Each period is designated by a <img src="<?php echo base_url();?>/images/flag.png" /> red flag.<br />
		Please sign up for 2 dates per period. If you need help, please <a href="<?php echo base_url(); ?>user/contact">contact the admin</a>.
		</span>
	</div>
	<div style="float:right;">
		<a onclick="printcal();" class="button white"><img src="<?php echo base_url(); ?>images/printer.png" /><span class="text">Print Calendar</span></a>		
		<a onclick="printcaluser();" style="margin-left:10px;" class="button white"><img src="<?php echo base_url(); ?>images/printer.png" /><span class="text">Print Notes</span></a>
	</div>
</div>
<br />
<br />
<?php echo $calendar; ?>

<input type="hidden" id="hfyear" value="<?php echo $year;?>" />
<input type="hidden" id="hfmonth" value="<?php echo $month;?>" />
<input type="hidden" id="hfusername" value="<?php echo $username;?>" />	
<div id="note-form"></div>
