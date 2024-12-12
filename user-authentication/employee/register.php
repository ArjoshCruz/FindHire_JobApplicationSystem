<?php 
    require_once '../../core/models.php';
    require_once '../../core/dbConfig.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Hire</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../styles/main-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../styles/header/auth-header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../styles/authentication/auth.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>
<body>
    <header class="admin-action">
        <img src="../../img/logo.png" alt="FindHire-Logo">
    </header>

    <h2 class="text-portal">Employee Portal - <span>Sign Up</span></h2>
    
    <div class="form-container">
        <form action="../../core/handleForms.php" method="POST">
            <p>
                <label class="bold" for="email_address">Email Address: </label> <br>
                <input type="email" name="email_address" required>
            </p>
            <p>
                <label class="bold" for="password">Password: </label> <br>
                <input type="password" name="password" required>
            </p>
            <p>
                <label class="bold" for="confirm_password">Confirm Password: </label> <br>
                <input type="password" name="confirm_password" required>
            </p>
            <p>
                <label class="terms">
                    <input type="checkbox" name="agree" required>
                    I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>*
                </label>
            </p>

            <p>
                <input class="submit-btn" type="submit" name="registerEmployeeBtn" value="Register">
            </p>
        </form>
    </div>

    <h5><a href="../authentication.php" class="back-btn">Back</a></h5>

    <?php if (isset($_SESSION['message'])) { ?>
    <h1 style="color: red;"><?php echo $_SESSION['message']?></h1>
    <?php }?>
    <?php unset($_SESSION['message']);?>
</body>
</html>