<?php 
    require_once '../core/dbConfig.php';
    require_once '../core/models.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Hire</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/main-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../styles/header/auth-header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../styles/authentication/main-auth.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <header class="admin-action">
        <img src="../img/logo.png" alt="FindHire-Logo">

    </header>

    <section class="main">
        <div class="container">
            <img class="icon" src="../img/authentication/employee-icon.png" alt="Employee Icon">
            <h3 class="text">Are you an Employee <br> looking for work?</h3>
            <div class="btn-container">
                <a href="employee/login.php" class="sign-in">Sign In</a>
                <a href="employee/register.php" class="sign-up">Sign Up</a>
            </div>
        </div>

        <div class="container">
            <img class="icon" src="../img/authentication/hr-icon.png" alt="Employee Icon">
            <h3 class="text">Are you an Employer <br> looking to hire top talent?</h3>
            <div class="btn-container">
                <a href="hr/login.php" class="sign-in">Sign In</a>
                <a href="hr/register.php" class="sign-up">Sign Up</a>
            </div>
        </div>        
    </section>

</body>
</html>