<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'dbConfig.php';
require_once 'models.php';
require_once 'validate.php';

if (isset($_POST['registerEmployeeBtn'])) {
    $email_address = sanitizeInput($_POST['email_address']);
    $password = sanitizeInput($_POST['password']);
    $confirm_password = sanitizeInput($_POST['confirm_password']);

    if (!empty($email_address) && !empty($password) && !empty($confirm_password)) {
        
        if ($password == $confirm_password) {

			if (validatePassword($password)) {

				$insertQuery = insertNewEmployee($pdo, $email_address, sha1($password));
                
                // Get the user_id from the last insert operation
                $user_id = $pdo->lastInsertId(); 

                // Inserting into user details
                $insertIntoUserDetails = insertIntoUserDetails($pdo, "-", "-", 0, "-", $email_address, "-", "-", $user_id);

				if ($insertQuery && $insertIntoUserDetails) {
					header("Location: ../user-authentication/employee/login.php");
				}
				else {
					header("Location: ../user-authentication/employee/register.php");
				}
			}

			else {
				$_SESSION['message'] = "Password should be more than 8 characters and should contain both uppercase, lowercase, and numbers";
				header("Location: ../user-authentication/employee/register.php");
			}
		}

		else {
			$_SESSION['message'] = "Please check if both passwords are equal!";
			header("Location: ../user-authentication/employee/register.php");
		}
    } else {
        $_SESSION['message'] = "Please make sure the input fields are not empty for registration!";
        header("Location: ../user-authentication/employee/register.php");
    }
}


if (isset($_POST['loginEmployeeBtn'])) {
    $email_address = sanitizeInput($_POST['email_address']);
    $password = sha1($_POST['password']); 

    if (!empty($email_address) && !empty($password)) {
        $loginQuery = loginEmployee($pdo, $email_address, $password);

        if ($loginQuery) {
            if ($_SESSION['user_role'] === "Employee") {
                header("Location: ../index.php"); // Redirect Employee to their dashboard
            } elseif ($_SESSION['user_role'] === "Employer") {
                header("Location: ../user-authentication/authentication.php"); // Redirect Employer to their dashboard
            }
            exit;
        } else {
            $_SESSION['message'] = "Email/Password invalid";
            header("Location: ../user-authentication/employee/login.php");
            exit;
        }
    } else {
        $_SESSION['message'] = "Please make sure the input fields are not empty for the login!";
        header("Location: ../user-authentication/employee/login.php");
        exit;
    }
}


// Register Button for Employer
if (isset($_POST['registerEmployerBtn'])) {
    $email_address = sanitizeInput($_POST['email_address']);
    $password = sanitizeInput($_POST['password']);
    $confirm_password = sanitizeInput($_POST['confirm_password']);

    if (!empty($email_address) && !empty($password) && !empty($confirm_password)) {
        
        if ($password == $confirm_password) {

			if (validatePassword($password)) {

				$insertQuery = insertNewEmployer($pdo, $email_address,  sha1($password));
                
                // Get the user_id from the last insert operation
                $user_id = $pdo->lastInsertId(); 

                // Inserting into user details
                $insertIntoUserDetails = insertIntoUserDetails($pdo, "-", "-", 0, "-", $email_address, "-", "-", $user_id);

                if ($insertQuery && $insertIntoUserDetails) {
					header("Location: ../user-authentication/hr/login.php");
				}
				else {
					header("Location: ../user-authentication/hr/register.php");
				}
			}

			else {
				$_SESSION['message'] = "Password should be more than 8 characters and should contain both uppercase, lowercase, and numbers";
				header("Location: ../user-authentication/hr/register.php");
			}
		}

		else {
			$_SESSION['message'] = "Please check if both passwords are equal!";
			header("Location: ../user-authentication/hr/register.php");
		}
    } else {
        $_SESSION['message'] = "Please make sure the input fields are not empty for registration!";
        header("Location: ../user-authentication/hr/register.php");
    }
} 

if (isset($_POST['loginEmployerBtn'])) {
    $email_address = sanitizeInput($_POST['email_address']);
    $password = sha1($_POST['password']); 

    if (!empty($email_address) && !empty($password)) {
        $loginQuery = loginEmployer($pdo, $email_address, $password);

        if ($loginQuery) {
            if ($_SESSION['user_role'] === "Employer") {
                header("Location: ../index.php"); // Redirect Employer to their dashboard
            } elseif ($_SESSION['user_role'] === "Employee") {
                header("Location: ../user-authentication/authentication.php"); // Redirect Employee to their dashboard
            }
            exit;
        } else {
            $_SESSION['message'] = "email_address/password invalid";
            header("Location: ../user-authentication/hr/login.php");
            exit;
        }
    } else {
        $_SESSION['message'] = "Please make sure the input fields are not empty for the login!";
        header("Location: ../user-authentication/hr/login.php");
        exit;
    }
}

