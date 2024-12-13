<?php 
    require_once '../core/handleForms.php';
    require_once '../core/models.php';

    if($_SESSION['user_role'] == "Employee") {
        header("Location: ../index.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Hire</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/main-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../styles/header/main-header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php if (isset($_SESSION['email_address'])) {?>
    <header class="admin-action">
            <div class="left-side">
                <a href="../index.php" class="header-link">
                    <img src="../img/logo.png" alt="FindHire-Logo">
                </a>

                <?php $getUserByID = getUserByID($pdo, $_SESSION['user_id']);?>
                <p class="hello">Hello, <span><?php echo $getUserByID['first_name']?></span></p>
            </div>


        <ul>
            <li>
                <a href="../index.php">Home</a>
            </li>
            <li>
                <?php if (isset($_SESSION['user_id'])) { ?>
                    <a href="../actions/profile/profile.php?user_id=<?php echo $_SESSION['user_id']; ?>">Profile</a>
                <?php } ?>
            </li>
            <?php if ($_SESSION['user_role'] === "Employer") { ?>
                <li>
                    <a href="job-list.php">Job List</a>
                </li>
            <?php }?>
            <li>
                <a href="core/handleForms.php?logoutAUser=1">Logout</a>
            <?php } else { echo "<h1>No user Logged in</h1>";}?>
            </li>
        </ul>
    </header>

    <!-- Create Job -->
    <div class="container">
        <h2 class="create-job">Create A Job</h2>
        <div class="job-container">
           <form class="job-form" action="../core/handleForms.php?user_id=<?php echo $_GET['user_id']; ?>" method="POST">
               <p>
                   <label class="job-label" for="job-title">Job Title:</label> <br>
                   <input class="job-details" type="text" name="job-title" required>
               </p>
                <p>
                   <label class="job-label" for="job-description">Job Description:</label> <br>
                   <textarea class="job-details" name="job-description" rows="5" cols="50" required></textarea>
               </p>
               <p>
                   <input class="submit-btn job-btn" class="submit-form" type="submit" name="insertJobBtn" value="Create">
               </p>
           </form>
        </div>
     </div>
</body>
</html>