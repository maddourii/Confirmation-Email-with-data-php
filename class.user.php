<?php

require_once 'dbconfig.php';

class USER
{	

	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	
	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
	
	public function lasdID()
	{
		$stmt = $this->conn->lastInsertId();
		return $stmt;
	}
	
	public function register($uname,$lname,$email,$tel,$message,$code)
	{
		try
		{							
			
			$stmt = $this->conn->prepare("INSERT INTO tbl_users(userName,userLname,userEmail,userTel,message,tokenCode) 
			                                             VALUES(:user_name,:user_lname, :user_mail, :user_tel,:user_message, :active_code)");
			$stmt->bindparam(":user_name",$uname);
			$stmt->bindparam(":user_lname",$lname);
			$stmt->bindparam(":user_mail",$email);
			$stmt->bindparam(":user_tel",$tel);
			$stmt->bindparam(":user_message",$message);
			$stmt->bindparam(":active_code",$code);
			$stmt->execute();	
			return $stmt;
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}
		
	
	
	
	public function redirect($url)
	{
		header("Location: $url");
	}
	
	
	
	function send_mail($email,$message,$subject)
	{						
		require_once('mailer/class.phpmailer.php');
		$mail = new PHPMailer();
		$mail->IsSMTP(); 
		$mail->SMTPDebug  = 0;                     
		$mail->SMTPAuth   = true;                  
		$mail->SMTPSecure = "ssl";                 
		$mail->Host       = "smtp.gmail.com";      
		$mail->Port       = 465;             
		$mail->AddAddress($email);
		$mail->Username="YourMail";  
		$mail->Password="YourPassword.";            
		$mail->SetFrom('YourMail','Name');
		$mail->AddReplyTo("YourMail","Name");
		$mail->Subject    = $subject;
		$mail->MsgHTML($message);
		$mail->Send();
	}	
}