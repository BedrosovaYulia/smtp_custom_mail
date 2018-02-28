<?
function custom_mail($to, $subject, $message, $additionalHeaders = '', $additional_parameters='')
{

require_once __DIR__ . '/phpmailer/PHPMailerAutoload.php';
CModule::IncludeModule('mail');
CModule::IncludeModule('crm');
CModule::IncludeModule('iblock');
global $DB;

	//define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/NEW_MAIL.txt");
	//AddMessage2Log($to, "to");
	//AddMessage2Log($additionalHeaders, "ADDITIONAL_HEADER");



		$mail = new PHPMailer();
		$mail->CharSet = 'UTF-8';
		$mail->IsSMTP();
		$mail->Timeout=30;
		$mail->Host = "smtp.gmail.com";
		//$mail->Port = 587;
		$mail->Port = 465;
		//$mail->SMTPDebug = 2;
		$mail->SMTPAuth   = true;
		//$mail->SMTPSecure   = "tls";
		$mail->SMTPSecure   = "ssl";
		$mail->Username = "xxxxxx@gmail.com";		
		$mail->Password = 'xxxxxx';
		$mail->SetFrom("xxxxxx@gmail.com");
		$mail->Subject = $subject;
		$arr = explode("\n", $additionalHeaders);
		$mail->Body = $message;
		$mail->AltBody  =  $message;
		$mail->IsHtml(true);
		$mail->CharSet = "text/html; charset=UTF-8;";

		if (is_array($arr))
		{
			foreach ($arr as $key => $value) {

				//$message=$value.PHP_EOL.$message;

				$arrr = explode(":", $value);
				if (is_array($arr))
				{
					foreach ($arr as $key => $value) 
					{
		
						//$message=$value.PHP_EOL.$message;
		
						$arrr = explode(":", $value);
						if(strtolower($arrr[0])=="cc") 
						{
							$copies=str_replace(" ", "", $arrr[1]);
							$copies=explode(",", $copies);
							foreach($copies as $copy)
							{
									$mail->AddCC($copy);	
							}
						}
						elseif(strtolower($arrr[0])=="bcc")
						{
							$copies=str_replace(" ", "", $arrr[1]);
							$copies=explode(",", $copies);
							foreach($copies as $copy)
							{
									$mail->AddBCC($copy);	
							}
						}
						elseif($arrr[0] == 'Content-Type')
						{
							$mail->ContentType = $arrr[1];
						}
						elseif($arrr[0] == 'X-Bitrix-Posting')
						{
							$is_posting_field_set=true;
							$mail->addCustomHeader($arrr[0], $arrr[1]);
						}
						else 
						{
							//do nothing
						}
					}
				}
			}
		}

		$to_array = explode(",", $to);
		if(count($to_array)>1)
		{
			foreach ($to_array as $key=>$val)
			{
				$val = trim($val);
				$to_array[$key]=$val;
				if($key==0) $mail->AddAddress($val);
				else $mail->AddCC($val);
			}
			
		}
		else
		{
			$mail->AddAddress($to);
		}



		if(!$is_posting_field_set) 
		{
			$mail->addCustomHeader("X-Bitrix-Posting", "1");
		}

		$status = $mail->Send();

	//AddMessage2Log($mail, "mail");
		
	if (!$status) 
	{
		//return mail($to, $subject, $message, $additionalHeaders, $additional_parameters);	 
		return false;
	}
	else return true;
}?>