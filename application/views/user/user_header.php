
<div class="wrapper1">
	<div class="wrapper">
		<div class="toplogo">
			<img src="<?php echo base_url();?>/img/logo.jpg" />
		</div>
		<div class="nav-wrapper">			
			<div class="nav">
				<ul id="navigation" >
 					<li>
						<a href="<?php echo base_url(); ?>user/calendar/display">View Calendar</a>
 					</li>					
 					<li>
						<a href="<?php echo base_url(); ?>user/video">Watch Tutorials</a>
 					</li>					
 					<li>
						<a href="<?php echo base_url(); ?>user/info">View My Account</a>
 					</li>					
 					<li>
						<a href="<?php echo base_url(); ?>user/contact">Contact Admin</a>
 					</li>					
 					<li>
						<a href="<?php echo base_url(); ?>user/dashboard/logout">Log Out</a>
 					</li>					
			   	</ul>
			</div>			
			<div id="breadcrumb">
				<?php
				if(isset($breadcrumb)){
					echo '<ul class="breadcrumb">';
					foreach($breadcrumb as $bread){
						echo '<li><img src="'.$bread['icon'].'" /><a href="'.$bread['url'].'">'.$bread['txt'].'</a></li>';
					}
					echo '</ul>';
				}
				?>
			</div>		
		</div>
	</div>
</div>
