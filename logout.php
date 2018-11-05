<?php
session_start();


class Logout
{
	function logMeOut()
	{
		// remove all session variables
		session_unset(); 

		// destroy the session 
		session_destroy(); 
		return 1;
	}
}

$obj = new Logout();
$obj->logMeOut();

header('Location: login.php');
exit;
