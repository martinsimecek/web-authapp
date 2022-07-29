<?php
  // Check variables
  session_start();
  if(empty($_POST['password1']) || empty($_POST['password2'])) {
    die("Form submission failed. Please, contact the administrator.");
  }
  // Check right to edit
  include($_SERVER['DOCUMENT_ROOT'] . 'login/app/authorize.php');
  if($auth_edit !== 1) {
    die("Access denied - missing permission (edit).");
  }
  // Verify new password
  if((strlen($_POST['password1'])<8) || (strlen($_POST['password1'])>20)) {
    die("Password length rules were not met.");
  }
  if($_POST['password1'] !== $_POST['password2']) {
    die("Password confirmation does not match.");
  }
  // Change password
  $mysqli_pass_update = $conn->prepare("UPDATE `auth_user` SET `password` = ? WHERE `user` = ?");
  $mysqli_pass_update->bind_param('ss', password_hash($_POST['password1'], PASSWORD_DEFAULT), $auth_user);
  $mysqli_pass_update->execute();
  if($mysqli_pass_update->affected_rows !== 1) {
    die("Update failed. Please, contact the administrator.");
  }
  $mysqli_pass_update->close();
  $conn->close();
  // Send email and return success
  mail($auth_email, 'AuthApp - Your account', 'Dear user,' . "\n\n" . 'Your account has been updated! The following information has changed:' . "\n\n" . '- Password: <secret>' . "\n\n" . '// This is an automated email. Please do not reply.', 'From: ' . $admin . "\r\n");
  header('Location: logout.php');
  die();
?>
