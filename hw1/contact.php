<?php
	ini_set("display_errors", "off");
	
	if (isset($_POST["name"]))
	{
		if (strlen($_POST["name"]) && strlen($_POST["email"]) && strlen($_POST["text"]))
		{
			$fp = fopen("contact_data/" . $_POST["name"] . "-" . date("Y.m.d-H.i.s") . ".txt", "w");
			fwrite($fp, $_POST["gender"] . "\r\n");
			fwrite($fp, $_POST["email"] . "\r\n");
			fwrite($fp, $_POST["text"] . "\r\n");
			fclose($fp);
			
			echo "<script type=\"text/javascript\">\n";
			echo "alert(\"訊息已儲存!\");\n";
			echo "location.href=\"contact.php\";\n";
			echo "</script>\n";
			
			return;
		}
		else
		{
			echo "<script type=\"text/javascript\">\n";
			echo "alert(\"請輸入完整資料!\");\n";
			echo "location.href=\"contact.php\";\n";
			echo "</script>\n";
			
			return;
		}
	}
?>

<html>
	<head>
		<meta charset="utf-8">
		<title>F74022167 - Homework 1 - 與我聯絡</title>
		
		<link href="css/style.css" rel="stylesheet">
		
		<script type="text/javascript">
			function CheckData() {
				if (document.comment.name.value != "" && document.comment.email.value != "" && document.comment.text.value != "")
				{
					return true;
				}
				else
				{
					alert("請輸入完整資料!");
					return false;
				}
			}
		</script>
	</head>
	<body>
		<!-- 導航欄 -->
		<nav class="navbar">
			<ul class="nav">
				<li><a class="nav-header" href=".">F74022167</a></li>
				<li><a href="profile.html">個人檔案</a></li>
				<li><a href="hw_status.php">作業繳交狀態</a></li>
				<li><a href="links.html">常用連結</a></li>
				<li><a href="contact.php">與我聯絡</a></li>
				<div class="nav-text nav-right">吳勃興</div>
			</ul>
		</nav>
		<!-- 內容 -->
		<div class="container">
			<div style="width: 450px">
				<form method="POST" name="comment" onsubmit="return CheckData();">
					姓名<br>
					<input name="name" type="text"><br>
					性別<br>
					<select name="gender">
							<option value="male">男</option>
							<option value="female">女</option>
					</select><br>
					信箱<br>
					<input name="email" type="text"><br>
					想說的話<br>
					<textarea name="text" rows="20" cols="61" style="resize: none;"></textarea><br>
					<br><input style="float: right" type="submit" value="提交">
				</form>
			</div>
		</div>
	</body>
</html>