<?php 
    require_once '../../../core/handleForms.php';
    require_once '../../../core/models.php';
    
    if($_SESSION['user_role'] == "Employee") {
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
            <?php if ($_SESSION['user_role'] === "Employer") { ?>
                <li>
                    <a href="../../../employer-files/job-list.php">Job List</a>
                </li>
            <?php }?>
            <li>
                <a href="../../../core/handleForms.php?logoutAUser=1">Logout</a>
            </li>
        </ul>
    </header>

    <!-- view Job -->
    <?php
        $getJobPostByID = getJobPostByID($pdo, $_GET['post_id']);
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
                <?php if($_SESSION['user_id'] == $getJobPostByID['employer_id']) {?>
                    <th>Download File</th>
                <?php }?>
                <th>Status</th>
                <th>Message</th>
                
                <?php if($_SESSION['user_id'] == $getJobPostByID['employer_id']) {?>            
                    <th>Action</th>
                <?php }?>
            </tr>
            <?php 
            // $getAllApplications = getAllApplications($pdo);
            $getApplicationByJobID = getApplicationByJobID($pdo, $_GET['post_id']);

            foreach ($getApplicationByJobID as $row) {
                $isUserApplicant = ($_SESSION['user_id'] == $row['employee_id']);
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                <td><?php echo htmlspecialchars($row['date_sent']); ?></td>
                <?php if ($_SESSION['user_id'] == $getJobPostByID['employer_id']) { ?>
                    <td>
                        <a href="path/to/resumes/<?php echo htmlspecialchars($row['resume_file']); ?>" download>
                            <?php echo htmlspecialchars($row['resume_file']); ?>
                        </a>
                    </td>
                <?php } ?>

                
                <td><?php echo htmlspecialchars($row['application_status']); ?></td>
                
                    <?php if ($_SESSION['user_id'] == $getJobPostByID['employer_id']) { ?>
                        <td>
                            &#8594;
                            <a href='../../messages.php?post_id=<?php echo $_GET['post_id']; ?>&application_id=<?php echo $row['application_id']?>'>
                                Message <?php echo htmlspecialchars($row['first_name']); ?>
                            </a>
                            &#8592;
                        </td>
                    <?php } ?>
                
                    <?php if ($row['application_status'] == "Accepted" || $row['application_status'] == "Rejected") {?>    
                
                        <?php if($_SESSION['user_id'] == $getJobPostByID['employer_id']) {?>
                            <td class="action-td">
                                <form action="../../../core/handleForms.php" method="POST">
                                    <input type="hidden" name="application_id" value="<?php echo htmlspecialchars($row['application_id']); ?>">
                                    <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($_GET['post_id']); ?>">
                                    <button type="submit" class="applicant-action disabled" name="actionBtn" value="accept" disabled>Accept</button>
                                    <button type="submit" class="applicant-action disabled" name="actionBtn" value="reject" disabled>Reject</button>
                                </form>
                            </td>
                        <?php }?>
                    <?php } else {?>
                        <?php if($_SESSION['user_id'] == $getJobPostByID['employer_id']) {?>
                            
                            <?php if($_SESSION['user_id'] == $getJobPostByID['employer_id']) {?>
                                <td class="action-td">
                                    <form action="../../../core/handleForms.php" method="POST">
                                        <input type="hidden" name="application_id" value="<?php echo htmlspecialchars($row['application_id']); ?>">
                                        <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($_GET['post_id']); ?>">
                                        <button type="submit" class="applicant-action accept-btn" name="actionBtn" value="accept" onclick="confirmAction(event, 'accept')">Accept</button>
                                        <button type="submit" class="applicant-action reject-btn" name="actionBtn" value="reject" onclick="confirmAction(event, 'reject')">Reject</button>
                                    </form>
                                </td>
                            <?php }?>
                        <?php }?>
                    <?php }?>
            </tr>
            <?php } ?>
        </table>
    </section>

    <script>
        function confirmAction(event, action) {
            const message = `Are you sure you want to ${action} this application?`;
            if (!confirm(message)) {
                event.preventDefault(); 
            }
        }
    </script>
</body>
</html>
