<?php
	require_once("include.inc.php");
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (strlen($_POST["oldpassword"]) && strlen($_POST["newpassword"]) && strlen($_POST["again"])) {
			$result = $database->query("SELECT * FROM users WHERE account='" . addslashes($_SESSION["account"]) . "' AND password='" . md5($_POST["oldpassword"]) . "'");
			$row = $result->fetch_array();
			
			if (!is_null($row) && $_POST["newpassword"] == $_POST["again"]) {
				$database->query("UPDATE users SET `password`='" . md5($_POST["newpassword"]) . "' WHERE account='" . $_SESSION["account"] . "'");
				echo "MODIFIED_SUCCESS";
				exit(0);
			}
		}
		
		echo "WRONG_INFO";
		exit(0);
	}
?>

<form>
	<div class="input-group"><span class="input-group-addon" >舊密碼　</span><input class="form-control" name="oldpassword" type="password">
	</div>
	<div class="input-group"><span class="input-group-addon">新密碼　</span><input class="form-control" name="newpassword" type="password">
	</div>
	<div class="input-group"><span class="input-group-addon">確認密碼</span><input class="form-control" name="again" type="password">
	</div>
</form>