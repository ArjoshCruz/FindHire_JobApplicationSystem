CREATE TABLE user_accounts (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    email_address VARCHAR(50) UNIQUE,
    password VARCHAR(255) NOT NULL,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_role ENUM('Employer', 'Employee') NOT NULL
);

CREATE TABLE user_details (
    user_id INT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    age INT,
    gender ENUM('Male', 'Female', 'Other'),
    email_address VARCHAR(50) UNIQUE,
    contact_number VARCHAR(15),
    home_address VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES user_accounts(user_id) ON DELETE CASCADE
);

CREATE TABLE job_post (
    post_id INT AUTO_INCREMENT PRIMARY KEY,
    employer_id INT NOT NULL,
    job_title VARCHAR(50) NOT NULL,
    job_description TEXT NOT NULL,
    date_posted TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employer_id) REFERENCES user_details(user_id) ON DELETE CASCADE
);

CREATE TABLE applications (
  application_id INT AUTO_INCREMENT PRIMARY KEY,
  employee_id INT NOT NULL,
  post_id INT NOT NULL,
  cover_letter TEXT NOT NULL,
  resume_file VARCHAR(512) NOT NULL,
  application_status ENUM('Pending', 'Accepted', 'Rejected') NOT NULL,
  date_sent TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  FOREIGN KEY (employee_id) REFERENCES user_accounts(user_id) ON DELETE CASCADE,
  FOREIGN KEY (post_id) REFERENCES job_post(post_id) ON DELETE CASCADE
);

CREATE TABLE messages (
  message_id INT AUTO_INCREMENT PRIMARY KEY,
  application_id INT NOT NULL,
  sender_id INT NOT NULL,
  message_content TEXT NOT NULL,
  date_sent TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  FOREIGN KEY (application_id) REFERENCES applications(application_id) ON DELETE CASCADE,
  FOREIGN KEY (sender_id) REFERENCES user_accounts(user_id) ON DELETE CASCADE
);
