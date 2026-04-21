<?php
    session_start();
    $allowed_roles = ["Admin", "Data Entry"];
    if (!isset($_SESSION["role"]) || !in_array($_SESSION["role"], $allowed_roles)) {
        die("Error: You need to login to access this page.");
    }


    $errors = array();
    $result = [];
    $result2 = null;
    $searchError = "";
    $searchSuccess = false;

    if (isset($_POST['send'])){
        require_once ('../pdo_connect.php');

        $gridref = trim($_POST['gridref']);
        $name = trim($_POST['name']);

        if (empty($name) && empty($gridref)){
            $errors['bothempty'] = 'You need to provide either a name or grid reference.';
        }

        if (!empty($name) && !empty($gridref)){
            $errors['bothfull'] = 'You need only provide the name or grid reference.';
        }

        if (empty($errors) && !empty($gridref)){
            $sql = "SELECT * FROM Bricks WHERE GridReference = ?";
            $stmt = $dbc->prepare($sql);
            $stmt->bindParam(1, $gridref);
            $stmt->execute();
            $result2 = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if (empty($errors) && !empty($name)){
            $namelike = "%$name%";
            $sql = "SELECT * FROM Bricks WHERE Name LIKE ?";
            $stmt = $dbc->prepare($sql);
            $stmt->bindParam(1, $namelike);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        if (empty($errors) && ( !empty($result2) || !empty($result) )) {
            $searchSuccess = true;
        }

        if (empty($errors) && empty($result2) && empty($result)) {
            $searchError = "Username Does Not Exist";
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
	
	<h2>View/Search Brick Location</h2>
    
	
	<form method="post" action="search_list.php"> 
		<div class="search">
			<div class="leftSearch">
				<div class="filter">
					<h3 class="searchHead">Search Location:</h3>
					
					<input name="gridref" class="input" placeholder="Grid Reference">
					
				</div>
			</div>
			
			<div class="rightSearch">
				<div class="filter">
					<h3 class="searchHead">Search by Name:</h3>
					
					<input name="name" class="input" placeholder="Name">
				</div>
			
				<div class="filter power_link">
					<h3 class="searchHead">Search with PowerBI:</h3>
					<a href="https://app.powerbi.com/view?r=eyJrIjoiNGQ4N2U5ODYtYmRlMi00ZjU2LTljYWUtOGIzYzRlMDVlMThjIiwidCI6IjIyMTM2NzgxLTk3NTMtNGM3NS1hZjI4LTY4YTA3ODg3MWViZiIsImMiOjF9" target="blank">
						<img src="../images/PowerBI_image.png" alt="Power BI Homepage" class="power-BI">
					</a>
				</div>
			</div>
		</div>
	
		<?php if (isset($_POST['send'])) : ?>
    <div class="filter foundBrick">
        <?php
            if (!empty($errors['bothempty'])) {
                echo "<p style='color: red;'>".$errors['bothempty']."</p>";
            } elseif (!empty($errors['bothfull'])) {
                echo "<p style='color: red;'>".$errors['bothfull']."</p>";
            } elseif (!empty($searchError)) {
                echo "<p style='color: red;'>".$searchError."</p>";
            } elseif ($searchSuccess) {
                echo "<p style='color: green;'>The brick you searched for is:</p>";

                if (!empty($result2)) {
                    echo "<p style='color: green;'>{$result2['Name']} {$result2['GridReference']} {$result2['Location']}</p>";
                } elseif (!empty($result)) {
                    foreach ($result as $row) {
                        echo "<p style='color: green;'>{$row['Name']} {$row['GridReference']} {$row['Location']}</p>";
                    }
                }
            }
        ?>
    </div>
<?php endif; ?>
		
		<button type="submit" name="send" class="search-btn">SEARCH</button>
		<button type="button" class="search-btn" onclick="window.location.href='search_list.php'">RESET</button>
	</form>
</body>
</html>
