<?php
	$title = "Edit Animal";
	require('header.php');
?>
		<h1>Edit Animal</h1>
		
		<?php
			require('check_access.php');
			require('db_connection.php');
		
			if (!isset($_GET['animalid']) || empty($_GET['animalid'])) {
				echo "Error: Animal ID not supplied.";
				$db->close();
				require('footer_logged_in.php');
				exit;
			}
			$animalID = $_GET['animalid'];
			
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
				
				$query = "UPDATE animal SET name=?, animal_type=?, adoption_fee=?, sex=?, desexed=? WHERE animalid = ?";
				
				$stmt = $db->prepare($query);
				$stmt->bind_param("ssisii", $name, $type, $fee, $sex, $desexed, $animalID);
				$stmt->execute();
				
				$affectedRows = $stmt->affected_rows;
				$stmt->close();
				$db->close();
				
				if ($affectedRows == 1) {
					echo "Successfully Edited Animal<br>";
					echo "<a href=\"home.php\">Back to Animal List</a>";
					echo "<br><hr>";
					exit;		
				}
				else {
					echo "Failed to Edit Animal<br>";
					echo "<a href=\"home.php\">Back to Animal List</a>";
					echo "<br><hr>";
					exit;				
				}
			} 
			else {
			
				$queryAnimalDetails = "SELECT * FROM animal WHERE animalid = ?";
				$stmtAnimalDetails = $db->prepare($queryAnimalDetails);
				$stmtAnimalDetails->bind_param("i", $animalID);
				
				$stmtAnimalDetails->execute();
				$result = $stmtAnimalDetails->get_result();
				$stmtAnimalDetails->close();
				
				$row = $result->fetch_assoc();
				
				$name = $row['name'];
				$type = $row['animal_type'];
				$fee = $row['adoption_fee'];
				$sex = $row['sex'];
				$desexed = $row['desexed'];

				
				echo <<<END
					Editing Animal with ID: <strong>$animalID</strong><br><br>
					<form action="" method="POST">
						<table>
							<tr>
								<td>Animal Name:</td>
								<td><input type="text" name="name" value="$name" maxlength="100"></td>
							</tr>
							<tr>
								<td>Animal Type:</td>
								<td><select name="animal_type">
										<option value="Dog">Dog</option>
										<option value="Bird" 
END;
										if ($type == "Bird") {
											echo " selected";
										}
										echo <<<END
										>Bird</option>
										<option value="Cat" 
END;
										if ($type == "Cat") {
											echo " selected";
										}
										echo <<<END
										>Cat</option>
									</select></td>
							</tr>
							<tr>
								<td>Adoption Fee($):</td>
								<td><input type="number" name="adoption_fee" value="$fee"></td>
							</tr>
							<tr>
								<td>Sex:</td>
								<td><select name="sex">
										<option value="Male">Male</option>
										<option value="Female" 
END;
										if ($sex == "Female") {
											echo " selected";
										}
										echo <<<END
										>Female</option>
									</select></td>
							</tr>
							<tr>
								<td>Desexed?</td>
								<td><select name="desexed">
										<option value="1">Yes</option>
										<option value="0" 
END;
										if ($desexed == "0") {
											echo " selected";
										}
										echo <<<END
										>No</option>
									</select></td>
							</tr>
						</table>
						<br>
						<input type="hidden" name="animalid" value=$animalID>
						<input type="submit" name="submit" value="Submit Changes">
						<input type="submit" name="submit" value="Cancel">
					</form>
END;
				$result->free();
			}
			$db->close();
		?>
		
	<?php
		require('footer_logged_in.php')
	?>