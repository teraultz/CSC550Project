<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>SB Vets MMS</title>
	<link rel="stylesheet" href="../styles/main.css">
</head>
<body>
<h1>You have been signed out</h1>
<a href="../index.html" class="btn">Return to Homepage</a>
</body>
</html>