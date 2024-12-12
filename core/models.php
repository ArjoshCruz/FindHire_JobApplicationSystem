<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Insert new employee to database
function insertNewEmployee($pdo, $email_address, $password) {
    $checkUserSql = "SELECT * FROM user_accounts
                    WHERE email_address = ?";
    $checkUserSqlStmt = $pdo->prepare($checkUserSql);
    $checkUserSqlStmt->execute([$email_address]);

    if ($checkUserSqlStmt->rowCount() == 0) {
        $sql = "INSERT INTO user_accounts (email_address, password, user_role) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $executeQuery = $stmt->execute([$email_address, $password, "Employee"]);

        if ($executeQuery) {
            $_SESSION['message'] = "Employee Successfully Inserted";
            return true;
        } else {
            $_SESSION['message'] = "An error occurred from the query"; 
        }
    } else {
        $_SESSION['message'] = "Email already exists"; 
    }
}

// Login employee
function loginEmployee($pdo, $email_address, $password) {
    $sql = "SELECT * FROM user_accounts WHERE email_address = ? AND user_role = 'Employee'";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$email_address]);

    if ($executeQuery) {
        $userInfoRow = $stmt->fetch();
        $email_addressFromDB = $userInfoRow['email_address'];
        $passwordFromDB = $userInfoRow['password'];
        $user_roleFromDB = $userInfoRow['user_role'];
        $_SESSION['user_id'] = $userInfoRow['user_id'];

        if ($password == $passwordFromDB) {
            $_SESSION['email_address'] = $email_addressFromDB;
            $_SESSION['user_role'] = $user_roleFromDB; // Dynamically assign user_role
            $_SESSION['message'] = "Login is successful";
            return true;
        }
    }
    return false; // Return false if login fails
}

// Insert new employer to database
function insertNewEmployer($pdo, $email_address, $password) {
    $checkUserSql = "SELECT * FROM user_accounts
                    WHERE email_address = ?";
    $checkUserSqlStmt = $pdo->prepare($checkUserSql);
    $checkUserSqlStmt->execute([$email_address]);

    if ($checkUserSqlStmt->rowCount() == 0) {
        $sql = "INSERT INTO user_accounts (email_address, password, user_role) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $executeQuery = $stmt->execute([$email_address, $password, "Employer"]);

        if ($executeQuery) {
            $_SESSION['message'] = "Employer Successfully Inserted";
            return true;
        } else {
            $_SESSION['message'] = "An error occurred from the query"; 
        }
    } else {
        $_SESSION['message'] = "Email already exists"; 
    }
}

// Login Employer
function loginEmployer($pdo, $email_address, $password) {
    $sql = "SELECT * FROM user_accounts WHERE email_address = ? AND user_role = 'Employer'";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$email_address]);

    if ($executeQuery) {
        $userInfoRow = $stmt->fetch();
        $email_addressFromDB = $userInfoRow['email_address'];
        $passwordFromDB = $userInfoRow['password'];
        $user_roleFromDB = $userInfoRow['user_role'];
        $_SESSION['user_id'] = $userInfoRow['user_id'];

        if ($password == $passwordFromDB) {
            $_SESSION['email_address'] = $email_addressFromDB;
            $_SESSION['user_role'] = $user_roleFromDB; // Dynamically assign user_role
            $_SESSION['message'] = "Login is successful";
            return true;
        }
    }
    return false; // Return false if login fails
}


// Fetching User Data
function getAllUser($pdo): mixed {
    $sql = "SELECT * FROM user_details";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute();

    if ($executeQuery) {
        return $stmt->fetchAll();
    }
}

function getUserByID($pdo, $user_id) {
    $sql = "SELECT * FROM user_details
            WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$user_id]);

    if ($executeQuery) {
        return $stmt->fetch();
    }
}

// Insert to Database
function insertIntoUserDetails($pdo, $first_name, $last_name, $age, $gender, $email_address, $contact_number, $home_address, $user_id) {
    $sql = "INSERT INTO user_details (first_name, last_name, age, gender, email_address, contact_number, home_address, user_id) 
            VALUES (?,?,?,?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);

    try {
        $executeQuery = $stmt->execute([$first_name, $last_name, $age, $gender, $email_address, $contact_number, $home_address, $user_id]);

        if ($executeQuery) {
            return true;
        } 
    } catch (Exception $e) {
        return ["message" => "Error: " . $e->getMessage(), "statusCode" => 500];
    }
}

