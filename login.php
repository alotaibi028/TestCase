<?php
session_start();
require_once "database.php";
class Auth extends database
{
	function checkLogin($data)
	{
		if(trim($data['email']) == '' || trim($data['password']) == '' || !filter_var($data['email'], FILTER_VALIDATE_EMAIL))
		{
			return 0;
			exit;
		}
		$res = $this->fetchRow('users', $data);
		if($res)
		{
			$_SESSION['email'] = $res['email'];
			$_SESSION['name'] = $res['name'];
			return 1;
		}
		else
		{
			return 0;
		}
	}

	function register($data)
	{
		if(trim($data['name']) == '' || trim($data['email']) == '' || trim($data['password']) == '' || !filter_var($data['email'], FILTER_VALIDATE_EMAIL))
		{
			return 0;
			exit;
		} 
		return $this->insert('users', $data);
	}
}

$obj = new Auth();
$msg = '';
if(isset($_POST['data'])){
	$data = $_POST['data'];
	$res = $obj->checkLogin($data);	
	if($res)
	{
		header('Location: index.php');
	}
	else
	{
		$msg.= "Something Went Wrong while Lggoing You In";		
	}
}

if(isset($_POST['register'])){
	$data = $_POST['register'];
	$res = $obj->register($data);	
	if($res)
	{
		$msg.= "Registered Successfully";
	}
	else
	{
		$msg.= "Something Went Wrong while registering";		
	}
}


?>

<html>
<head>
<title>
Login Form
</title>
</head>
<link href="style.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
<body>
<div class="login-page">
  <div class="form">
    <form class="register-form" method="POST">
	<?php
	if(trim($msg) != '')
	{
		echo $msg;
	}
	?>
      <input type="text" placeholder="name" name="register[name]"/>
      <input type="password" placeholder="password" name="register[password]"/>
      <input type="email" placeholder="email address" name="register[email]"/>
      <button type="submit">create</button>
      <p class="message">Already registered? <a href="#">Sign In</a></p>
    </form>
    <form class="login-form" method="POST">
	<?php
	if(trim($msg) != '')
	{
		echo $msg;
	}
	?>
      <input type="email" placeholder="email" name="data[email]"/>
      <input type="password" placeholder="password" name="data[password]"/>
      <button type="submit">login</button>
      <p class="message">Not registered? <a href="#">Create an account</a></p>
    </form>
  </div>
</div>
<script>
$('.message a').click(function(){
   $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
});
</script>
</body>
</html>
