<?php
  // Check variables
  session_start();
  if(empty($_POST['email']) || empty($_POST['role'])) {
    die("Form submission failed. Please, contact the administrator.");
  }
  // Check right to manage
  include($_SERVER['DOCUMENT_ROOT'] . 'login/app/authorize.php');
  if($auth_manage !== 1) {
    die("Access denied - missing permission (manage).");
  }
  // Restrict self change
  if($_POST['email'] == $auth_email) {
    die("Self role change not allowed.");
  }
  // Delete user / Change role
  if($_POST['role'] == "DEL") {
    $mysqli_role_update = $conn->prepare("DELETE FROM `auth_user` WHERE 1 != ? AND `email` = ?");
    $message = ('Dear user,' . "\n\n" . 'Your account has been deleted! If you did not expect this action, please contact us immediately.' . "\n\n" . '// This is an automated email. Please do not reply.');
  } else {
    $mysqli_role_update = $conn->prepare("UPDATE `auth_user` SET `role` = ? WHERE `email` = ?");
    $message = ('Dear user,' . "\n\n" . 'Your account has been updated! The following information has changed:' . "\n\n" . '- User role: ' . $_POST['role'] . "\n\n" . '// This is an automated email. Please do not reply.');
  }
  // Update
  $mysqli_role_update->bind_param('ss', $_POST['role'], $_POST['email']);
  $mysqli_role_update->execute();
  if($mysqli_role_update->affected_rows !== 1) {
    die("Update failed. Please, contact the administrator.");
  }
  $mysqli_role_update->close();
  $conn->close();
  // Send email and return success
  mail($_POST['email'], 'AuthApp - Your account', $message, 'From: ' . $admin . "\r\n");
  header('Location: ../admin.php');
  die();
?>
