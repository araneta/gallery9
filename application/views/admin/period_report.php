<style>
	tr.even{background-color:#eee;}
	table
	{
	border-collapse:collapse;
	} 
</style>

<div class="hideprint">
	<form method="post" action="<?php echo base_url();?>admin/periodreport/display">
		<label>Period</label>
		<select name="periodid">
			<?php
				foreach($periods as $period){
					$iselected = '';
					if(isset($selperiod) && $selperiod['id']==$period['id'])
						$iselected = 'selected = "selected"';
					echo '<option value="'.$period['id'].'" '.$iselected.'>'.$period['period_date'].' - '.$period['period_date_end'].'</option>';
				}
			?>
		</select>
		<input type="submit" value="View" />
	</form>
</div>
<br />
<div style="width:700px;margin:0 auto;">
	<h3>
		<?php 
		if(isset($selperiod)){ 
			echo date_format(date_create($selperiod['period_date']), 'F d, Y') .' to '. date_format(date_create($selperiod['period_date_end']), 'F d, Y'); 
		}
		?>
	</h3>
	<table cellpadding="6">
		<tr>
			<td style="border-bottom:thin solid #000;" align="center" colspan="2"><strong>Empty Dates:</strong></td>
		</tr>
		<?php
		if(isset($empty_dates)){
			$c = 'odd';
			foreach($empty_dates as $emptydate){
				if($c=='odd')
					$c='even';
				else
					$c='odd';
				echo '<tr class="'.$c.'">
						<td width="350">'.date_format(date_create($emptydate), 'l' ).'</td>
						<td width="350">'.date_format(date_create($emptydate), 'd F Y' ).'</td>
					</tr>';
			} 
		}
		?>
	</table>
	<br /><br />
	<br /><br />
	<table cellpadding="6">
		<tr>
			<td style="border-bottom:thin solid #000;" align="center" colspan="3"><strong>Missing Sign-ups, (<img src="<?php echo base_url();?>images/check3.gif" /> means only 1 sign up)</strong></td>
		</tr>
		<?php
		if(isset($missing_signup)){
			$c = 'odd';
			foreach($missing_signup as $missing){
				if($c=='odd')
					$c='even';
				else
					$c='odd';
				echo '<tr class="'.$c.'">
						<td width="350">'.$missing['username'].'</td>
						<td width="350">'.$missing['phone'].'</td>
						<td width="350">'.($missing['nsignup']==1?'<img src="'. base_url().'images/check3.gif" />':'').'</td>
					</tr>';
			} 
		}
		?>
	</table>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		//window.print();
	});
</script>
