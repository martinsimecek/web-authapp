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
  // Check email duplicity
  $mysqli_register_select = $conn->prepare("SELECT `email` FROM `auth_user` WHERE `email` = ?");
  $mysqli_register_select->bind_param('s', $_POST['email']);
  $mysqli_register_select->execute();
  $mysqli_register_select->store_result();
  if($mysqli_register_select->num_rows > 0) {
    die("Email address is already in use.");
  }
  $mysqli_register_select->close();
  // Generate random password
  function RandomPassword() {
    $gen_alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $gen_password = array();
    $gen_alphabet_length = strlen($gen_alphabet) - 1;
    for($gen_loop_i = 0; $gen_loop_i < 8; $gen_loop_i++) {
      $gen_loop_x = rand(0, $gen_alphabet_length);
      $gen_password[] = $gen_alphabet[$gen_loop_x];
    }
    return implode($gen_password);
  }
  $register_password = RandomPassword();
  $register_password_hash = password_hash($register_password, PASSWORD_DEFAULT);
  // Register user
  $mysqli_register_insert = $conn->prepare("INSERT INTO `auth_user` (`user`, `created_by`, `email`, `password`, `role`) VALUES (uuid(), ?, ?, ?, ?)");
  $mysqli_register_insert->bind_param('ssss', $auth_user, $_POST['email'], $register_password_hash, $_POST['role']);
  $mysqli_register_insert->execute();
  if($mysqli_register_insert->affected_rows !== 1) {
    die("Update failed. Please, contact the administrator.");
  }
  $mysqli_register_insert->close();
  $conn->close();
  // Send email and return success
  mail($_POST['email'], 'AuthApp - Your account', 'Dear user,' . "\n\n" . 'Your account has been created! You can now log in to https://login.martinsimecek.cz/ with following credentials:' . "\n\n" . '- Email address: ' . $_POST['email'] . "\n" . '- Password: ' . $register_password . "\n\n" . 'Please, do not share your password with anyone else.' . "\n\n" . '// This is an automated email. Please do not reply.', 'From: admin@martinsimecek.cz' . "\r\n");
  header('Location: ../admin.php');
  die();
?>
