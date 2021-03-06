<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $title; ?></title>			
        <link href="<?= base_url();?>css/default.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript">
		//<![CDATA[
		var base_url = '<?= base_url();?>';
		//]]>
	</script>
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.6.2.min.js"></script>
	
        <?php
        if(isset($jslang))
        {
        ?>
        <script type="text/javascript" src="<?php echo base_url();?>javalang/f/<?php echo $jslang; ?>"></script>
	<?php
        }
	if (isset($cssfiles) && count($cssfiles)){		
		foreach ($cssfiles as $css){
			echo "<link href=\"". base_url(). "css/$css\" rel=\"stylesheet\" type=\"text/css\" />";			
		}		
	} 
	if (isset($jsfiles) && count($jsfiles)){		
		foreach ($jsfiles as $js){
			echo "<script type=\"text/javascript\" src=\"".base_url()."js/$js\"></script>";			
		}		
	} 	
	?>	
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<?php $this->load->view("admin/user_header");?>
		</div>		
                <div class="maincontent">                    
                    <div id="main" >
                            <?php $this->load->view($main);?>
                    </div>					
                </div>
		<div id="footer">
			<?php $this->load->view("admin/user_footer");?>
		</div>
	</div>
</body>
</html>
