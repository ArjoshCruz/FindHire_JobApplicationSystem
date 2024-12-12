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
    
    <link rel="stylesheet" href="../styles/header/main-header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../styles/main-style.css?v=<?php echo time(); ?>">
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
                <a href="../core/handleForms.php?logoutAUser=1">Logout</a>
            <?php } else { echo "<h1>No user Logged in</h1>";}?>
            </li>
        </ul>
    </header>

    <!-- Job List -->
     <section class="table-div">        
        <div class="job-title view-job-title job-list-title">
            <h3>Job List</h3>
        </div>

            <table>
            <tr>
                <th>Post ID</th>
                <th>Job Title</th>
                <th>Job Description</th>
                <th>Date Posted</th>
                <th>Action</th>
            </tr>

                <?php 
                $getJobPostByUserID = getJobPostByUserID($pdo, $_SESSION['user_id']);

                foreach ($getJobPostByUserID as $row) {
                ?>
            <tr>
                <td><?php echo $row['post_id']?></td>
                <td><?php echo $row['job_title']?></td>
                <td><?php echo $row['job_description']?></td>
                <td><?php echo $row['date_posted']?></td>
                <td class="action-td">
                    <a class="action-button edit" href="edit-job.php?post_id=<?php echo $row['post_id']; ?>">Edit</a>
                    <form action="../core/handleForms.php?post_id=<?php echo $row['post_id']; ?>" method="POST" onsubmit="confirmDelete(event)">
                        <p>
                            <input class="action-button delete" class="submit-btn job-btn" class="submit-form" type="submit" name="deleteJobBtn" value="Delete" >
                        </p>
                    </form>
                </td>
                <?php } ?>
            </tr>
        </table>
     </section>

     <script>
        function confirmDelete(event) {
            // Display confirmation dialog
            var confirmed = confirm("Are you sure you want to delete this job post?");
            if (!confirmed) {
                // Prevent form submission if the user clicks 'Cancel'
                event.preventDefault();
            }
        }
    </script>

</body>
</html>