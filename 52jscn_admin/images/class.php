<?php
include_once("smtp.php");
class SendMail2 extends smtp_class
{
	var $sm_host_name = "smtp.163.com"; /* relay SMTP server address */
	var $sm_localhost = "localhost";/* this computer address */
	var $sm_from = "dedecms_register@163.com";
	var $sm_direct_delivery = 0;/* Set to 1 to deliver directly to the recepient SMTP server */
	var $sm_debug = 0;/* Set to 1 to output the communication with the SMTP server */
	var $sm_user = "dedecms_register";/* Set to the user name if the server requires authetication */
	var $sm_realm = "";/* Set to the authetication realm, usually the authentication user e-mail domain */
	var $sm_password = "dedecms";/* Set to the authetication password */
	
	function send($to,$subject,$cont)
	{
		if(!function_exists("GetMXRR"))
		{
			/*
			 * If possible specify in this array the address of at least on local
			 * DNS that may be queried from your network.
			 */
			$_NAMESERVERS=array();
			//include_once("D:\phpstudy\class\email\getmxrr.php");
		}
		include_once("smtp.php");
		global  $paypal_email ;
		global  $send_email_account;
		global  $send_email_smtp ;
		global  $send_email_password;
		global  $send_email_suffix ;	
			
		$smtp = new smtp_class();
		$smtp->host_name=$this->sm_host_name; /* relay SMTP server address */
		$smtp->localhost=$this->sm_localhost; /* this computer address */
		$from=$this->sm_from;
		$smtp->direct_delivery=$this->sm_direct_delivery; /* Set to 1 to deliver directly to the recepient SMTP server */
		$smtp->debug=$this->sm_debug; /* Set to 1 to output the communication with the SMTP server */
	    $smtp->user=$this->sm_user; /* Set to the user name if the server requires authetication */
		$smtp->realm=$this->sm_realm; /* Set to the authetication realm, usually the authentication user e-mail domain */
		$smtp->password=$this->sm_password; /* Set to the authetication password */
				
		$mail_boundary = '--=nextpart_' . md5(uniqid(time()));
		$mail_body = "--$mail_boundary\r\n";
		$mail_body .= "Content-type: text/html; charset=gb2312\r\n";
		$mail_body .= "Content-transfer-encoding: 8bit\r\n\r\n";
				
	  if( $db_file != "")
		{
			$mail_body .= "--$mail_boundary\r\n";	
			$filename = basename($db_file);
			$mail_body .= "Content-type: application/octet-stream; name=\"$filename\"\r\n";
			$mail_body .= "Content-transfer-encoding:base64\r\n";
			$mail_body .= "Content-Disposition: attachment; filename=\"$filename\"\r\n\r\n";
			$mail_body .= $file. "\r\n\r\n";
					
			if( $db_file2 != "")
			{	
				$mail_body .= "--$mail_boundary\r\n";			
				$filename2 = basename($db_file2);
				$mail_body .= "Content-type: application/octet-stream; name=\"$filename2\"\r\n";
				$mail_body .= "Content-transfer-encoding:base64\r\n";
				$mail_body .= "Content-Disposition: attachment; filename=\"$filename2\"\r\n\r\n";
				$mail_body .= $file2. "\r\n\r\n";
			}
			if( $db_file3 != "")
			{	
				$mail_body .= "--$mail_boundary\r\n";			
				$filename3 = basename($db_file3);
				$mail_body .= "Content-type: application/octet-stream; name=\"$filename3\"\r\n";
				$mail_body .= "Content-transfer-encoding:base64\r\n";
				$mail_body .= "Content-Disposition: attachment; filename=\"$filename3\"\r\n\r\n";
				$mail_body .= $file3. "\r\n\r\n";
			}
		}
		else 
		{
			$mail_body .= "$cont \r\n";
		}
		$mail_body .= "--$mail_boundary--";
		$mail_header = array();
		array_push($mail_header,"From: $from");
		array_push($mail_header,"To: $to");
		array_push($mail_header,"Subject: $subject");
		array_push($mail_header,"MIME-Version: 1.0");
	  array_push($mail_header,"Content-type: multipart/mixed; boundary=\"$mail_boundary\"\r\n");
		array_push($mail_header,"This is a multi-part message in MIME format.");
		array_push($mail_header,"Date: ".strftime("%a, %d %b %Y %H:%M:%S %Z") );
		$aa=$smtp->SendMessage($from,array($to),$mail_header,$mail_body );
		return true;
	}
}
?>
