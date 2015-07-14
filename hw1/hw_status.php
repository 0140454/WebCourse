<?php
	ini_set("display_errors", "off");
	
	include_once("func.inc.php");
?>

<html>
	<head>
		<meta charset="utf-8">
		<title>F74022167 - Homework 1 - 作業繳交狀態</title>
		
		<link href="css/style.css" rel="stylesheet">
		
		<style type="text/css">
			td {
				text-align: center;
			}
			
			a {
				color: blue;
			}
			
			#no-submitted {
				color: red;
			}
		</style>
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
			<table align="center" border="1" cellspacing="0">
				<?php
					list($student, $index) = FetchHWStatus();
					
					$submitted = 0;
					$no_submitted = 0;
					for ($i = 0; $i < count($student); $i++)
					{
						if (strlen($index[$i]))
							$submitted++;
						else
							$no_submitted++;
					}
					
					echo "<caption>已交：" . $submitted . " 人，未交：" . $no_submitted . " 人</caption>";
				?>
				<tr>
					<th style="width: 150px">學號</th>
					<th style="width: 170px">是否存在首頁檔案</th>
				</tr>
				<?php
					for ($i = 0; $i < count($student); $i++)
					{
						echo "<tr>\n";
						if (strlen($index[$i]))
							echo "<td><a href=\"" . $index[$i] . "\">" . strtoupper($student[$i]) . "</a></td>\n";
						else
							echo "<td>" . strtoupper($student[$i]) . "</td>\n";
						echo "<td" . ((strlen($index[$i])) ? "" : " id=\"no-submitted\"") . ">" . ((strlen($index[$i])) ? "是" : "否") . "</td>\n";
						echo "</tr>\n";
					}
				?>
			</table>
		</div>
	</body>
</html>