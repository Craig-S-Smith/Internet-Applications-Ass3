<?php
	$title = "Home";
	require('header.php');
?>
		<h1>Adoption Management Dashboard</h1>
		
		<?php
			session_start();
			$validLogin = require('check_login.php');
			$validSession = require('check_session.php');
			
			if ($validSession) {
				$userName = $_SESSION['valid_user'];
				echo "Welcome, $userName";
			}
		?>
		
		<form action="" method="POST">
			Filter by Animal Type: 
			<select name="filter-type">
				<option value="filter-all">All</option>
				<option value="filter-bird">Bird</option>
				<option value="filter-cat">Cat</option>
				<option value="filter-dog">Dog</option>
			</select>
			<input type="submit" name="submit" value="Search">
		</form>
		
		<?php
			require('db_connection.php');
			
			$allQuery = "SELECT * FROM animal ORDER BY animal_type, name";
			$birdQuery = "SELECT * FROM animal WHERE animal_type = 'Bird' ORDER BY name";
			$catQuery = "SELECT * FROM animal WHERE animal_type = 'Cat' ORDER BY name";
			$dogQuery = "SELECT * FROM animal WHERE animal_type = 'Dog' ORDER BY name";
			
			if (isset ($_POST['filter-type'])) {
				$filterType = $_POST['filter-type'];
				
				switch ($filterType) {
					case "filter-bird":
						echo "Sorted by: <strong>Bird</strong><br>";
						$result = $db->query($birdQuery);
						break;
					case "filter-cat":
						echo "Sorted by: <strong>Cat</strong><br>";
						$result = $db->query($catQuery);
						break;
					case "filter-dog":
						echo "Sorted by: <strong>Dog</strong><br>";
						$result = $db->query($dogQuery);
						break;
					default:
						echo "Sorted by: <strong>All</strong><br>";
						$result = $db->query($allQuery);
						break;
				}
				
			} else {
				$result = $db->query($allQuery);
			}
			$numResults = $result->num_rows;
		?>
		
		<table>
			<thead>
				<tr>
					<th>Name</th>
					<th>Animal Type</th>
					<th>Adoption Fee</th>
					<th>Sex</th>
					<th>Desexed?</th>
					<?php
						if ($validLogin || $validSession)
							echo "<th></th><th></th>";
					?>
				</tr>
			</thead>
			<tbody>
				<?php
					for ($i = 0; $i < $numResults; $i++) {
						$row = $result->fetch_assoc();
						$animalID = $row['animalid'];
						$name = $row['name'];
						$type = $row['animal_type'];
						$fee = $row['adoption_fee'];
						$sex = $row['sex'];
						$desexed = $row['desexed'];
						
						echo "<tr>";
						echo "<td valign=\"top\">$name</td>";
						echo "<td valign=\"top\">$type</td>";
						echo "<td valign=\"top\">$fee</td>";
						echo "<td valign=\"top\">$sex</td>";
						if ($desexed == "1") {
							echo "<td valign=\"top\">Yes</td>";
						} else {
							echo "<td valign=\"top\">No</td>";
						}
						if ($validLogin || $validSession) {
							createButtonColumn("animalid", $animalID, "Edit", "edit.php");
							createButtonColumn("animalid", $animalID, "Delete", "delete.php");
						}
						echo "</tr>";
					}
					
					$result->free();
					$db->close();

					
					function createButtonColumn($hiddenName, $hiddenValue, $buttonText, $actionPage) {
						echo "<td>";
						echo "<form action=$actionPage method=\"GET\">";
						echo "<input type=\"hidden\" name=$hiddenName value=$hiddenValue>";
						echo "<button type=\"submit\">$buttonText</button>";
						echo "</form>";
						echo "</td>";

					}
				?>
				</tbody>
		</table>
				
		<?php
			if ($validLogin || $validSession) {
				require("footer_logged_in.php");
			}
			else {
				require("footer_logged_out.php");
			}
		?>
