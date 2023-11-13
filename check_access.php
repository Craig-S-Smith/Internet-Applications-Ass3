<?php
	session_start();
	
	$validSession = require('check_session.php');
			
	if ($validSession) {
				
	}
	else {
		header("location: login.php");
		exit;
	}
?>