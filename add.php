<?php
	$title = "Add Animal";
	require('header.php');
?>
		<h1>Add Animal</h1>
		
		<?php
			require('check_access.php');
			require('db_connection.php');
			
			if (isset($_POST['submit'])) {
				$submit = $_POST['submit'];
				if ($submit == "Cancel") {
					$db->close();
					header('location: home.php');
					exit;
				}	
				
				
				if (!isset($_POST['name']) || empty($_POST['name'])) {
					echo "Error: Name not supplied.";
					$db->close();
					exit;
				}
			
				if (!isset($_POST['animal_type']) || empty($_POST['animal_type'])) {
					echo "Error: Animal Type not supplied.";
					$db->close();
					exit;
				}
				
				if (!isset($_POST['adoption_fee']) || empty($_POST['adoption_fee'])) {
					echo "Error: Adoption Fee not supplied.";
					$db->close();
					exit;
				}
				
				if (!isset($_POST['sex']) || empty($_POST['sex'])) {
					echo "Error: Sex not supplied.";
					$db->close();
					exit;
				}
				
				if (!isset($_POST['desexed'])) {
					echo "Error: Desexed not supplied.";
					$db->close();
					exit;
				}
				
				$name = $_POST['name'];
				$type = $_POST['animal_type'];
				$fee = $_POST['adoption_fee'];
				$sex = $_POST['sex'];
				$desexed = $_POST['desexed'];
				
				$query = "INSERT INTO animal (name, animal_type, adoption_fee, sex, desexed) VALUES (?, ?, ?, ?, ?)";
				
				$stmt = $db->prepare($query);
				$stmt->bind_param("ssisi", $name, $type, $fee, $sex, $desexed);
				$stmt->execute();
				
				$affectedRows = $stmt->affected_rows;
				$stmt->close();
				$db->close();
				
				if ($affectedRows == 1) {
					echo "Successfully Added Animal<br>";
					echo "<a href=\"home.php\">Back to Animal List</a>";
					echo "<br><hr>";
					exit;		
				}
				else {
					echo "Failed to Add Animal<br>";
					echo "<a href=\"home.php\">Back to Animal List</a>";
					echo "<br><hr>";
					exit;				
				}
			} 
			else {
				echo <<<END
					<form action="" method="POST">
						<table>
							<tr>
								<td>Animal Name:</td>
								<td><input type="text" name="name" value="" maxlength="100"></td>
							</tr>
							<tr>
								<td>Animal Type:</td>
								<td><select name="animal_type">
										<option value="Dog">Dog</option>
										<option value="Bird">Bird</option>
										<option value="Cat">Cat</option>
									</select></td>
							</tr>
							<tr>
								<td>Adoption Fee($):</td>
								<td><input type="number" name="adoption_fee" value=""></td>
							</tr>
							<tr>
								<td>Sex:</td>
								<td><select name="sex">
										<option value="Male">Male</option>
										<option value="Female">Female</option>
									</select></td>
							</tr>
							<tr>
								<td>Desexed?</td>
								<td><select name="desexed">
										<option value="1">Yes</option>
										<option value="0">No</option>
									</select></td>
							</tr>
						</table>
						<br>
						<input type="submit" name="submit" value="Add">
						<input type="submit" name="submit" value="Cancel">
					</form>
END;
			}
			
		?>
		
	<?php
		require('footer_logged_in.php')
	?>