<?php
	require_once("include.inc.php");
	
	if (!strlen($_GET["query"]))
	{
		Header("Location: .");
		exit(0);
	}
	
	if (!strlen($_GET["page"]))
		$_GET["page"] = 1;
?>

<html>
	<head>
		<meta charset="utf-8">
		<title>F74022167 - Homework 3 - Meta Search</title>
		
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/style.css" rel="stylesheet">
		
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		
		<script>
			function AddBookmark(title, url, desc, section_id) {
				$.ajax({
					url: "index.php",
					data: "title=" + title + "&desc=" + desc + "&url=" + url,
					type: "POST",
					dataType: "json",
					complete: function(data) {
						$("#btn-" + section_id).attr("class", "btn btn-sm btn-primary");
						$("#btn-" + section_id).attr("onclick", "DeleteBookmark('" + data.responseText + "', '" + section_id + "');");
						$("#btn-" + section_id).html("已經加入");
					}
				}); 
			}
			
			function DeleteBookmark(id, section_id) {
				$.ajax({
					url: "delete.php",
					data: "id=" + id,
					type: "GET",
					dataType: "json",
					complete: function(data) {
						$("#btn-" + section_id).attr("class", "btn btn-sm btn-warning");
						$("#btn-" + section_id).attr("onclick", "AddBookmark('" + encodeURI($("#href-" + section_id).html()) + "', '" + encodeURI($("#href-" + section_id).attr("href")) + "', '" + encodeURI($("#quote-" + section_id).html()) + "', '" + section_id + "');");
						$("#btn-" + section_id).html("加入書籤");
					}
				}); 
			}
		</script>
	</head>
	<body>
		<!-- 導航欄 -->
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-brand" href=".">線上書籤</a>
				</div>

				<div class="collapse navbar-collapse">
					<form action="search.php" class="navbar-form navbar-left" role="search">
						<div class="input-group">
							<input class="form-control" name="query" placeholder="Meta Search" type="text" value="<?php echo $_GET["query"]; ?>">
							<span class="input-group-btn">
								<button type="submit" class="btn btn-default">搜尋</button>
							</span>
						</div>
					</form>
					<ul class="nav navbar-nav navbar-right">
						<?php
							if (strlen($_SESSION["password"]))
								echo "<li><a href=\"profile.php\">" . $_SESSION["real_name"] . "</a></li>";
						?>
						<li><?php echo (strlen($_SESSION["password"])) ? "<a href=\"logout.php\">登出</a>" : "<a href=\"login.php\">登入</a>"; ?></li>
					</ul>
				</div>
			</div>
		</nav>
		<!-- 主要內容 --> 
		<div>
				<div style="padding-top: 50px; text-align: center;">
				<div style="height: calc(100% - 50px); overflow: auto;">
					<br>
					<?php
						$results = MetaSearch(urlencode($_GET["query"]), $_GET["page"]);
						for ($i = 0; $i < count($results); $i++)
						{
							if (strlen($_SESSION["password"]))
							{
								$sql_result = $connect->query("SELECT * FROM bookmarks WHERE owner=" . $_SESSION["id"] . " AND url='" . urlencode($results[$i][1]) . "'");
								$saved_bookmark[0] = $sql_result->fetch_array();
							}
							else
							{
								$saved_bookmark = SearchCookieBookmarks(json_decode(stripslashes($_SESSION["bookmarks"]), true), $results[$i][1], "url");
							}

							echo "<div class=\"post-box\" style=\"text-align: left;\">";
							if (strlen($saved_bookmark[0]["id"]))
								echo "<button class=\"btn btn-sm btn-primary\" onclick=\"DeleteBookmark('" . md5($saved_bookmark[0]["id"] . "delete") . "', '$i');\" id=\"btn-$i\" type=\"button\">已經加入</a></button><br><br>";
							else
								echo "<button class=\"btn btn-sm btn-warning\" onclick=\"AddBookmark('" . urlencode($results[$i][0]) . "', '" . urlencode($results[$i][1]) . "', '" . urlencode($results[$i][2]) . "', '$i');\" id=\"btn-$i\" type=\"button\">加入書籤</button><br><br>";
							echo "<a class=\"search-result\" href=\"" . $results[$i][1] . "\" id=\"href-$i\" target=\"_blank\">" . $results[$i][0] . "</a>";
							echo "<blockquote class=\"search-result\" id=\"quote-$i\" style=\"font-size: small;\">" . $results[$i][2] . "</blockquote>";
							echo "</div>";
							echo "<br>";
						}
						
						echo "<ul class=\"pagination\">";
						for ($offset = -3, $num = 0; $offset <= 6; $offset++)
						{
							if ($offset == -3 && $_GET["page"] - 1 > 0)
									echo "<li><a href=\"?query=" . urlencode($_GET["query"]) . "&page=" . urlencode($_GET["page"] - 1) . "\">&laquo;</a></li>";
									
							if ($_GET["page"] + $offset > 0)
							{					
								if ($offset == 0)
									echo "<li class=\"active\">";
								else
									echo "<li>";
								echo "<a href=\"?query=" . urlencode($_GET["query"]) . "&page=" . urlencode($_GET["page"] + $offset) . "\">" . ($_GET["page"] + $offset) . "</a>";
								echo "</li>";
								
								$num++;
							}
							
							if ($num == 7)
							{
								echo "<li><a href=\"?query=" . urlencode($_GET["query"]) . "&page=" . urlencode($_GET["page"] + 1) . "\">&raquo;</a></li>";
								break;
							}
						}
						echo "</ul>";
					?>
				</div>
			</div>
		</div>
	</body>
</html>
