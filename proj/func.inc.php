<?php
	function IsLoggedIn()
	{
		return (strlen($_SESSION["password"]) && strlen($_SESSION["account"]) &&
		        strlen($_SESSION["name"]));
	}
?>