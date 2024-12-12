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
    <?php 
    // Ensure post_id is set and valid
    if (isset($_GET['post_id']) && !empty($_GET['post_id'])) {
        $getJobPostbyID = getJobPostByID($pdo, $_GET['post_id']);

        if ($getJobPostbyID) {
    ?>
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
                <a href="employer-files/job-list.php">Job List</a>
            </li>
            <?php }?>
            <li>
                <a href="../core/handleForms.php?logoutAUser=1">Logout</a>
            </li>
        </ul>
    </header>
    <?php 
        } else {
            echo "<h1>Job post not found.</h1>"; 
        }
    } else {
        echo "<h1>No valid post ID provided.</h1>";
    }
    ?>

    <section class="messages-container">
        <?php $getMessageByApplicationID = getMessageByApplicationID($pdo, $_GET['application_id']);?>
        <div class="main-messages-container">
            <?php foreach ($getMessageByApplicationID as $row) { ?>
                <div class="<?php echo ($_SESSION['user_id'] == $row['sender_id']) ? 'sender' : 'receiver'; ?>">
                    <h2 class="sender-name"><?php echo $row['sender_name'];?></h2>
                    <h4 class="sender-date"><?php echo $row['date_sent'];?></h4>
                    <h4 class="sender-message"><?php echo $row['message_content'];?></h4>
                </div>
            <?php } ?>
        </div>
    </section>

                
    <section>
        <div class="messages-container">
            <form class="message-area-wrapper" action="../core/handleForms.php?post_id=<?php echo $_GET['post_id'];?>&application_id=<?php echo $_GET['application_id']?>" method="POST">
                <textarea class="message-area" name="message_content" rows="3" placeholder="Type your message here..." required></textarea>                
                <input class="message-btn" class="submit-form" type="submit" name="sendMessage" value="Send">
            </form>
        </div>
    </section>

    <script>
        const messagesContainer = document.querySelector('.messages-container');

        function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    </script>
</body>
</html>
