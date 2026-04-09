<?php
    if (isset($_POST['send'])){
        $errors = array();
        $success = "";

        $gridref= filter_var(trim($_POST['gridref']), FILTER_SANITIZE_STRING);
        if (empty($gridref)){
		    $errors['gridref'] = 'A grid reference is required:';
        }
        $name= filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
        if (empty($name)){
		    $errors['name'] = 'An name is required:';
        }

        if (!$errors){
            require_once ('../pdo_connect.php');
            $sql = "UPDATE Bricks SET Name = ? WHERE GridReference = ?";
            $stmt = $dbc->prepare($sql);
            $stmt->bindParam(1, $name);
            $stmt->bindParam(2, $gridref);
            $stmt->execute();
            $success = "Brick entry was updated successfully";
        }
    }
?>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>SB Vets MMS</title>
	<link rel="stylesheet" href="../styles/main.css">
</head>

<body>
	<div class="header">
		<h1><a href="../index.html">SB Vets Memorial Management System</a></h1>
	</div>
	
	<h2>Update Brick List</h2>
    <?php
        if ($errors['name']) echo "<p style='color: red;'>{$errors['name']}</p>"; 
        if ($errors['gridref']) echo "<p style='color: red;'>{$errors['gridref']}</p>"; 
        if (!empty($success)) echo "<p style='color: green;'>$success</p>";
	?>
	<div class="update">
        <form method="post" action="update_brick.php">
            <div class="card">
                <h3 class="updateHead">Update Name:</h3>
                
                <p><strong>Enter Grid Reference:</strong></p>
                <input name="gridref" class="input" placeholder="Search for Name">
                                
                <div class="divider"></div>
                
                <p><strong>Updated Name:</strong></p>
                <input name="name" class="input" placeholder="First Name:">
                
                <button class="update-btn" name="send" type="submit">SUBMIT</button>
            </div>
        </form>
	</div>
</body>
</html>
