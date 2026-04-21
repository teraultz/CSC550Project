<?php
    session_start();
    $allowed_roles = ["Admin", "Data Entry"];
    if (!isset($_SESSION["role"]) || !in_array($_SESSION["role"], $allowed_roles)) {
        die("Error: You need to login to access this page.");
    }

	if (isset($_POST['send'])){

    $errors = array();
    $success = "";

    $gridReference = trim($_POST['gridReference']);

    if (empty($gridReference)) {
        $errors['gridReference'] = "Brick reference is required.";
    }

    if (!$errors) {
        require_once('../pdo_connect.php');

        $checkSql = "SELECT Name FROM Bricks WHERE GridReference = ?";
        $checkStmt = $dbc->prepare($checkSql);
        $checkStmt->bindParam(1, $gridReference);
        $checkStmt->execute();

        $result = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if (!$result || empty($result['Name'])) {
            $errors['notclaimed'] = "Brick Name Not Claimed.";
        }
    }

    if (!$errors) {
        $deleteSql = "DELETE FROM Bricks WHERE GridReference = ?";
        $deleteStmt = $dbc->prepare($deleteSql);
        $deleteStmt->bindParam(1, $gridReference);
        $deleteStmt->execute();

        $success = "Entry Deleted.";
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

        if (!$errors2 && empty($errors2['notclaimed'])){
            require_once('../pdo_connect.php');
			// Check if brick exists AND has a name by KM
		$checkSql = "SELECT Name FROM Bricks WHERE GridReference = ?";
		$checkStmt = $dbc->prepare($checkSql);
		$checkStmt->bindParam(1, $gridref);
		$checkStmt->execute();

		$result = $checkStmt->fetch(PDO::FETCH_ASSOC);

		if (!$result || empty($result['Name'])) {
  		  $errors2['notclaimed'] = "Brick Name Not Claimed";
}


          $search = substr($name, 0, 3);

$updateSql = "UPDATE Bricks SET Name = ?, Search = ? WHERE GridReference = ?";
$updateStmt = $dbc->prepare($updateSql);
$updateStmt->bindParam(1, $name);
$updateStmt->bindParam(2, $search);
$updateStmt->bindParam(3, $gridref);
$updateStmt->execute();

$success2 = "Entry Updated";
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
    <?php
        if ($_SESSION["role"] === "Admin") {// If user is admin take them to admin home
            echo '<h1><a href="admin_home.php">SB Vets Memorial Management System</a></h1>';
        } else {
            // If user is data entry take them to data entry home
            echo '<h1><a href="data_entry_home.php">SB Vets Memorial Management System</a></h1>';
        }
        ?>
        <h4><a href="session_logout.php">Logout</a></h4>
	</div>
	
	<h2>Update Brick List</h2>
	
	<div class="update">
		
		<div class="card">
			
			<!--<div class="divider"></div>--> <!--Take divider off to take line off update page-->
			<?php //adding error and success KM
    if (!empty($errors['gridReference'])) echo "<p style='color: red;'>{$errors['gridReference']}</p>";
    if (!empty($errors['notclaimed'])) echo "<p style='color: red;'>{$errors['notclaimed']}</p>";
    if (!empty($success)) echo "<p style='color: green;'>{$success}</p>";
?>
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
				if (!empty($errors2['notclaimed'])) echo "<p style='color: red;'>{$errors2['notclaimed']}</p>";
				if (!empty($success2)) echo "<p style='color: green;'>{$success2}</p>";
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
