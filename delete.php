<?php
	$title = "Delete Animal";
	require('header.php');
?>
		<h1>Delete Animal</h1>
		
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
				
				$query = "DELETE FROM animal WHERE animalid = ?";
				
				$stmt = $db->prepare($query);
				$stmt->bind_param("i", $animalID);
				$stmt->execute();
				
				$affectedRows = $stmt->affected_rows;
				$stmt->close();
				$db->close();
				
				if ($affectedRows == 1) {
					echo "Successfully Deleted Animal<br>";
					echo "<a href=\"home.php\">Back to Animal List</a>";
					echo "<br><hr>";
					exit;		
				}
				else {
					echo "Failed to Delete Animal<br>";
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
				
				if ($row['desexed'] == "1") {
							$desexed = "Yes";
						} else {
							$desexed = "No";
						}

				
				echo <<<END
					Deleting Animal with ID: <strong>$animalID</strong><br><br>
					<form action="" method="POST">
						<table>
							<tr>
								<td>Name:</td>
								<td>$name</td>
							</tr>
							<tr>
								<td>Animal Type:</td>
								<td>$type</td>
							</tr>
							<tr>
								<td>Adoption Fee:</td>
								<td>$fee</td>
							</tr>
							<tr>
								<td>Sex:</td>
								<td>$sex</td>
							</tr>
							<tr>
								<td>Desexed:</td>
								<td>$desexed</td>
							</tr>
						</table>
						<br>
						<input type="hidden" name="animalid" value=$animalID>
						<input type="submit" name="submit" value="Delete">
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