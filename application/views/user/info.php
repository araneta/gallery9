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
<form id="frmadmininfo" method="post" action="<?php echo base_url(); ?>user/info/update">
<div style="float:left;width:60%">	
	<label>Username</label>
	<div style="width:220px;float:left;padding:12px 0;"><?php echo $userinfo['username']; ?></div>	
	<div class="clear"></div>	
	<label>Phone</label>
	<input type="text" name="phone" maxlength="20" value="<?php echo $userinfo['phone']; ?>" />
	<div class="clear"></div>
	<label>Email</label>
	<input type="text" name="email"  maxlength="40" value="<?php echo $userinfo['email']; ?>" />
	<div class="clear"></div>
	<br />
	<label>&nbsp;</label>
	<a class="button" onclick="$('#frmadmininfo').submit();return false" href="#">SAVE CHANGES</a>
</div>
<div style="float:left;width:38%">
	<h3 style="float:left">Dates Volunteered</h3>
	<a target="_blank" style="margin-top:0;float:right;" href="<?php echo base_url();?>user/info/printinfo" class="button white" ><img src="<?php echo base_url(); ?>images/printer.png" /><span class="text">Print</span></a>
	<?php
	if(sizeof($datesvolunteered)>0){		
		echo '<ul class="ulperiods">';
		foreach($datesvolunteered as $periods){					
			echo '<li><div class="volitem"><img src="'.base_url().'images/flag.png" />';
			echo '<span>'.date_format(date_create($periods['period_date']), 'd F Y');
			echo ' - ' .date_format(date_create($periods['period_date_end']), 'd F Y').'</span></div>';
			$ndates = sizeof($periods['dates']);
			if($ndates>0){
				echo '<ul class="uldates">';
				$d=0;
				foreach($periods['dates'] as $date){
					$last = '';
					if($d==$ndates-1)
						$last = 'style="border-bottom:none;"';
					echo '<li ><div class="volitem" '.$last.'><span>'.date_format(date_create($date['date']), 'l jS F Y' ).'</span></div></li>';
					
					$d++;
				}
				echo '</ul>';
			}
			echo '</li>';
		}
		echo '</ul>';
	}
	?>
	
</div>
</form>
