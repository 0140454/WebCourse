<html>
	<head>
		<meta charset="utf-8">
		<title>F74022167 - Homework 2</title>
		
		<link href="css/style.css" rel="stylesheet">

		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/jquery.mousewheel.js"></script>
		<script type="text/javascript" src="js/scripts.js"></script>
	</head>
	<body>
		<!-- 導航欄 -->
		<nav class="navbar">
			<ul class="nav">
				<li><a class="nav-header" href=".">F74022167</a></li>
				<div class="nav-text nav-right">吳勃興</div>
			</ul>
		</nav>
		<!-- 內容 -->
		<div id="show">
			<img id="show_img" name = "custom"/><br>
			<input name="custom" onclick="GotoImage(-1);" type="button" value="上一張" />
			<input id="play" name="custom" onclick="Play();" type="button" value="播放幻燈片" />
			<input name="custom" onclick="GotoImage(1);" type="button" value="下一張" />
			<div style="padding-bottom: 30px;"></div>
		</div>
		<div class="container">
			<div id="preview">
				<?php
					$total_img = 12;
					
					for ($i = 0; $i < $total_img; $i++)
					{
						$img_path = "img/" . $i . ".jpg";
						$img_tag = "<img id=\"preview_img\" src=\"" . $img_path . "\" />";

						echo  "<div id=\"preview_div\"><a href=\"#\" onclick=\"ShowImage('" . $img_path . "');\">" . $img_tag . "</a></div>\n";
					}
				?>
			</div>
		</div>
	</body>
</html>