<?php
require_once '../pdo_connect.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);

    if (!empty($username)) {
        $stmt = $dbc->prepare("SELECT * FROM Accounts WHERE Username = :username");
		$stmt->execute(['username' => $username]);

        if ($stmt->rowCount() > 0) {
            $error = "Username Already Exists";
        }
    }
}
?>
<!DOCTYPE html>
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
	
	<div class="create">
		<h2>Create Login Credentials</h2>
		<?php if (!empty($error)) : ?>
    	<p style="color:red;"><?php echo $error; ?></p>
		<?php endif; ?>
		
		<!-- Kenneth added method="POST" action="" to form, commented out the unneeded sections and turned this into a php doc -->
		<form method="POST" action="">
			<div class="row">
				<input type="text" name="first_name" class="account" placeholder="First Name: ">
				<input type="text" name="username" class="account" placeholder="Username: ">
			</div>
			
			<div class="row">
				<input type="text" name="last_name" class="account" placeholder="Last Name: ">
				<input type="password" name="password" class="account" placeholder="Password: ">
			</div>
			
			<div class="row-check">
				<label class="checkbox">
					<input type="checkbox"> Admin
				</label>

			<!--	<label>
					<input type="text" class="input" placeholder="UNCW ID: " />
				</label>
			-->
		<form method="POST" action="">	
			</div>
				
			<div class="row check">
				<label class="checkbox">
					<input type="checkbox"> Data Entry
				</label>
			
			<!--	<label>
				  <input type="text" class="input" placeholder="Employee ID: ">
		-->
		<form method="POST" action="">		</label>
			</div>	
				
				
			<button type="submit" class="login-btn">Create</button>
		</form>

</body>
</html>
