<style>
ol, ul {
    list-style: none outside none;
    margin:0;
    padding:0;
}	
.clear {
    clear: both;
}
.volitem{
	border-bottom:thin solid #585858;
}
.ulperiods span{
	font-size: 14px;
	font-weight:bold;
}

.ulperiods li .volitem{
	padding:10px 0;
	clear:both;
}
.uldates li .volitem{
	padding:6px 0;	
}
.uldates span{
	font-size: 12px;	
	font-weight:normal;
}
label{
	float:left;
	width:80px;
	padding:12px 0;
}
input,.input{
	width:220px;
	margin-left:20px;
	border:none;
	float:left;
	padding:12px 0;
}

</style>
<div class="titlepage">
	<h1><?php echo $title; ?></h1>	
</div>
<br /><br />

<div style="float:left;width:60%">	
	<label>Username</label>
	<div class="input" ><?php echo $userinfo['username']; ?></div>	
	<div class="clear" ></div>	
	<label>Phone</label>
	<input type="text" name="phone" maxlength="20" value="<?php echo $userinfo['phone']; ?>" />
	<div class="clear" ></div>
	<label>Email</label>
	<input type="text" name="email"  maxlength="40" value="<?php echo $userinfo['email']; ?>" />
	<div class="clear" ></div>
	<br />
		
</div>
<div style="float:left;width:38%">
	<h3 style="float:left">Dates Volunteered</h3>
	
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

