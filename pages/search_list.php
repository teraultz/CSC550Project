<?php
    if (isset($_POST['send'])){
        $errors = array();
		require_once ('../pdo_connect.php');

        $gridref= filter_var(trim($_POST['gridref']), FILTER_SANITIZE_STRING);
        $name= filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
	    if (empty($name) and (empty($gridref))){
            $errors['bothempty'] = 'You need to provide either a name or grid reference.';
        }
        if (!empty($name) and !empty($gridref)){
            $errors['bothfull'] = 'You need only provide the name or grid reference.';
        }
        if (empty($errors) && !empty($gridref)){
            #require_once ('../pdo_connect.php');
            $sql = "SELECT * FROM Bricks WHERE GridReference = ?";
            $stmt = $dbc->prepare($sql);
            $stmt->bindParam(1, $gridref);
            $stmt->execute();
            $result = $stmt->fetch();
        }
        if (empty($errors) && !empty($name)){
			$namelike = "%$name%";
            #require_once ('../pdo_connect.php');
            $sql = "SELECT * FROM Bricks WHERE Name LIKE ?";
            $stmt = $dbc->prepare($sql);
            $stmt->bindParam(1, $namelike);
            $stmt->execute();
            $result = $stmt->fetchAll();
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
	
	<h2>View/Search Brick Location</h2>
    <?php
        if (!empty($errors['bothempty'])) echo "<p style='color: red;'>{$errors['bothempty']}</p>";
        if (!empty($errors['bothfull'])) echo "<p style='color: red;'>{$errors['bothfull']}</p>";
    ?>
	
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
	
		<div class="filter foundBrick">
			<p>
                The brick you searched for is: 
                <?php 
					if (!empty($result)) {
						foreach ($result as $row) {
							echo "<p>{$row['Name']} {$row['GridReference']} {$row['Location']}</p>";
						}
						#echo "<p>{$result['Name']} {$result['GridReference']} {$result['Location']}</p>";
					} else {
						echo "<p>No results found.</p>";
					}
				?>
            </p>
		</div>
		
		<button type="submit" name="send" class="search-btn">SEARCH</button>
		<button type="reset" class="search-btn">RESET</button>
	</form>
</body>
</html>
