<?php
  // Check variables
  session_start();
  if(empty($_POST['email']) || empty($_POST['password']) || empty($_POST['source'])) {
    die("Form submission failed. Please, contact the administrator.");
  }
  // Check user
  include($_SERVER['DOCUMENT_ROOT'] . 'login/app/config.php');
  $mysqli_auth_select = $conn->prepare("SELECT u.user, u.password, r.read FROM `auth_user` u LEFT OUTER JOIN `auth_role` r ON u.role = r.role WHERE u.email = ?");
  $mysqli_auth_select->bind_param('s', $_POST['email']);
  $mysqli_auth_select->execute();
  $mysqli_auth_select->store_result();
  if($mysqli_auth_select->num_rows !== 1) {
    die("Incorrect email address. Please, try it again.");
  }
  $mysqli_auth_select->bind_result($auth_user, $auth_password, $auth_read);
  $mysqli_auth_select->fetch();
  $mysqli_auth_select->close();
  // Check password
  if(!password_verify($_POST['password'], $auth_password)) {
    die("Incorrect password. Please, try it again.");
  }
  // Check right to read
  if($auth_read !== 1) {
    die("Access denied - missing permission (read).");
  }
  // Successful login
  session_regenerate_id();
  $_SESSION['authUser'] = $auth_user;
  $mysqli_auth_insert = $conn->prepare("INSERT INTO `auth_login` (`created_by`, `ip_address`) VALUES (?, ?)");
  $mysqli_auth_insert->bind_param('ss', $auth_user, $_SERVER['REMOTE_ADDR']);
  $mysqli_auth_insert->execute();
  $mysqli_auth_insert->close();
  $conn->close();
  // Redirect
  header('Location: ' . $_POST['source']);
  die();
?>
