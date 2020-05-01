<?php
	//$login was declared in config file to prevent from  checking sessions
	$login = true;
	//included because we started sessions in that file so that sessions will be unique
	include("config-admin.php"); 
	if(isset($_REQUEST['action'])){
		var_dump("signout");
		$action = $_REQUEST['action'];
		if($action == 'signout'){
			session_destroy();
			header('location: '.URL.'login.php');
			exit();
		}
	}
	if(isset($_SESSION['admin_id'])){
		header('location: '.URL.'index.php');
		exit();
	}


?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>LOGIN DIGIKALA</title>
<script src="js/jquery.min.js"></script>
<link rel = "stylesheet" type="text/css" href="font/font.css"/>
<link rel = "stylesheet" type="text/css" href="css/admin.css?v=<?php echo time() ?>"/>
<link rel = "stylesheet" type="text/css" href="css/btn.css"/>

</head>

<body>

<div id = "login">
	<div class = "head">ورود به سامانه</div>
	<div class = "body">
		<div class = "error"></div>
		<div class = "wait">
			<div class = "spin"></div>
			<dic class = "wait-text">درحال بررسی</dic>
		</div>
		<div class="row">
			<div class = "name">نام کاربری</div>
			<div class = "input"><input type="text" id="username" placeholder="نام کاربری">
			</div>
		</div>  
		<div class="row">
			<div class = "name">رمز عبور</div>
			<div class = "input"><input type="password" id="password" placeholder="رمز عبور">
			</div>
		</div>
			<div class="row">
			<div class="btn btn-block btn-default" id="chklogin">ورود</div>
	</div>
	
	<script>
		$('#chklogin').click(function(){
			$('#login .body .row').hide();
			$('#login .body .wait').show();
			var u = $('#username').val();
			var p = $('#password').val();
			if(u != '' && p != ''){
				$.ajax({
					url:'function/login.php',
					type:'POST',
					data:'username=' + u + '&password=' + p,
					success: function(data){
						if(data != null){
							window.parent.location = 'index.php';
						} else {
							showerror(data);
						}
					}
				});
			} else {
				showerror('رمز عبور یا نام کاربری خالی است.');
			}
			function showerror(str){
				$('#login .body .row').show();
				$('#login .body .wait').hide();
				$('#login .body .error').show();
				$('#login .body .error').html(str);
				setTimeout(function(){
					$('#login .body .error').hide();
				},30000);
			}
		});
		
		
	</script>
	
	
		
	</div>
</div>

</body>
</html>