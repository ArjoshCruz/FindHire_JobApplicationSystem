<?php 
    require_once '../core/handleForms.php';
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
            <li>
                <a href="core/handleForms.php?logoutAUser=1">Logout</a>
            <?php } else { echo "<h1>No user Logged in</h1>";}?>
            </li>
        </ul>
    </header>

    <!-- Create Job -->

    <div class="container">
        <h2 class="create-job">Apply Job</h2>
        <div class="job-container">
            <form 
                class="job-form" 
                action="../core/handleForms.php?post_id=<?php echo $_GET['post_id']; ?>" 
                method="POST" 
                enctype="multipart/form-data">
                <p>
                    <label class="job-label" for="cover_letter">Cover Letter:</label> <br>
                    <textarea class="job-details" name="cover_letter" rows="5" cols="50" required></textarea>
                </p>
                <p>
                    <label class="job-label" for="resume_file">Resume:</label> <br>
                    <input class="attachment" type="file" name="resume_file" accept=".pdf" required>

                </p>
                <p>
                    <input class="submit-btn job-btn" class="submit-form" type="submit" name="insertJobApplication" value="Apply">
                </p>
            </form>
        </div>
    </div>

</body>
</html>