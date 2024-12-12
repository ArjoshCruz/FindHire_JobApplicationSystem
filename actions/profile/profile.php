<?php 
    require_once '../../core/handleForms.php';
    require_once '../../core/models.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Hire</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../styles/main-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../styles/header/main-header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php $getUserById = getUserByID($pdo, $_GET['user_id']); ?>

    <?php if (isset($_SESSION['email_address'])) {?>
    <header class="admin-action">
        <div class="left-side">
            <a href="../../index.php" class="header-link">
                <img src="../../img/logo.png" alt="FindHire-Logo">
            </a>

            <?php $getUserByID = getUserByID($pdo, $_SESSION['user_id']);?>
            <p class="hello">Hello, <span><?php echo $getUserByID['first_name']?></span></p>
        </div>


        <ul>
            <li>
                <a href="../../index.php">Home</a>
            </li>
            <li>
                <a href="profile.php?user_id=<?php echo $_GET['user_id'];?>">Profile</a>
            </li>
            <?php if ($_SESSION['user_role'] === "Employer") { ?>
                <li>
                    <a href="../../employer-files/job-list.php">Job List</a>
                </li>
            <?php }?>
            <li>
                <a href="../../core/handleForms.php?logoutAUser=1">Logout</a>
            <?php } else { echo "<h1>No user Logged in</h1>";}?>
            </li>
        </ul>
    </header>

        <?php $getUserByID = getUserByID($pdo, $_GET["user_id"]);?>

    <!-- Profile -->
    <section class="profile-section" >
        <div class="container">
            <form class="form-container" action="../../core/handleForms.php?user_id=<?php echo $_GET['user_id']; ?>" method="POST">
                <p>
                    <label for="firstName">First Name:  </label> <br>
                    <input class="info-details"  type="text" name="firstName" value="<?php echo $getUserByID['first_name']?>" required>
                </p>

                <p>        
                    <label for="lastName">Last Name:  </label> <br>
                    <input class="info-details"  type="text" name="lastName" value="<?php echo $getUserByID['last_name']?>" required>
                </p>

                <p>
                    <label for="age">Age:  </label> <br>
                    <input class="info-details"  type="number" name="age" value="<?php echo $getUserByID['age']?>" required>
                </p>

                <p>
                    <label for="gender">Gender:  </label> <br>
                    <select class="info-details" name="gender">
                        <option value="Male" <?php echo ($getUserByID['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($getUserByID['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Prefer Not to Say" <?php echo ($getUserByID['gender'] == 'Prefer Not to Say') ? 'selected' : ''; ?>>Prefer Not to Say</option>
                    </select>
                </p>

                <p>
                    <label for="email">Email:  </label> <br>
                    <input class="info-details" type="email" name="email_address" value="<?php echo $getUserByID['email_address']?>" readonly>

                </p>

                <p>
                    <label for="contact_number">Contact Number:  </label> <br>
                    <input class="info-details" type="number" name="contact_number" value="<?php echo $getUserByID['contact_number']?>" required>
                </p>

                <p>
                    <label for="home_address">Home Address:  </label> <br>
                    <input class="info-details" type="text" name="home_address" value="<?php echo $getUserByID['home_address']?>" required>
                </p>

                <br>
                <p >
                    <input class="submit-btn" class="submit-form" type="submit" name="editUserBtn" value="Edit">
                </p>
            </form>
        </div>

    </section>
</body>
</html>