// Edit User Details
function editUser($pdo, $first_name, $last_name, $age, $gender, $email_address, $contact_number, $home_address, $user_id) {
    $sql = "UPDATE user_details
            SET first_name = ?,
                last_name = ?,
                age = ?,
                gender = ?,
                email_address = ?,
                contact_number = ?,
                home_address = ?
            WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);

    try {
        $executeQuery = $stmt->execute([$first_name, $last_name, $age, $gender, $email_address, $contact_number, $home_address, $user_id]);

        if ($executeQuery) {
            return true;
        } 
    } catch (Exception $e) {
        return ["message" => "Error: " . $e->getMessage(), "statusCode" => 500];
    }
}

// Inserting Job to Database - EMPLOYER
function insertIntoJobPost($pdo, $job_title, $job_description, $user_id) {
    $sql = "INSERT INTO job_post (job_title, job_description, employer_id) 
            VALUES (?,?,?)";
    $stmt = $pdo->prepare($sql);

    try {
        $executeQuery = $stmt->execute([$job_title, $job_description, $user_id]);

        if ($executeQuery) {
            return true;
        } 
    } catch (Exception $e) {
        return ["message" => "Error: " . $e->getMessage(), "statusCode" => 500];
    }
}

// Fetching Data
function getAllJobPost($pdo): mixed {
    $sql = "SELECT 
                jp.post_id, 
                jp.job_title, 
                jp.job_description, 
                jp.date_posted, 
                ud.first_name, 
                ud.last_name 
            FROM job_post jp
            JOIN user_details ud ON jp.employer_id = ud.user_id
            ORDER BY jp.date_posted DESC
        ";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute();

    if ($executeQuery) {
        return $stmt->fetchAll();
    }
}

function getJobPostByID($pdo, $post_id) {
    $sql = "SELECT 
                jp.post_id, 
                jp.job_title, 
                jp.job_description, 
                jp.date_posted,
                jp.employer_id,
                ud.first_name, 
                ud.last_name 
            FROM job_post jp
            JOIN user_details ud ON jp.employer_id = ud.user_id
            WHERE post_id = ?";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$post_id]);

    if ($executeQuery) {
        return $stmt->fetch();
    }
}

function getJobPostByUserID($pdo, $user_id): mixed {
    $sql = "SELECT 
                jp.post_id, 
                jp.job_title, 
                jp.job_description, 
                jp.date_posted,
                jp.employer_id,
                ud.first_name, 
                ud.last_name 
            FROM job_post jp
            JOIN user_details ud ON jp.employer_id = ud.user_id
            WHERE jp.employer_id = ?";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$user_id]);

    if ($executeQuery) {
        return $stmt->fetchAll();
    }
    return []; // Return an empty array if the query fails
}


function searchJobPosts($pdo, $query) {
    $sql = "SELECT job_post.post_id, job_post.job_title, job_post.job_description, job_post.date_posted, 
                   user_details.first_name, user_details.last_name
            FROM job_post
            INNER JOIN user_details ON job_post.employer_id = user_details.user_id
            WHERE job_post.job_title LIKE ? OR job_post.job_description LIKE ?";
    $stmt = $pdo->prepare($sql);
    $searchTerm = '%' . $query . '%';
    $executeQuery = $stmt->execute([$searchTerm, $searchTerm]);

    if ($executeQuery) {
        return $stmt->fetchAll();
    }
    return [];
}

// Fetching Applicants Data
function getAllApplications($pdo) {
    $sql = "SELECT 
                a.application_id, 
                ud.first_name, 
                ud.last_name, 
                a.application_status, 
                a.date_sent 
            FROM applications a
            JOIN user_accounts ua ON a.employee_id = ua.user_id
            JOIN user_details ud ON ua.user_id = ud.user_id";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute();

    if ($executeQuery) {
        return $stmt->fetchAll();
    }
}

