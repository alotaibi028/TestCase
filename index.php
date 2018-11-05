<?php
session_start();
if(!isset($_SESSION['email']))
{
	header('Location: login.php');
	exit;
}

echo 'Welcome ' . $_SESSION['name'] . '<br/>';
echo '<a href="logout.php">Logout</a>';