if (isset($_GET['logoutAUser'])) {
    session_unset();
    session_destroy();
    header("Location: ../user-authentication/authentication.php");
    exit;
}

if (isset($_POST['editUserBtn'])) {
    $user_id = $_SESSION['user_id'];

    $first_name = sanitizeInput($_POST['firstName']);
    $last_name = sanitizeInput($_POST['lastName']);
    $age = sanitizeInput($_POST['age']);
    $gender = sanitizeInput($_POST['gender']);
    $email_address = sanitizeInput($_POST['email_address']);
    $contact_number = sanitizeInput($_POST['contact_number']);
    $home_address = sanitizeInput($_POST['home_address']);

    $response = editUser($pdo, $first_name, $last_name, $age, $gender, $email_address, $contact_number, $home_address, $user_id);
    
    $_SESSION['message'] = $response['message'];
    $_SESSION['statusCode'] = $response['statusCode'];

    header("Location: ../actions/profile/profile.php?user_id=" . $user_id);
    exit();
}

// Create job - EMPLOYER

if (isset($_POST['insertJobBtn'])) {
    $user_id = $_SESSION['user_id'];

    // Ensure these match the names in the form
    $job_title = sanitizeInput($_POST['job-title']);
    $job_description = sanitizeInput($_POST['job-description']);

    // Call the editUser function with these sanitized inputs
    $response = insertIntoJobPost($pdo, $job_title, $job_description, $user_id);
    
    // Assuming $response is either a success message or an array with status and message
    $_SESSION['message'] = $response['message'];
    $_SESSION['statusCode'] = $response['statusCode'];

    // Redirect to the profile page after updating
    header("Location: ../index.php");
    exit();
}


// Apply Job - Employee
if (isset($_POST['insertJobApplication'])) {
    $employee_id = $_SESSION['user_id'];
    $post_id = $_GET['post_id'];
    $cover_letter = sanitizeInput($_POST['cover_letter']);
    $resume_file = $_FILES['resume_file'];
    $application_status = 'Pending';

    $response = insertIntoApplications($pdo, $cover_letter, $resume_file, $application_status, $employee_id, $post_id);
    
    $_SESSION['message'] = $response['message'];
    $_SESSION['statusCode'] = $response['statusCode'];

    header("Location: ../actions/view-job/employee/view-job.php?post_id=" . $post_id);
    exit();
}



if (isset($_POST['actionBtn'])) {
    $action = $_POST['actionBtn']; 
    $applicationId = $_POST['application_id'];
    $postId = $_POST['post_id'];

    if ($action === 'accept' || $action === 'reject') {
        $status = ($action === 'accept') ? 'Accepted' : 'Rejected';

        $updated = updateApplicationStatus($pdo, $applicationId, $status);

        if ($updated) {
            $_SESSION['message'] = "Application has been {$status}.";
            $_SESSION['statusCode'] = 'success';
        } else {
            $_SESSION['message'] = "Failed to update application status.";
            $_SESSION['statusCode'] = 'error';
        }

        header("Location: ../actions/view-job/hr/view-job.php?post_id=" . $postId);
        exit();
    }
}

// Insert Message to DB
if (isset($_POST['sendMessage'])) {

    $application_id = $_GET['application_id'];
    $sender_id = $_SESSION['user_id'];
    $message_content = $_POST['message_content']; 

    $response = insertIntoMessages($pdo, $application_id, $sender_id, $message_content);
    
    $_SESSION['message'] = $response['message'];
    $_SESSION['statusCode'] = $response['statusCode'];

    header("Location: ../actions/messages.php?post_id=" . $_GET['post_id'] . "&application_id=" . $application_id);
    exit();

}

// Edit Job
if(isset($_POST['editJobBtn'])){
    $post_id = $_GET['post_id'];
    $job_title = $_POST['job_title'];
    $job_description = $_POST['job_description'];

    $query = updateJobPost($pdo, $job_title, $job_description, $post_id);

    if($query){
        header("Location: ../employer-files/job-list.php");
    } else{
        echo "Update Failed";
    }
}
// Delete Job
if(isset($_POST['deleteJobBtn'])) {
    $query = deletePost($pdo, $_GET['post_id']);

    if ($query) {
        header("Location: ../employer-files/job-list.php");
    } else {
        echo "Deletion Failed";
    }
}


?>