function getApplicationByID($pdo, $application_id) {
    $sql = "SELECT 
                a.application_id, 
                a.cover_letter, 
                a.resume_file, 
                a.application_status, 
                a.date_sent, 
                a.employee_id,
                ud.first_name, 
                ud.last_name, 
                jp.job_title 
            FROM applications a
            JOIN user_accounts ua ON a.employee_id = ua.user_id
            JOIN user_details ud ON ua.user_id = ud.user_id
            JOIN job_post jp ON a.post_id = jp.post_id
            WHERE a.application_id = ?";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$application_id]);

    if ($executeQuery) {
        return $stmt->fetch();
    }
}

function getApplicationByJobID($pdo, $post_id) {
    $sql = "SELECT 
                a.application_id, 
                a.cover_letter, 
                a.resume_file,
                a.employee_id,
                a.application_status, 
                a.date_sent, 
                ud.first_name, 
                ud.last_name, 
                jp.job_title 
            FROM applications a
            JOIN user_accounts ua ON a.employee_id = ua.user_id
            JOIN user_details ud ON ua.user_id = ud.user_id
            JOIN job_post jp ON a.post_id = jp.post_id
            WHERE a.post_id = ?";
    $stmt = $pdo->prepare($sql);

    $executeQuery = $stmt->execute([$post_id]);

    if ($executeQuery) {
        return $stmt->fetchAll();
    }
}


// Insert Application
function insertIntoApplications($pdo, $cover_letter, $resume_file, $application_status, $employee_id, $post_id) {

    $upload_dir = "../uploads/resumes/";

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $resume_filename = basename($resume_file['name']);
    $upload_path = $upload_dir . $resume_filename;

    if (move_uploaded_file($resume_file['tmp_name'], $upload_path)) {
        $sql = "INSERT INTO applications (cover_letter, resume_file, application_status, employee_id, post_id) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        try {
            $executeQuery = $stmt->execute([$cover_letter, $resume_filename, $application_status, $employee_id, $post_id]);

            if ($executeQuery) {
                return ["message" => "Application submitted successfully!", "statusCode" => 200];
            }
        } catch (Exception $e) {
            return ["message" => "Error: " . $e->getMessage(), "statusCode" => 500];
        }
    } else {
        return ["message" => "File upload failed!", "statusCode" => 500];
    }
}

// Update Action Application
function updateApplication($pdo, $pending) {
    $sql = "UPDATE applications
                SET  pending = ?
            WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);

    $executeQuery = $stmt->execute([$firstName, $lastName, $gender, $age, $experience, $job, $user_id]);

    if($executeQuery) {
        return true;
    }
}

// Update Application Status
function updateApplicationStatus($pdo, $applicationId, $status) {
    $sql = "UPDATE applications
            SET application_status = ?
            WHERE application_id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$status, $applicationId]);
}

function insertIntoMessages($pdo, $application_id, $sender_id, $message_content) {
    $sql = "INSERT INTO messages (application_id, sender_id, message_content) 
            VALUES (?,?,?)";
    $stmt = $pdo->prepare($sql);

    try {
        $executeQuery = $stmt->execute([$application_id, $sender_id, $message_content]);

        if ($executeQuery) {
            return true;
        } 
    } catch (Exception $e) {
        return ["message" => "Error: " . $e->getMessage(), "statusCode" => 500];
    }
}
function getMessageByApplicationID($pdo, $application_id){
    $sql = "SELECT
                m.message_id,
                m.application_id,
                m.sender_id,
                m.message_content,
                m.date_sent,
                CONCAT(ud.first_name, ' ', ud.last_name) AS sender_name
            FROM 
                messages m
            JOIN
                user_details ud ON m.sender_id = ud.user_id
            WHERE 
                m.application_id = ?"; 
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$application_id]); 

    if ($executeQuery) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }

    return [];
}

function updateJobPost($pdo, $job_title, $job_description, $post_id){
    $sql = "UPDATE job_post
                SET  job_title = ?, 
                     job_description = ?
            WHERE post_id = ?";
        $stmt = $pdo->prepare($sql);

        $executeQuery = $stmt->execute([$job_title, $job_description, $post_id]);
    
        if($executeQuery) {
            return true;
        }
}

function deletePost($pdo, $post_id){
    $sql = "DELETE FROM job_post
            WHERE post_id = ?";
    $stmt = $pdo->prepare($sql);

    $executeQuery = $stmt->execute([$post_id]);

    if ($executeQuery){
        return true;
    }
}