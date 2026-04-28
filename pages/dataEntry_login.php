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
                $pw=$result['Password'];
                $role=$result['Role'];
                if ($password == $pw) { //passwords match
                    session_start();
                    $_SESSION["role"] = $role;
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
		<h2>Data Entry Log In</h2>
		
		<form method="post" action="dataEntry_login.php">
           <?php
if ($errors['no_username'] || $errors['wrong_pw']) {
    echo "<h2 style='color:red;'>Invalid username or password.</h2>";
}
?>
            <input name="username" id="username" type="text" class="account" placeholder="Username: ">
			<br>
			<input name="password" id="pw" type="password" class="account" placeholder="Password: ">
			<br>
			<input name="send" type="submit" value="Login" class="login-btn"> 							<!--Fixed submit class to be same style-->
		</form>
	
	</div>
	<script src="script.js"></script>
</body>
</html>
