<?php
    // Include db conection file
    include("dbconection.php");

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";     
    } else{
        $username = trim($_POST["username"]);
    }

    // Validate phone
    if(empty(trim($_POST["phone"]))){
        $phone_err = "Please enter a phone.";     
    } else{
        $phone = trim($_POST["phone"]);
    }

    $checkMail = trim($_POST["email"]);
    $statement = $db->query("SELECT * FROM users_table WHERE email = '$checkMail'");
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    

    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an email.";     
    } elseif (!filter_var($checkMail, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format"; 
    } elseif($results) {
        $email_err = "This email already exists.";
    } else{
        $email = trim($_POST["email"]);
    }


    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) <= 7){
        $password_err = "Password must have atleast 7 characters.";
    } else{
        $password = trim($_POST["password"]);
        $param_password = password_hash($password, PASSWORD_DEFAULT);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
    }   
    if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
    }
?>
 
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
   
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="signup.css">
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username <span class="text-danger">*</span></label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>  

            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Phone <span class="text-danger">*</span></label>
                <input type="text" name="phone" class="form-control" value="<?php echo $phone; ?>">
                <span class="help-block"><?php echo $phone_err; ?></span>
            </div>  

            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email <span class="text-danger">*</span></label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>  


            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password <span class="text-danger">*</span></label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group text-center">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p><a href="login.php">Login here</a>.</p>
        </form>
    </div>    
    </body>
</html>

<?php    
     try {
        $query = 'INSERT INTO users_table (username, phone, email, hashpassword) VALUES (:username, :phone, :email, :hashpassword)'; 
        $stmt = $db->prepare($query);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':phone', $phone, PDO::PARAM_INT);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':hashpassword', $param_password, PDO::PARAM_STR);
        $stmt->execute();   
        header("location: login.php");
        die();
    
    }
    catch (Exception $ex) {
        echo "I am getting the following error:  $ex";
        die();
    }


?>