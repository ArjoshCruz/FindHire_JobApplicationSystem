<?php 
    require_once '../../../core/handleForms.php';
    require_once '../../../core/models.php';

    if($_SESSION['user_role'] == "Employer") {
        header("Location: ../../../index.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Hire</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../styles/main-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../../styles/header/main-header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php 
    // Ensure post_id is set and valid
    if (isset($_GET['post_id']) && !empty($_GET['post_id'])) {
        $getJobPostbyID = getJobPostByID($pdo, $_GET['post_id']);

        if ($getJobPostbyID) {
    ?>
    <header class="admin-action">
        <div class="left-side">
            <a href="../../../index.php" class="header-link">
                <img src="../../../img/logo.png" alt="FindHire-Logo">
                </a>

            <?php $getUserByID = getUserByID($pdo, $_SESSION['user_id']);?>
            <p class="hello">Hello, <span><?php echo $getUserByID['first_name']?></span></p>
        </div>


        <ul>
            <li>
                <a href="../../../index.php">Home</a>
            </li>
            <li>
            <?php if (isset($_SESSION['user_id'])) { ?>
                <a href="../../../actions/profile/profile.php?user_id=<?php echo $_SESSION['user_id']; ?>">Profile</a>
            <?php } ?>
            </li>
            <li>
                <a href="../../../core/handleForms.php?logoutAUser=1">Logout</a>
            </li>
        </ul>
    </header>

    <!-- view Job -->
    <?php
        $getJobPostByID = getJobPostByID($pdo, $_GET['post_id']);

        $getApplicationByJobID = getApplicationByJobID($pdo, $_GET['post_id']);
     ?>
    <section class="job-section">
        <div class="job-title view-job-title">
            <h3>
                <?php echo htmlspecialchars($getJobPostbyID['job_title']); ?>
            </h3>
        </div>
        <div class="job-info view-job-info">
            <p>
                <span>
                    <?php echo htmlspecialchars($getJobPostbyID['first_name']) . ' ' . htmlspecialchars($getJobPostbyID['last_name']); ?>
                </span>
                · <?php echo htmlspecialchars($getJobPostbyID['date_posted']); ?>
            </p>
        </div>
        <div class="job-description view-job-description">
            <p><?php echo htmlspecialchars($getJobPostbyID['job_description']); ?></p>
        </div>
        <div class="apply-div">
            <a class="apply-button" href="../../../employee-files/apply-job.php?post_id=<?php echo $getJobPostByID['post_id'];?>">Apply for this Job</a>
        </div>

    </section>
    <?php 
        } else {
            echo "<h1>Job post not found.</h1>"; 
        }
    } else {
        echo "<h1>No valid post ID provided.</h1>";
    }
    ?>

    <hr>
    <!-- ----------------------------------------------------------------------------------------- -->
    <?php  $getApplicationByID = getApplicationByID($pdo, $_GET['post_id']);?>

    <section class="table-div">
        <div class="job-title view-job-title applicants-text">
            <h3>
                Applicants
            </h3>
        </div>
        <table>
            <tr>
                <th>Applicant Name</th>
                <th>Date Applied</th>
                <th>Status</th>
                <?php if (isset($_SESSION['user_id'])) { ?>
                    <th>Message</th>
                <?php } ?>
            </tr>
            <?php 
            foreach ($getApplicationByJobID as $row) {
                // Dynamically check if the logged-in user is the applicant
                $isUserApplicant = ($_SESSION['user_id'] == $row['employee_id']);
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                <td><?php echo htmlspecialchars($row['date_sent']); ?></td>
                <td><?php echo htmlspecialchars($row['application_status']); ?></td>
                <?php if ($isUserApplicant) { ?>
                    <td>
                        &#8594;
                        <a href='../../messages.php?post_id=<?php echo $_GET['post_id']; ?>&application_id=<?php echo $row['application_id']?>'>
                            Message <?php echo htmlspecialchars($getJobPostbyID['first_name']); ?>
                        </a>
                        &#8592;
                    </td>
                <?php } ?>
            </tr>
            <?php } ?>
        </table>
    </section>




</body>
</html>
