<?php
	$title = "Login";
	require('header.php');
?>
		<h1>Adoption Management Dashboard</h1>
		<?php
			
			session_start();
			$validLogin = require('check_login.php');
			$validSession = require('check_session.php');
			
			if ($validLogin || $validSession) {
				header("location: home.php");
			}
			else {
				$actionPage = "login.php";
				require('login_form.php');
			}
			
			require('footer_logged_out.php');
		?>