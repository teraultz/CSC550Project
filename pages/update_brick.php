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

    if (isset($_POST['send2'])){
        $errors2 = array();
    	$success2 = "";

        $gridref= filter_var(trim($_POST['gridref']), FILTER_SANITIZE_STRING);
        if (empty($gridref)) {
			$errors2['gridref'] = "Grid reference is required.";
		}
        $name= filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
        if (empty($name)) {
			$errors2['name'] = "A name is required.";
		}

        if (!$errors2){
            require_once('../pdo_connect.php');
            $updateSql = "UPDATE Bricks SET Name = ? WHERE GridReference = ?";
			$updateStmt = $dbc->prepare($updateSql);
			$updateStmt->bindParam(1, $name);
            $updateStmt->bindParam(2, $gridref);
			$updateStmt->execute();

            $success2 = "Brick updated successfully.";
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
			<div class="divider"></div>
			<form method="post" id="deleteForm">
				<h3 class="deleteHead">Delete Location</h3>

				<p><strong>Brick to Delete:</strong></p>
				<input class="input" name = "gridReference" placeholder="Enter BrickID">

				<button class="update-btn" name="send">SUBMIT</button>
			</form>
		</div>

		<div class="card">
            <?php 
                if (!empty($errors2['gridref'])) echo "<p style='color: red;'>{$errors2['gridref']}</p>";
                if (!empty($errors2['name'])) echo "<p style='color: red;'>{$errors2['name']}</p>"; 
            ?>
            <form method="post">
                <h3 class="updateHead">Update Name:</h3>
                
                <p><strong>Brick Grid Reference:</strong></p>
                <input class="input" name="gridref" placeholder="Grid Reference">
                
                <div class="divider"></div>
                
                <p><strong>Updated Name:</strong></p>
                <input class="input" name="name" placeholder="Name">
                
                <button class="update-btn" name="send2">SUBMIT</button>
            </form>
		</div>
	</div>
</body>
</html>
