<?php
    if (isset($_POST['send'])){

    
        $errors = array();
        $success = "";

        $column= filter_var(trim($_POST['column']), FILTER_SANITIZE_STRING);
        if (empty($column)){
		    $errors['column'] = 'A column is required:';
        }
        $row= filter_var(trim($_POST['row']), FILTER_SANITIZE_STRING);
        if (empty($row)){
		    $errors['row'] = 'An row is required:';
        }
        $location= filter_var(trim($_POST['location']), FILTER_SANITIZE_STRING);
        if (empty($location)){
		    $errors['location'] = 'An location is required:';
        }
        $name= filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
        if (empty($name)){
		    $errors['name'] = 'An name is required:';
        }
        $search = substr($name, 0, 3);
        $gridReference = $column . $row;

        //Adding error checking of duplicate bricks KM

        if (!$errors) {
    require_once ('../pdo_connect.php');

    $checkSql = "SELECT 1 FROM Bricks WHERE ColumnVal = ? AND RowVal = ?";
    $checkStmt = $dbc->prepare($checkSql);
    $checkStmt->bindParam(1, $column);
    $checkStmt->bindParam(2, $row);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        $errors['duplicate'] = 'That brick column and row are already taken.';
    }
}

        if (!$errors){
            require_once ('../pdo_connect.php');
            $sql = "INSERT INTO Bricks (Search, Name, ColumnVal, RowVal, GridReference, Location) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $dbc->prepare($sql);
            $stmt->bindParam(1, $search);
            $stmt->bindParam(2, $name);
            $stmt->bindParam(3, $column);
            $stmt->bindParam(4, $row);
            $stmt->bindParam(5, $gridReference);
            $stmt->bindParam(6, $location);
            $stmt->execute();
            $success = "Brick entry was added successfully";
        }
    }
?>

<head>
	<meta charset="utf-8">
	<title>SB Vets MMS</title>
	<link rel="stylesheet" href="../styles/main.css">
</head>

<body>
	<div class="header">
		<h1><a href="../index.html">SB Vets Memorial Management System</a></h1>
	</div>
	
	<h2>Add Brick Entry</h2>
	
	<div class="add">
        <form method="post" action="add_brick.php">
            <?php //Doing error and success handling
    if ($errors['column']) echo "<p style='color: red;'>{$errors['column']}</p>"; 
    if ($errors['row']) echo "<p style='color: red;'>{$errors['row']}</p>"; 
    if ($errors['location']) echo "<p style='color: red;'>{$errors['location']}</p>";
    if ($errors['name']) echo "<p style='color: red;'>{$errors['name']}</p>"; 
    if (!empty($errors['duplicate'])) echo "<p style='color: red;'>{$errors['duplicate']}</p>";

    if (!empty($success)) {
        echo "<p style='color: green;'>$success</p>";
    } 
?>
            <div class="addBrick">
                <h3 class="updateHead">Brick Location:</h3>
                
                <input name="column" class="input" placeholder="Column">
                
                <input name="row" class="input" placeholder="Row">
                
                <input name="location" class="input" placeholder="Location">
                
            </div>
            
            <div class="addBrick">
                <h3 class="updateHead">Name for Brick:</h3>
                
                <input name="name" class="input" placeholder="Name:">
                
                <br><br>
                            
                <button name="send" type="submit" class="update-btn">Add Entry</button>
            </div>
        </form>
	</div>
</body>
</html>