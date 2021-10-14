# AuthApp
Authentication Application

## Basic info
- Owner: Martin Simecek
- Contact: admin@martinsimecek.cz

## About
This is the core module for personal web applications. It is secure authentication and authorization tool where users can review their personal information, list login attempts, or change your password. Administrators can also list, create, update or delete accounts of other users.

## Setup
Database structure can be created with SQL commands in **db.sql** file. The database connection also requires the **_config.php** file in the root folder with following code:

    <?php
      // Configurate connection
      $db_host = "127.0.0.1";
      $db_username = "<username>";
      $db_password = "<password>";
      $db_database = "<database>";
      // Create connection
      $conn = mysqli_connect($db_host, $db_username, $db_password, $db_database);
      if (mysqli_connect_errno()) {
        die('Failed to connect to MySQL: '.mysqli_connect_error());
      }
    ?>

## Structure
The app consists of signin, user and admin webpage. Data tables, sessions and variables used in this app are listed bellow.

### SQL Tables
- **auth_user** - List of users and their personal information
    - (PK) **user** CHAR(36)
    - **created_on** TIMESTAMP
    - **created_by** CHAR(36)
    - **email** VARCHAR(255)
    - **password** VARCHAR(255)
    - **role** VARCHAR(255)
- **auth_login** - Incremental table of successful logins
    - (PK) **id** INT
    - **created_on** TIMESTAMP
    - **created_by** CHAR(36)
    - **ip_address** VARCHAR(45)
- **auth_role** - List of user roles
    - (PK) **group** CHAR(3)
    - **group_name** VARCHAR(50)
    - **access** TINYINT(1)
    - **modify** TINYINT(1)
    - **manage** TINYINT(1)

### Sessions
- **authUser** - User number

### Variables
- **$conn** - Database connection string
- **$db_database** - Parameter for database connection
- **$db_host** - Parameter for database connection
- **$db_password** - Parameter for database connection
- **$db_username** - Parameter for database connection
- **$gen_alphabet** - Parameter for password generator
- **$gen_alphabet_length** - Parameter for password generator
- **$gen_loop_i** - Parameter for password generator
- **$gen_loop_x** - Parameter for password generator
- **$gen_password** - Randomly generated password
- **$register_password** - New password from password generator
- **$register_password_hash** - Hash of the new password
- **$mysqli_register_select** - DB query - checks for email duplicity
- **$mysqli_register_insert** - DB query - inserts new user
- **$mysqli_pass_update** - DB query - updates password
- **$mysqli_role_update** - DB query - updates/deletes user
- **$mysqli_auth_select** - DB query - selects data for authentication
- **$mysqli_auth_insert** - DB query - inserts successful login
- **$message** - Email body of change_pass app
- **$auth_user** - User identification (user number)
- **$auth_createdon** - User identification (created on)
- **$auth_createdby** - User identification (created by)
- **$auth_email** - User identification (email address)
- **$auth_password** - User identification (password)
- **$auth_role** - User identification (role)
- **$auth_read** - User identification (right to access)
- **$auth_edit** - User identification (right to modify)
- **$auth_manage** - User identification (right to manage)
- **$users_mysqli** - Select users (database query)
- **$users_result** - Select users (query result)
- **$users_row** - Select users (result row)
- **$logins_mysqli** - Select logins (database query)
- **$logins_result** - Select logins (query result)
- **$logins_row** - Select logins (result row)
- **stats_mysqli** - Select stats (database query)
- **stats_result1** - Select stats (query result 1)
- **stats_result2** - Select stats (query result 2)
- **stats_result3** - Select stats (query result 3)
- **stats_result4** - Select stats (query result 4)
