<?php
	ini_set("display_errors", "off");
	
	function GetFtpHandle()
	{
		if (($ftp = ftp_connect("localhost")) !== false)
		{
			if (ftp_login($ftp, "f74022167", "s4004480"))
			{
				if (ftp_chdir($ftp, "hw2/img"))
				{
					return $ftp;
				}
			}
			
			ftp_close($ftp);
		}
		
		return false;
	}
	function GetComments($id) {
		$temp = fopen('php://temp', 'r+');
		$ftp = GetFtpHandle();
		
		if ($ftp !== false)
		{
			if (ftp_fget($ftp, $temp, $id . ".comment", FTP_BINARY, 0))
			{
				rewind($temp);
						
				$result = stream_get_contents($temp);
						
				fclose($temp);
				ftp_close($ftp);	
				return $result;
			}
			
			ftp_close($ftp);
		}

		fclose($temp);
		return false;
	}
	
	function SaveComment($id, $comment) {
		$temp = fopen('php://temp', 'r+');
		$ftp = GetFtpHandle();
		
		if ($ftp !== false)
		{
			ftp_fget($ftp, $temp, $id . ".comment", FTP_BINARY, 0);
			
			fwrite($temp, $comment . "\r\n");
			rewind($temp);
			
			if (ftp_fput($ftp, $id . ".comment", $temp, FTP_BINARY))
			{
				fclose($temp);
				ftp_close($ftp);	
				return true;
			}
			
			ftp_close($ftp);
		}

		fclose($temp);
		return false;
	}
?>