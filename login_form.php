<?php
	echo <<< END
	<form method="post" action="$actionPage">
	<p>Username: <input type="text" name="name"></p>
	<p>Password: <input type="password" name="password"></p>
	<p><input type="submit" name="submit" value="Log In"></p>
	</form>	
END;
?>
