# AuthApp
Authentication Application

## Basic info
- Owner: Martin Simecek
- Contact: hello@martinsimecek.cz

## About
This is the core module for personal web apps. It is an authentication and authorization tool where users can review their personal details, list logins, or change their password. Administrators can also list, create, update or delete another users.

## Setup
Database can be created with SQL commands in **db.sql** file. The database connection also requires the **config.php** file in the **app** folder with following code:

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
      // Set administrator email address
      $admin = "<email>";
    ?>

## Structure
The app consists of index (login), user and admin webpage. Data tables used in this app are listed bellow.

### SQL Tables
- **auth_login** - Incremental table of successful logins
    - (PK) **id** INT
    - **created_on** TIMESTAMP
    - **created_by** CHAR(36)
    - **ip_address** VARCHAR(45)
- **auth_user** - List of users and their personal information
    - (PK) **user** CHAR(36)
    - **created_on** TIMESTAMP
    - **created_by** CHAR(36)
    - **email** VARCHAR(255)
    - **password** VARCHAR(255)
    - **role** VARCHAR(255)
- **auth_role** - List of user roles
    - (PK) **role** VARCHAR(255)
    - **read** TINYINT(1)
    - **edit** TINYINT(1)
    - **manage** TINYINT(1)
