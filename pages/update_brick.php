<?php
	if (isset($_POST['send'])){


		$errors = array();
    	$success = "";

		$gridReference = trim($_POST['gridReference']);

		if (empty($gridReference)) {
			$errors['gridReference'] = "Brick reference is required.";
		}
	
		if (!$errors) {
			require_once('../pdo_connect.php');
			$checkSql = "SELECT 1 FROM Bricks WHERE GridReference = ?";
			$checkStmt = $dbc->prepare($checkSql);
			$checkStmt->bindParam(1, $gridReference);
			$checkStmt->execute();
	
			if ($checkStmt->rowCount() == 0) {
				$errors['notfound'] = "Brick not found.";
			}
		}

		if (!$errors) {
			$deleteSql = "DELETE FROM Bricks WHERE GridReference = ?";
			$deleteStmt = $dbc->prepare($deleteSql);
			$deleteStmt->bindParam(1, $gridReference);
			$deleteStmt->execute();
	
			$success = "Brick deleted successfully.";
		}
		if (!empty($errors)) {
			foreach ($errors as $error) {
				echo "<p style='color:red;'>$error</p>";
			}
		}
		
		if (!empty($success)) {
			echo "<p style='color:green;'>$success</p>";
		}
	}
?>
<head>
	<meta charset="utf-8">
	<title>SB Vets MMS</title>
	<link rel="stylesheet" href="../styles/main.css">
	<script>
	document.getElementById('deleteForm').addEventListener('submit', function(event) {
    const confirmed = confirm("Are you sure you want to delete this brick? This cannot be undone.");
    if (!confirmed) {
        event.preventDefault();
    }
});
</script>
</head>

<body>
	<div class="header">
		<h1><a href="../index.html">SB Vets Memorial Management System</a></h1>
	</div>
	
	<h2>Update Brick List</h2>
	
	<div class="update">
		
		<div class="card">
			<h3 class="updateHead">Update Location:</h3>
			
			<p><strong>Brick to Change:</strong></p>
			<input class="input" placeholder="Search for Brick">
			
			<p><strong>New Location:</strong></p>
			<input class="input" placeholder="Search for Location">
			
			<button class="update-btn">SUBMIT</button>

			<div class="divider"></div>
			<form method="post" id="deleteForm">
				<h3 class="deleteHead">Delete Location</h3>

				<p><strong>Brick to Delete:</strong></p>
				<input class="input" name = "gridReference" placeholder="Enter BrickID">

				<button class="update-btn" name="send">SUBMIT</button>
			</form>
		</div>
		
		<div class="card">
			<h3 class="updateHead">Update Name:</h3>
			
			<p><strong>Search Name:</strong></p>
			<input class="input" placeholder="Search for Name">
			
			<p style="margin: 10px 0;">OR</p>
			
			<p><strong>Search Brick:</strong></p>
			<input class="input" placeholder="Search for Brick">
			
			<div class="divider"></div>
			
			<p><strong>Updated Name:</strong></p>
			<input class="input" placeholder="First Name:">
			
			<br><br>
			
			<input class="input" placeholder="Last Name:">
			
			<button class="update-btn">SUBMIT</button>
		</div>
	</div>
</body>
</html>
