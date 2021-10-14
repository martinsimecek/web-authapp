<?php
  // Check login
  session_start();
  if(empty($_SESSION['authUser'])) {
    die('Access denied - user not <a href="' . $_SERVER['DOCUMENT_ROOT'] . 'login/">logged in</a>.');
  }
  // Check right to read
  include($_SERVER['DOCUMENT_ROOT'] . 'login/app/config.php');
  $mysqli_auth_select = $conn->prepare("SELECT u.user AS 'auth_user', u.created_on AS 'auth_created_on', u.created_by AS 'auth_created_by', u.email AS 'auth_email', u.role AS 'auth_role', r.read AS 'auth_read', r.edit AS 'auth_edit', r.manage AS 'auth_manage' FROM `auth_user` u LEFT OUTER JOIN `auth_role` r ON u.role = r.role WHERE u.user = ?");
  $mysqli_auth_select->bind_param('s', $_SESSION['authUser']);
  $mysqli_auth_select->execute();
  $mysqli_auth_select->bind_result($auth_user , $auth_created_on, $auth_created_by, $auth_email, $auth_role, $auth_read, $auth_edit, $auth_manage);
  $mysqli_auth_select->fetch();
  $mysqli_auth_select->close();
  if($auth_read !== 1) {
    die("Access denied - missing permission (read).");
  }
?>
