<?php
	require_once("include.inc.php");
	
	if (strlen($_SESSION["password"]))
	{
		$connect->query("DELETE FROM bookmarks WHERE md5(concat(id, 'delete'))='" . $_GET["id"] . "'");
	}
	else
	{
		$bookmarks = json_decode(stripslashes($_SESSION["bookmarks"]), true);
		for ($i = 0; $i < count($bookmarks); $i++)
		{
			if (md5($bookmarks[$i]["id"] . "delete") == $_GET["id"])
			{
				$results = array_slice($bookmarks, 0, $i);
				$results = array_merge($results, array_slice($bookmarks, $i + 1));
				$_SESSION["bookmarks"] = json_encode($results, true);
				break;
			}
		}
	}
	
	Header("Location: " . ((strlen($_GET["ref"])) ? $_GET["ref"] : "."));
?>
