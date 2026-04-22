<?php
	session_start();
	if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
		die("Error: You need to login as an admin to access this page.");
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
		<h1>SB Vets Memorial Management System</h1>
		<h4><a href="session_logout.php">Logout</a></h4>
	</div>
	
	<div class="buttons">
		<h2>Admin Homepage</h2>
		<!-- Kenneth changed html to php for create login page-->
		<a href="create_login.php" class="btn">Create Login Credentials</a>
		
		<a href="add_brick.php" class="btn">Add Brick Entry</a>
		
		<a href="update_brick.php" class="btn">Update Brick List</a>
		
		<a href="search_list.php" class="btn">View/Search Brick List</a>
	</div>
</body>
</html>
