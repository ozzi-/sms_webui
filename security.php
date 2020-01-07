<?php
	header("X-XSS-Protection: 1; mode=block");
	header("X-Content-Type-Options: nosniff"); 
	header("X-Frame-Options: DENY");
	header('X-Content-Type-Options: nosniff');
	ini_set('session.cookie_httponly', 1 );

	if(!isset($_SERVER["HTTP_X_LOGIN_NAME"])){
		die("nope");
	}

	session_start();

	if(!isset($_SESSION['CSRF'])){
		$_SESSION['CSRF']= base64_encode( openssl_random_pseudo_bytes(32));
		$_SESSION['CSRF'] = str_replace("+", "p", $_SESSION['CSRF']);
		$_SESSION['CSRF'] = str_replace("/", "s", $_SESSION['CSRF']);
		$_SESSION['CSRF'] = str_replace("=", "e", $_SESSION['CSRF']);
	}

?>