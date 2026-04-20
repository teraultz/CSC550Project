<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
	die("Error: You need to login as an admin to access this page.");
}

require_once '../pdo_connect.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);

    if (!empty($username)) {
        $stmt = $dbc->prepare("SELECT * FROM Accounts WHERE Username = :username");
		$stmt->execute(['username' => $username]);

        if ($stmt->rowCount() > 0) {
            $error = "Username Already Exists";
        }
		else {
    $password = trim($_POST["password"]);

    // Insert new account (default role = Data Entry)
    $insert = $dbc->prepare("INSERT INTO Accounts (Username, Password, Role) VALUES (:username, :password, 'Data Entry')");
    $insert->execute([
        'username' => $username,
        'password' => $password
    ]);

    $success = "Login credentials accepted. Data Entry account created.";
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

		<?php if (!empty($success)) : ?>
    <p style="color:green;"><?php echo $success; ?></p>
<?php endif; ?>
		
		<!-- Kenneth added method="POST" action="" to form, commented out the unneeded sections and turned this into a php doc -->
		<form method="POST" action="">
			<div class="row">
				<input type="text" name="username" class="account" placeholder="Username: ">
			</div>
			<br> <!--Added br to separate-->
			<div class="row">
				<input type="password" name="password" class="account" placeholder="Password: ">
			</div>
			
			<div class="row-check">
				<label class="checkbox">
					<input type="checkbox"> Admin
				</label>
				<label class="checkbox">
					<input type="checkbox"> Data Entry
				</label>

				<!--<form method="POST" action="">	can this be outside the div because it pushes the checkboxes?-->
			</div>		

			<!--<form method="POST" action="">		</label> -->
			
				
				
			<button type="submit" class="login-btn">Create</button>
		</form>

</body>
</html>
