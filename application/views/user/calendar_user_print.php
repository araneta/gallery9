<style>
	table{
		border-collapse:collapse;
	}
	td{		
		font-family: Arial;
		font-size: 13px;

	}
	.odd{
		background-color:#efefef;
	}
	tr.header{
		border-bottom:thin solid #000;
	}
	tr.header td{
		font-weight:bold;
	}
	.titlepage{
		margin:0 auto;
		text-align:center;
	}
	#main{
		margin:0 auto;
		width:700px;
	}
</style>
<div class="titlepage">	
	<h1><?php echo $title .' - '. date_format(date_create("$year-$month"), 'F Y'); ?></h1>
</div>
<?php

//var_dump($user);
if(sizeof($users)>0){
	$class = 'odd';
	echo "<table cellpadding=\"6\">";
	echo "<tr class=\"header\">
		<td width=\"200\">Date</td>
		<td width=\"200\">Username</td>
		<td width=\"100\">Phone</td>
		<td width=\"300\">Note</td>
		</tr>";
	foreach($users as $user){
		echo "<tr class=\"$class\">"; 
		echo "<td>".substr(date_format(date_create($user->date), 'l'),0,2).' ';
		echo date_format(date_create($user->date), 'd F Y')."</td>";
		echo "<td>".$user->username."</td>";
		echo "<td>".$user->phone."</td>";
		echo "<td>".$user->note."</td>";
		echo "</tr>";
		if($class=='odd')
			$class='even';
		else
			$class='odd';
		
	}
	echo "</table>";
}
?>
