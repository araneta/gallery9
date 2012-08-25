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
<div style="float:left;width:68%">		
	<div class="panel" id="userlistpanel">
            <h3 class="pnltitle pane-toggler-down">
                <a href="javascript:void(0);">
                    <span>User List</span>
                </a>
            </h3>
            <div class="pnlcontent pane-down" >   
				<form id="frmupdate" action="<?php echo base_url(); ?>admin/user/update" method="post">
				<table cellspacing="10">					
						<tr>
							<th>Action</th>
							<th >Username</th>
							<th >Phone</th>	
							<th >Email</th>	
						</tr>
					<?php
					for($i=0,$n=sizeof($users);$i<$n;$i++){
						$user = $users[$i];						
						echo '<tr>';
						echo '<td><a onclick="edit_user('.$user['id'].',\''.$user['username'].'\',\''.$user['phone'].'\',\''.$user['email'].'\')">Edit</a></td>';
						echo '<td >'.$user['username'].'</td>';						
						echo '<td >'.$user['phone'].'</td>';	
						echo '<td >'.$user['email'].'</td>';											
						echo '</tr>';
					}
					?>					
					
				</table>
				</form>
			</div>                
	</div>   
	<div class="clear" ></div>            	
</div>

<div style="float:right;width:30%">
	<a target="_blank" href="<?php echo base_url();?>admin/periodreport/display/" class="button white" ><img src="<?php echo base_url(); ?>images/printer.png" /><span class="text">VIEW PRINTABLE REPORT</span></a>
	<div class="clear" ></div>            	
	<br /><br />
	<div class="panel" id="edituserpanel">
            <h3 class="pnltitle pane-toggler-down">
                <a href="javascript:void(0);">
                    <span>Edit User</span>
                </a>
            </h3>
            <div class="pnlcontent pane-down" >   
				<form id="frmupdate" action="<?php echo base_url(); ?>admin/user/update" method="post">
					<label>Username</label>
					<div id="txteditusername2" style="width:300px;padding-top:6px;clear:both"><?php echo set_value('txteditusername', ''); ?></div>
					<input type="hidden" name="txteditusername" id="txteditusername" size="10" maxlength="20" value="<?php echo set_value('txteditusername', ''); ?>" />
					<br/>				
					<label>Phone</label>
					<input type="text" id="txteditphone" name="txteditphone"  size="10" maxlength="20"  value="<?php echo set_value('txteditphone', ''); ?>" />
					<br/>			
					<label>Email</label>
					<input type="text" id="txteditemail" name="txteditemail"  size="10" maxlength="40" value="<?php echo set_value('txteditemail', ''); ?>" />
					<input type="hidden" id="hfedituserid" name="hfedituserid" value="" />
					
					<a href="#" onclick="$(this).parent().submit();return false;" class="button">SAVE CHANGES</a>													
					<a href="#" style="margin-left:10px;" onclick="deleteuser();return false;" class="button">DELETE</a>
				</form>
			</div>                
	</div>   
	<div class="clear" ></div>    
	<br /><br />
	<div class="panel" id="adduserpanel">
            <h3 class="pnltitle pane-toggler-down">
                <a href="javascript:void(0);">
                    <span>Add New User</span>
                </a>
            </h3>
            <div class="pnlcontent pane-down" >    
				<form id="frmadduser" method="post" action="<?php echo base_url(); ?>admin/user/add">
					<label>Username</label><br />
					<input name="username" type="text" value="<?php echo set_value('username', ''); ?>" maxlength="20" />
					<br />
					<label>Phone</label><br />
					<input name="phone" type="text" value="<?php echo set_value('phone', ''); ?>" maxlength="20" />
					<br />
					<label>Email</label><br />
					<input name="email" type="text" value="<?php echo set_value('email', ''); ?>" maxlength="40"/>
					<br />
					<a class="button" onclick="$('#frmadduser').submit();return false" href="#">ADD NEW USER</a>
				</form>
            
			</div>                
	</div>   
	<div class="clear" ></div>  
	<br />          	
	<br />          	
	<div class="panel" id="editpassuserpanel">
            <h3 class="pnltitle pane-toggler-down">
                <a href="javascript:void(0);">
                    <span>Edit Password</span>
                </a>
            </h3>
            <div class="pnlcontent pane-down" >    
				<form id="frmpassuser" method="post" action="<?php echo base_url(); ?>admin/user/change_all_user_pass">
					<label>Password</label><br />
					<input name="password" type="text" value=""/>
					<br />					
					<a class="button" onclick="$('#frmpassuser').submit();return false" href="#">SAVE CHANGES</a>
				</form>
            
			</div>                
	</div>   
	<div class="clear" ></div> 	
</div>
