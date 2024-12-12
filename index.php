<?php 
    require_once 'core/dbConfig.php';
    require_once 'core/models.php';

    if (!isset($_SESSION['email_address'])) {
        header("Location: user-authentication/authentication.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Hire</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/main-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="styles/header/main-header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

    <?php if (isset($_SESSION['email_address'])) {?>
        <nav class="admin-action">
            <div class="left-side">
                <a href="index.php" class="header-link">
                    <img src="img/logo.png" alt="FindHire-Logo">
                </a>

                <?php $getUserByID = getUserByID($pdo, $_SESSION['user_id']);?>
                <p class="hello">Hello, <span><?php echo $getUserByID['first_name']?></span></p>
            </div>


            <?php 
                if (isset($_SESSION['email_address'])) {
                    $getAllUser = getAllUser($pdo);
                ?>
                    <ul>
                        <li>
                            <a href="index.php">Home</a>
                        </li>
                        <li>
                            <?php if (isset($_SESSION['user_id'])) { ?>
                                <a href="actions/profile/profile.php?user_id=<?php echo $_SESSION['user_id']; ?>">Profile</a>
                            <?php } ?>
                        </li>

                        <?php if ($_SESSION['user_role'] === "Employer") { ?>
                            <li>
                                <a href="employer-files/job-list.php">Job List</a>
                            </li>
                        <?php }?>


                        <li>
                            <a href="core/handleForms.php?logoutAUser=1">Logout</a>
                        </li>
                    </ul>
                <?php 
                }
            } else { 
                echo "<h1>No user Logged in</h1>";
            } 
            ?>
        </nav>
 

    <!-- Landing Page -->
    <?php if ($_SESSION['user_role'] === "Employee") { ?>
    <section class="landing-page">
        <h2>Bridging Talent and Employers for <br> Online Jobs in the Philippines.</h2>
        <h4>Looking for <span>Work</span>?</h4>
        <div class="search-bar">
            <form action="index.php" method="GET">
                <div class="search-container">
                    <span class="search-icon"><i class="fa fa-search"></i></span>
                    <input type="text" name="query" placeholder="Search Jobs" value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>" />
                    <button type="submit" class="search-button" value="Search">Search</button>
                </div>
            </form>
        </div>
        <a href="index.php" class="clear-button">Reload Job Feed</a>
    </section>
    <?php } else { ?>
    <section class="landing-page">
        <h2>Creating Opportunities by Connecting <br> Employers with Top Talent in the <br> Philippines.</h2>
        <h4>Create a <span>Job</span>?</h4>
        <?php if (isset($_SESSION['user_id'])) { ?>
            <a href="employer-files/create-job.php?user_id=<?php echo $_SESSION['user_id']; ?>" class="create-button">Create</a>
        <?php } ?>

    </section>
    <?php } ?>

<!-- ********************************************************************************************************* -->
<?php
// Check if a search query exists
$searchResults = [];
if (isset($_GET['query']) && !empty($_GET['query'])) {
    $searchQuery = htmlspecialchars($_GET['query']);
    $searchResults = searchJobPosts($pdo, $searchQuery);
} else {
    $searchResults = getAllJobPost($pdo);
}

// Display search results or default job feed
    if (!empty($searchResults)) {
        echo "<h2 style='text-align: center; font-size: 36px; padding: 30px 0;'><u>Job Feed</u></h2>";
        
    ?>
        <div class="job-post-container">
            <?php foreach ($searchResults as $row) { ?>
            <div class="job-post">
                <div class="job-title">
                    <h3>
                        <span><?php echo htmlspecialchars($row['post_id']); ?> . </span>
                        <?php echo htmlspecialchars($row['job_title']); ?>
                    </h3>
                </div>
                <div class="job-info">
                    <p>
                        <span>
                            <?php echo htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']); ?>
                        </span>
                        · <?php echo htmlspecialchars($row['date_posted']); ?>
                    </p>
                </div>
                <div class="job-description">
                    <p><?php echo htmlspecialchars($row['job_description']); ?></p>
                </div>
                <div class="view-job">
                    
                    <?php if ($_SESSION['user_role'] === "Employee") { ?>
                        <a href="actions/view-job/employee/view-job.php?post_id=<?php echo $row['post_id']; ?>">View Job</a>
                    <?php } else {?>
                        <a href="actions/view-job/hr/view-job.php?post_id=<?php echo $row['post_id']; ?>">View Job</a>
                    <?php }?>
                </div>
            </div>
            <?php } ?>
        </div>
    <?php
    } else {
        echo "<h2 style='text-align: center; font-size: 36px; padding: 30px 0;'><u>Job Feed</u></h2>";
        echo "<p style='text-align: center; padding: 0 0 10px'>No job posts found.</p>";
    }
    ?>

</body>
</html>