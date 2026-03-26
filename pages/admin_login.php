<?php
    if (isset($_POST['send']) && $_POST['send']=="Login" ){
        $errors = array();

        $username= filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
	    if (empty($username)){
		    $errors['username'] = 'A username is required:';
        }
        $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);
	    if (empty($password)){
		    $errors['pw']= "A password is required";
        }
        while (!$errors){
            require_once ('../pdo_connect.php');
            $sql = "SELECT * FROM Accounts WHERE Username = :username";
            $stmt = $dbc->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $numRows = $stmt->rowCount();
            if ($numRows==0) 
                $errors['no_username'] = "That username wasn't found";
            else { // email found, validate password
                $result = $stmt->fetch(); //convert the result object pointer to an associative array 
                $pw_hash=$result['pw'];
                $role=$result['Role'];
                if (password_verify($password, $pw_hash )) { //passwords match
                    session_start();
                    if ($role == "Admin"){
                        header("Location: admin_home.php");	
                        exit;
                    }
                    if ($role == "Data Entry"){
                        header("Location: data_entry_home.php");	
                        exit;
                    }
                }
                else {
                    $errors['wrong_pw'] = "That isn't the correct password";
                }
            }
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
	
	<div class="login">
		<h2>Administrative Log In</h2>
		
		<form method="post" action="login.php">
            <?php 
                if ($errors) echo "<h2 class=\"warning\">Please fix the item(s) indicated.</h2>";
            	if ($errors['username']) echo "<h2 class=\"warning\">{$errors['username']}</h2>"; 
                if ($errors['pw']) echo "<h2 class=\"warning\">{$errors['pw']}</h2>";    
				if ($errors['wrong_pw']) echo "<h2 class=\"warning\">{$errors['wrong_pw']}</h2>"; 
            ?>
            <input name="username" id="username" type="text" class="account" placeholder="Username: ">
			<br>
			<input name="password" id="pw" type="password" class="account" placeholder="Password: ">
			<br>
			<input name="send" type="submit" value="Login">
		</form>
	
	</div>
	<script src="script.js"></script>
</body>
</html>