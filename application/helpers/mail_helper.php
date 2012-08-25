<?php
	/*param
	 * string @from
	 * string @from_name
	 * array string @tos
	 * string @subject
	 * string @body
	 * **/
	function send_email($param){
		/*
		$config = Array(
			'protocol' => 'smtp',
			'smtp_host' => 'smtp.gmail.com',
			'smtp_port' => 465,
			'smtp_crypto' => 'ssl',
			'smtp_user' => 'aldopraherda@gmail.com',
			'smtp_pass' => 'sakurabiyorigedubrak',
			'mailtype'  => 'html', 
			'charset'   => 'iso-8859-1'
		);  
		*/ 
		$config['protocol'] = 'sendmail';
		$config['mailpath'] = '/usr/sbin/sendmail';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;
		
		$CI =& get_instance();
		$CI->load->library('email');
		$CI->email->initialize($config);
		$CI->email->from($param['from'], $param['from_name']);		
		$CI->email->to($param['tos']);
		$CI->email->subject($param['subject']);
		$CI->email->message($param['body']);
		$ret = array("success"=> $CI->email->send(),"msg"=>$CI->email->print_debugger());
		return $ret;
		/*
		error_reporting(E_ALL);
		require_once(dirname(__FILE__).'/PHPMailer_5.2.1/class.phpmailer.php');
		$param['body'] = 'tes';
		$param['from'] = 'bejo@magicsoft-asia.com';
		$param['from_name'] = 'aldo';
		$param['subject'] = 'tes email';
		$param['tos'] = 'aldo@magicsoft-asia.com';
		
		$mail             = new PHPMailer();
		$body             = $param['body'];		
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->Host       = "mail.magicsoft-asia.com"; // SMTP server
		$mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
												   // 1 = errors and messages
												   // 2 = messages only
		$mail->SMTPAuth   = true;                  // enable SMTP authentication		
		$mail->Port       = 25;                    // set the SMTP port for the GMAIL server
		$mail->Username   = "aldo@magicsoft-asia.com"; // SMTP account username
		$mail->Password   = "P@ssw0rd";        // SMTP account password

		$mail->SetFrom($param['from'], $param['from_name']);

		$mail->AddReplyTo($param['from'], $param['from_name']);

		$mail->Subject    = $param['subject'];

		$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

		$mail->MsgHTML($body);

		
		$mail->AddAddress($param['tos'], "Gallery Admin");

		if(!$mail->Send()) {		  
		  $ret = array("success"=> FALSE,"msg"=>"Mailer Error: " . $mail->ErrorInfo);
		} else {
		  $ret = array("success"=> TRUE,"msg"=>"");
		}
		return $ret;
		*/ 
	}
?>
