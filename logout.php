<?php
	$title = "Logout";
	require('header.php');
?>
		<?php
			session_start();
			$validSession = require('check_session.php');
			
			if ($validSession) {
				$oldUser = $_SESSION['valid_user'];
				unset($_SESSION['valid_user']);
				session_destroy();		
			}
			
			if (!empty($oldUser)) {
				echo 'Logged Out<br>';

			}
			else {
				echo 'You were not logged in, and so have not been logged out.<br>';
			}
			include('footer_logged_out.php');
		?>
	</body>
</html>